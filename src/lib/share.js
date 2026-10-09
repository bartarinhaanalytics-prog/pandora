/*
 * قصه در خود لینک جا می‌گیرد (base64url از JSON)؛ پس صفحهٔ عمومی بدون سرور باز می‌شود.
 */
export function encodeStory(story) {
  const json = JSON.stringify({ t: story.title, p: story.parts });
  const bytes = new TextEncoder().encode(json);
  let bin = '';
  bytes.forEach((b) => {
    bin += String.fromCharCode(b);
  });
  return btoa(bin).replace(/\+/g, '-').replace(/\//g, '_').replace(/=+$/, '');
}

export function decodeStory(s) {
  try {
    const b64 = s.replace(/-/g, '+').replace(/_/g, '/');
    const bin = atob(b64 + '==='.slice((b64.length + 3) % 4));
    const bytes = Uint8Array.from(bin, (c) => c.charCodeAt(0));
    const o = JSON.parse(new TextDecoder().decode(bytes));
    if (typeof o.t !== 'string' || !Array.isArray(o.p)) return null;
    return { title: o.t.slice(0, 120), parts: o.p.slice(0, 20).map((x) => String(x).slice(0, 800)) };
  } catch {
    return null;
  }
}

export function storyLink(story) {
  return `${window.location.origin}${window.location.pathname}#/s/${encodeStory(story)}`;
}

/* اشتراک با منوی سیستم یا کپی در حافظه؛ true یعنی کپی شد */
export async function shareText({ title, text, url }) {
  if (navigator.share) {
    await navigator.share({ title, text, url });
    return false;
  }
  await navigator.clipboard.writeText(url ? `${text}\n${url}` : text);
  return true;
}
