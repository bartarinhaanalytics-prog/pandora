"""Generates src/sky/town.svg: a sleeping Iranian town on the horizon (domes, wind towers, cypresses, lit windows).
Run: python3 scripts/sky/town.py"""
import random, pathlib
random.seed(7)
W, H = 1440, 280
back, front = [], []

def dome(cx, base, r, neck=0):
    # onion dome: a curve rising to a point
    return (f"M{cx-r},{base} C{cx-r},{base-r*1.05} {cx-r*0.35},{base-r*1.25} {cx},{base-r*1.7-neck} "
            f"C{cx+r*0.35},{base-r*1.25} {cx+r},{base-r*1.05} {cx+r},{base} Z")

def house(x, w, h, base, layer, lit=0.35):
    layer.append(f'<rect x="{x}" y="{base-h}" width="{w}" height="{h+2}"/>')
    wins = []
    if layer is front:
        for wx in range(int(x + 8), int(x + w - 10), 16):
            for wy in range(int(base - h + 12), int(base - 14), 20):
                if random.random() < lit:
                    wins.append((wx, wy))
    return wins

def badgir(x, w, h, base, layer):
    layer.append(f'<rect x="{x}" y="{base-h}" width="{w}" height="{h+2}"/>')
    layer.append(f'<rect x="{x-3}" y="{base-h-6}" width="{w+6}" height="7"/>')

def cypress(x, h, base, layer):
    layer.append(f'<path d="M{x},{base} C{x-9},{base-h*0.4} {x-6},{base-h*0.8} {x},{base-h} C{x+6},{base-h*0.8} {x+9},{base-h*0.4} {x},{base} Z"/>')

# back layer: distant hills and a big mosque dome
back.append(f'<path d="M0,{H} L0,190 C180,160 320,175 460,168 C620,160 760,185 900,176 C1060,166 1200,150 1440,172 L1440,{H} Z"/>')
back.append(f'<path d="{dome(1010, 168, 46)}"/><rect x="964" y="168" width="92" height="30"/>')
back.append(f'<rect x="1080" y="96" width="9" height="80"/><path d="{dome(1084.5, 98, 7)}"/>')
back.append(f'<rect x="932" y="110" width="8" height="66"/><path d="{dome(936, 112, 6)}"/>')
for x in range(0, 1440, 70):
    hgt = random.randint(18, 40)
    back.append(f'<rect x="{x}" y="{190-hgt}" width="{random.randint(40,64)}" height="{hgt+20}"/>')

# front layer: houses, small domes, wind towers, cypresses
base = H
wins = []
x = -10
while x < W:
    kind = random.random()
    w = random.randint(60, 120)
    h = random.randint(40, 78)
    wins += house(x, w, h, base, front)
    if kind < 0.22:
        front.append(f'<path d="{dome(x + w/2, base-h, min(26, w/2.6))}"/>')
    elif kind < 0.42:
        badgir(x + random.randint(8, w-30), 18, h + random.randint(28, 44), base, front)
    elif kind < 0.55:
        cypress(x + w + 6, random.randint(70, 110), base - 6, front)
    x += w - random.randint(0, 10)

win_svg = "".join(f'<rect x="{wx}" y="{wy}" width="6" height="9" rx="2"/>' for wx, wy in wins)
svg = f'''<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {W} {H}" preserveAspectRatio="xMidYMax slice">
<g fill="#121a40">{''.join(back)}</g>
<g fill="#04060f">{''.join(front)}</g>
<g fill="#f4c96b" opacity=".85">{win_svg}</g>
</svg>
'''
out = pathlib.Path(__file__).resolve().parents[2] / 'src/sky/town.svg'
out.write_text(svg)
print(out, len(svg), 'windows', len(wins))
