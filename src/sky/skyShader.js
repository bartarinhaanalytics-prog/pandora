/*
 * آسمان شب دردونه: ماه، ستاره‌ها، کهکشان راه شیری و ابرهای نازک مهتابی.
 * همه‌چیز در یک فرگمنت‌شیدر رسم می‌شود؛ هیچ مدل سه‌بعدی یا تصویری لازم نیست.
 * uScroll (۰ تا ۱): دوربین با پیمایش از آسمان به سمت افق پایین می‌آید؛ ماه بالا می‌رود و از قاب بیرون می‌شود
 * و لایه‌های ستاره با سرعت‌های متفاوت (عمق) جابه‌جا می‌شوند.
 */
export const vertex = `
attribute vec2 p;
void main() { gl_Position = vec4(p, 0.0, 1.0); }
`;

export const fragment = `
precision highp float;
uniform vec2 uRes;
uniform float uTime;
uniform float uScroll;

float h21(vec2 p) {
  p = fract(p * vec2(123.34, 456.21));
  p += dot(p, p + 45.32);
  return fract(p.x * p.y);
}
vec2 h22(vec2 p) {
  float n = h21(p);
  return vec2(n, h21(p + n * 17.17));
}
float noise(vec2 p) {
  vec2 i = floor(p), f = fract(p);
  vec2 u = f * f * (3.0 - 2.0 * f);
  return mix(mix(h21(i), h21(i + vec2(1, 0)), u.x), mix(h21(i + vec2(0, 1)), h21(i + vec2(1, 1)), u.x), u.y);
}
float fbm(vec2 p) {
  float v = 0.0, a = 0.5;
  mat2 r = mat2(0.8, -0.6, 0.6, 0.8);
  for (int i = 0; i < 6; i++) { v += a * noise(p); p = r * p * 2.03 + 3.1; a *= 0.5; }
  return v;
}
float fbm3(vec2 p) {
  float v = 0.0, a = 0.5;
  mat2 r = mat2(0.8, -0.6, 0.6, 0.8);
  for (int i = 0; i < 3; i++) { v += a * noise(p); p = r * p * 2.03 + 3.1; a *= 0.5; }
  return v / 0.875;
}

// یک لایه ستاره: هر خانهٔ شبکه حداکثر یک ستاره دارد
vec3 stars(vec2 uv, float scale, float density, float t, float boost) {
  vec2 g = uv * scale;
  vec2 id = floor(g);
  vec2 f = fract(g) - 0.5;
  vec3 col = vec3(0.0);
  for (int y = -1; y <= 1; y++) for (int x = -1; x <= 1; x++) {
    vec2 o = vec2(float(x), float(y));
    vec2 cid = id + o;
    float n = h21(cid);
    if (n > density) continue;
    vec2 pos = o + (h22(cid) - 0.5) * 0.8;
    float d = length(f - pos);
    float mag = pow(h21(cid + 7.7), 6.0);
    float tw = 0.75 + 0.25 * sin(t * (1.0 + 2.5 * h21(cid + 3.3)) + n * 60.0);
    float core = smoothstep(0.06 + 0.05 * mag, 0.0, d);
    float glow = exp(-d * (22.0 - 12.0 * mag)) * mag;
    float temp = h21(cid + 1.9);
    vec3 tint = temp < 0.25 ? vec3(0.75, 0.84, 1.0) : temp > 0.85 ? vec3(1.0, 0.86, 0.68) : vec3(1.0, 0.98, 0.94);
    col += tint * (core * (0.25 + 0.95 * mag) + glow * 0.9) * tw * boost;
  }
  return col;
}

void main() {
  vec2 frag = gl_FragCoord.xy;
  float aspect = uRes.x / uRes.y;
  vec2 uv = frag / uRes.y;           // y از ۰ (پایین) تا ۱ (بالا)
  float t = uTime;
  float lift = uScroll;              // ۰: آسمان بالا، ۱: نزدیک افق

  // گرادیان آسمان: نیلی عمیق در بالا، آبی مهتابی نزدیک افق
  float y = uv.y - 0.25 * lift;
  vec3 top = vec3(0.006, 0.009, 0.028);
  vec3 mid = vec3(0.022, 0.034, 0.095);
  vec3 low = vec3(0.075, 0.095, 0.21);
  vec3 col = mix(low, mid, smoothstep(0.0, 0.55, y));
  col = mix(col, top, smoothstep(0.5, 1.3, y));

  // ماه سمت چپ بالا، روبه‌روی متن راست‌چین؛ در صفحه‌های باریک کوچک‌تر و بالاتر
  bool tall = aspect < 0.8;
  vec2 moonC = tall ? vec2(aspect * 0.2, 0.86 + lift * 1.6) : vec2(aspect * 0.24, 0.78 + lift * 1.6);
  float moonR = tall ? 0.048 : 0.085;
  vec2 mp = uv - moonC;
  float md = length(mp);

  // راه شیری: نوار مورب نرم با غبار تیره
  vec2 sp = uv - vec2(0.0, lift * 0.6);
  float band = exp(-pow((sp.y - 0.62 + (sp.x - aspect * 0.5) * 0.42) * 3.2, 2.0));
  float dust = fbm(sp * 3.0 + vec2(0.0, 1.7));
  float lanes = smoothstep(0.45, 0.75, fbm(sp * 6.0 + 11.0));
  col += vec3(0.17, 0.18, 0.32) * band * dust * 0.75 * (1.0 - lanes * 0.7);
  col += vec3(0.34, 0.28, 0.40) * band * pow(dust, 3.0) * 0.55;

  // ستاره‌ها در سه عمق؛ لایهٔ نزدیک با پیمایش بیشتر جابه‌جا می‌شود
  float moonMask = smoothstep(moonR * 1.05, moonR * 3.5, md);
  vec3 st = vec3(0.0);
  st += stars(uv - vec2(0.0, lift * 0.3), 90.0, 0.35 + band * 0.6, t, 0.45 + band * 1.1);
  st += stars(uv - vec2(0.0, lift * 0.6) + 3.7, 42.0, 0.35, t * 0.8, 0.9);
  st += stars(uv - vec2(0.0, lift * 1.1) + 9.1, 16.0, 0.32, t * 0.6, 1.3);
  float horizonFade = smoothstep(0.02, 0.32, uv.y - lift * 0.15);
  col += st * moonMask * horizonFade;

  // شهاب: هر ۱۱ ثانیه یک بار، کوتاه و آرام
  float cyc = floor(t / 11.0);
  float ph = fract(t / 11.0);
  if (ph < 0.09) {
    vec2 s0 = vec2(aspect * (0.45 + 0.4 * h21(vec2(cyc, 1.0))), 0.95 - 0.2 * h21(vec2(cyc, 2.0)));
    vec2 dir = normalize(vec2(-0.85, -0.42));
    float k = ph / 0.09;
    vec2 head = s0 + dir * k * 0.55;
    vec2 rel = uv - head;
    float along = dot(rel, -dir);
    float across = length(rel + dir * along);
    float tail = smoothstep(0.22, 0.0, along) * step(0.0, along);
    col += vec3(0.85, 0.9, 1.0) * tail * smoothstep(0.0025, 0.0, across) * sin(k * 3.14159) * 0.9;
  }

  // هالهٔ ماه
  col += vec3(0.38, 0.46, 0.76) * exp(-md * 5.5) * 0.32;
  col += vec3(0.75, 0.80, 0.95) * exp(-(md - moonR) * 30.0) * 0.28 * step(moonR, md);

  // قرص ماه با دریاها، دهانه‌ها و تاریکی لبه
  if (md < moonR) {
    vec2 q = mp / moonR;
    float z = sqrt(max(0.0, 1.0 - dot(q, q)));
    vec2 sph = q / (1.0 + z * 0.6);
    float maria = smoothstep(0.36, 0.72, fbm3(sph * 1.7 + vec2(4.0, 1.3))) * 1.15;
    float fine = fbm(sph * 7.0 + 2.0);
    vec3 base = vec3(1.0, 0.985, 0.94);
    vec3 surf = base * (1.0 - maria * 0.24) * (0.93 + fine * 0.1);
    float limb = pow(z, 0.32);
    surf *= 0.74 + 0.26 * limb;
    float edge = smoothstep(moonR, moonR * 0.985, md);
    col = mix(col, surf, edge);
  }

  // ابرهای نازک مهتابی در نیمهٔ پایین، آرام به راست می‌روند
  vec2 cp = vec2(uv.x * 0.9 + t * 0.012, uv.y * 2.4 - lift * 1.4);
  vec2 warp = vec2(fbm(cp * 1.3 + t * 0.01), fbm(cp * 1.3 + 5.2));
  float cl = fbm(cp * 1.6 + warp * 1.4);
  float cloudBand = smoothstep(0.0, 0.18, uv.y) * smoothstep(0.62, 0.22, uv.y);
  float cloud = smoothstep(0.42, 0.78, cl) * cloudBand;
  float lit = exp(-length(uv - moonC) * 2.2);
  vec3 cloudCol = mix(vec3(0.10, 0.12, 0.22), vec3(0.55, 0.60, 0.78), lit * 0.9 + 0.1);
  col = mix(col, cloudCol, cloud * 0.6);

  // مه افق
  col += vec3(0.12, 0.14, 0.28) * smoothstep(0.35 + lift * 0.2, 0.0, uv.y) * (0.4 + lift * 0.4);

  // تیرگی گوشه‌ها و دیتر برای جلوگیری از نوارنوار شدن گرادیان
  vec2 vq = frag / uRes - 0.5;
  col *= 1.0 - dot(vq, vq) * 0.55;
  col += (h21(frag + fract(t)) - 0.5) / 255.0;
  gl_FragColor = vec4(pow(col, vec3(0.95)), 1.0);
}
`;
