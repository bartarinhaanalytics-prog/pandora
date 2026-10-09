---
name: Dordooneh
description: One long night sky over a sleeping Iranian town; moonlight for words, star gold for the one thing to do.
colors:
  night: "#070a18"
  deep: "#060918"
  panel: "#0e1430"
  panel-2: "#18204a"
  line-strong: "#2b3672"
  halo: "#c9d6ff"
  star: "#f4c96b"
  star-hi: "#ffdc8f"
  star-ink: "#1b1405"
  moon-1: "#eef0fa"
  moon-2: "#c3c9e4"
  moon-3: "#8d95bd"
  danger: "#ffb4a2"
  ok: "#b9e3a9"
  town-ground: "#04060f"
  town-far: "#121a40"
typography:
  display:
    fontFamily: "Lalezar, Vazirmatn Variable, serif"
    fontSize: "clamp(2.6rem, 1.5rem + 4.6vw, 5.4rem)"
    fontWeight: 400
    lineHeight: 1.25
  headline:
    fontFamily: "Lalezar, Vazirmatn Variable, serif"
    fontSize: "clamp(2rem, 1.35rem + 2.6vw, 3.6rem)"
    fontWeight: 400
    lineHeight: 1.25
  title-display:
    fontFamily: "Lalezar, Vazirmatn Variable, serif"
    fontSize: "clamp(1.6rem, 1.3rem + 1vw, 2.1rem)"
    fontWeight: 400
    lineHeight: 1.2
  title:
    fontFamily: "Vazirmatn Variable, Vazirmatn, Tahoma, sans-serif"
    fontSize: "clamp(1.2rem, 1.1rem + 0.4vw, 1.45rem)"
    fontWeight: 750
    lineHeight: 1.5
  lead:
    fontFamily: "Vazirmatn Variable, Vazirmatn, Tahoma, sans-serif"
    fontSize: "clamp(1.06rem, 1rem + 0.35vw, 1.3rem)"
    fontWeight: 400
    lineHeight: 1.9
  body:
    fontFamily: "Vazirmatn Variable, Vazirmatn, Tahoma, sans-serif"
    fontSize: "1rem"
    fontWeight: 400
    lineHeight: 1.9
    fontFeature: "'ss01'"
  story:
    fontFamily: "Vazirmatn Variable, Vazirmatn, Tahoma, sans-serif"
    fontSize: "1.125rem"
    fontWeight: 400
    lineHeight: 2.05
  label:
    fontFamily: "Vazirmatn Variable, Vazirmatn, Tahoma, sans-serif"
    fontSize: "0.9375rem"
    fontWeight: 600
    lineHeight: 1.9
rounded:
  r-1: "8px"
  r-2: "14px"
  r-3: "24px"
  pill: "999px"
spacing:
  s-1: "4px"
  s-2: "8px"
  s-3: "12px"
  s-4: "16px"
  s-5: "24px"
  s-6: "32px"
  s-7: "48px"
  s-8: "72px"
  s-9: "112px"
  gutter: "clamp(16px, 4vw, 48px)"
  maxw: "1240px"
components:
  button-star:
    backgroundColor: "{colors.star}"
    textColor: "{colors.star-ink}"
    rounded: "{rounded.pill}"
    padding: "0 24px"
    height: "48px"
  button-star-hover:
    backgroundColor: "{colors.star-hi}"
    textColor: "{colors.star-ink}"
  button-line:
    backgroundColor: "transparent"
    textColor: "{colors.moon-1}"
    rounded: "{rounded.pill}"
    padding: "0 24px"
    height: "48px"
  chip:
    backgroundColor: "rgb(7 10 24 / 0.35)"
    textColor: "{colors.moon-2}"
    rounded: "{rounded.pill}"
    padding: "0 16px"
    height: "48px"
  chip-selected:
    backgroundColor: "{colors.moon-1}"
    textColor: "{colors.panel}"
  tab:
    backgroundColor: "transparent"
    textColor: "{colors.moon-2}"
    rounded: "{rounded.pill}"
    padding: "0 16px"
    height: "44px"
  tab-selected:
    backgroundColor: "{colors.halo}"
    textColor: "{colors.panel}"
  input:
    backgroundColor: "{colors.deep}"
    textColor: "{colors.moon-1}"
    rounded: "{rounded.r-2}"
    padding: "0 16px"
    height: "56px"
  panel:
    backgroundColor: "rgb(14 20 48 / 0.9)"
    textColor: "{colors.moon-1}"
    rounded: "{rounded.r-3}"
    padding: "32px"
  motion-toggle:
    backgroundColor: "rgb(7 10 24 / 0.55)"
    textColor: "{colors.moon-1}"
    rounded: "{rounded.pill}"
    padding: "0 16px"
    height: "44px"
---

# Design System: Dordooneh

## Overview

**Creative North Star: "One Long Night"**

The whole page is a single night sky. A live procedural sky (WebGL fragment shader, no modelled objects) sits fixed behind everything: a full moon with soft maria and halo upper-left, three depths of twinkling stars, a faint Milky Way, thin moonlit clouds low in the frame, and one slow shooting star every eleven seconds. Scrolling drives the camera downward toward the horizon, and the page ends on the silhouette of a sleeping Iranian town (domes, wind towers, cypresses, gold-lit windows) that bleeds into the footer. Content does not sit on pages or cards; it floats on the sky, either as moonlit text with a soft dark text-shadow or inside a few dark indigo night panels.

The system is calm, low-density and one-handed: Persian RTL throughout, generous vertical rhythm (112px between sections), 44px minimum targets, and a single warm color in a cold world. Moonlight (cool cream-lavender) carries all text; star gold is the only warm hue, and its filled form is spent on the one action that matters in each view. Lalezar, a heavy Persian display face, sets every headline and every title that names a thing (the story, a lullaby, the reviewer roster); Vazirmatn sets everything else.

The user rejected the earlier korsi direction (red quilt, rendered 3D room) as "red, cluttered, amateur 3D". The night replaces it: no literal 3D props, no warm-red room, no pastel mother-and-baby landing.

**Key Characteristics:**
- Fixed, living night sky as the page ground; a still poster stands in under reduced motion, save-data, a user pause, or no WebGL.
- Cool moonlight text on deep indigo; star gold is the single warm accent.
- Dark translucent night panels with a 1px moonlight edge and a soft halo from above.
- Lalezar for named things, Vazirmatn for reading; long Persian line-height (1.9 to 2.05).
- Dashed moonlight hairlines as the system's divider.
- Every surface ends at the town on the horizon.

## Colors

A cold, nearly monochrome indigo night lit by cool moonlight, with one warm star.

### Primary
- **Star Gold** (star): the filled primary button, the skip link, text selection, the caret, and small guiding icons (trust promises, emergency notice). The only warm fill in the system; dark Star Ink sits on it at about 11:1.
- **Bright Star** (star-hi): the hover state of Star Gold, the focus ring, links, and Lalezar titles inside panels and verses (story title, lullaby titles, reviewer roster, "awaiting review" notes). Warm text, never a fill outside hover.
- **Star Ink** (star-ink): text on Star Gold only.

### Secondary
- **Moon Halo** (halo): the pale periwinkle of the moon's glow. Fills the selected health-stage tab, colors the accordion chevron, and (as rgb(201 214 255 / 0.1 to 0.35)) tints panel edges, panel top-light and tab outlines.

### Neutral
- **Moonlight** (moon-1): primary text, headlines, selected chip fill. About 17:1 on Night.
- **Dim Moonlight** (moon-2): leads, body copy beside headlines, nav links, secondary text. About 11:1 on Night.
- **Faint Moonlight** (moon-3): placeholders, origins, footnotes, placeholder reviewer names, outline-button edge. About 6:1 on Night; the floor for text.
- **Night** (night): the page ground behind the sky, header when solid (at 0.94 alpha), mobile menu sheet, scrollbar track.
- **Deep Well** (deep): input wells, darker than the ground so fields read as recessed.
- **Night Panel** (panel): panel surface (used at 0.9 alpha over the sky) and the text color on light selected states.
- **Panel Raised** (panel-2): scrollbar thumb.
- **Indigo Line** (line-strong): scrollbar color in the native scrollbar-color pair.
- **Town Ground** (town-ground): the front row of the town silhouette and the footer background, so the horizon continues into the footer.
- **Far Town** (town-far): the distant row of domes, minarets and wind towers.

### Status
- **Soft Coral** (danger): error text and the 2px invalid ring on inputs; always paired with an icon.
- **Night Sage** (ok): success state after signup.

### Named Rules
**The One Warm Star Rule.** Star Gold is the only warm fill on the page. In any view, the filled gold button is the one primary action; everything else is moonlight, halo or outline.

**The Moonlight Text Rule.** All text is one of three moonlights (moon-1, moon-2, moon-3) or Bright Star. Nothing below Faint Moonlight's contrast is used for text, and there is no gray: every neutral leans indigo-lavender (hue about 272 to 277).

**The Logo Is the Palette Rule.** The mark is a Moonlight crescent (#eef0fa) with one Star Gold four-point star (#f4c96b); it introduces no colour outside the palette.

## Typography

**Display Font:** Lalezar (with Vazirmatn Variable, serif)
**Body Font:** Vazirmatn Variable (with Vazirmatn, Tahoma, sans-serif)

**Character:** Lalezar is a heavy, round, poster-like Persian face that reads like a storybook cover under moonlight; Vazirmatn is a clean, even workhorse for long Persian reading at night. Lalezar is only ever set at weight 400; its heft comes from the face.

### Hierarchy
- **Display** (Lalezar 400, clamp(2.6rem to 5.4rem), 1.25): the single h1 in the first viewport, Moonlight with a deep text-shadow.
- **Headline** (Lalezar 400, clamp(2rem to 3.6rem), 1.25): section h2s, balanced wrapping.
- **Title, display** (Lalezar 400, about 1.6rem to 2.5rem, 1.1 to 1.3): names of things inside panels and verses: "قصهٔ امشب", the generated story title, lullaby titles, the reviewer roster. Usually Bright Star.
- **Title** (Vazirmatn 750, clamp(1.2rem to 1.45rem), 1.5): generic h3.
- **Lead** (Vazirmatn 400, clamp(1.06rem to 1.3rem), 1.9): section intros in Dim Moonlight, 40 to 54ch.
- **Body** (Vazirmatn 400, 1rem, 1.9, stylistic set ss01): default text; answers capped at 64ch.
- **Story** (Vazirmatn 400, 1.125rem, 2.05): the generated tale, max 62ch; lullaby lines at 1.2rem / 2.
- **Label** (Vazirmatn 600 to 700, 0.9375rem): nav, form labels, small notes, help text. Buttons use 750 at 1.0625rem.

### Named Rules
**The Named Things Rule.** Lalezar sets headlines and the titles of things a parent would name aloud (a story, a lullaby, the reviewers). Labels, buttons, body and UI never use Lalezar.

**The Persian Numerals Rule.** Numbers display in Persian digits; tabular figures (.num) where they align, and phone numbers isolated LTR inside RTL text.

## Layout

Single-column scroll of full-bleed sections over the fixed sky, content inside a 1240px max wrap with a fluid gutter (16px to 48px). Direction is RTL; the moon sits upper-left, so headlines and the story maker align to the right (start) edge, opposite the moon.

- **Section rhythm:** 112px top and bottom per section; 72px top on narrow screens. Spacing steps on a 4/8 base (4, 8, 12, 16, 24, 32, 48, 72, 112).
- **First viewport:** min-height 100svh; headline, lead and the story-maker panel stacked at a max 560px measure on the start side.
- **Two-column sections:** Health uses 5fr intro (sticky under the header) / 7fr panel; Trust uses 7fr copy / 5fr roster. Both collapse to one column at 900px.
- **Lullabies:** three verse columns (1.2fr 1.2fr 1fr) divided by dashed hairlines, stacking at 900px with the dashes turning horizontal.
- **Signup:** min-height 92svh, panel max 600px, bottom padding reserving clamp(170px to 320px) for the town silhouette; on mobile the panel drops toward the town.
- **Breakpoints:** 1080px (desktop nav to menu sheet), 900px (two columns to one), 760px (hero top-aligned, footer single column), 720px (motion toggle icon-only, header signup link hidden), 560px (tighter panel padding).
- **Header:** fixed 64px band, transparent over the sky, turning Night at 0.94 alpha with a soft shadow after 40px of scroll.

## Elevation & Depth

Depth comes from the sky itself: three parallax star layers, the moon's halo, and translucent panels floating over it. Shadows are always soft, offset downward and very dark (near-black indigo), never hard or colored except for the star button's warm glow. Light comes from above: panels carry a radial moonlight wash at their top edge and a 1px inner top highlight.

### Shadow Vocabulary
- **Panel float** (`box-shadow: 0 30px 70px -24px rgb(1 2 10 / 0.85), 0 8px 20px -8px rgb(1 2 10 / 0.6)` plus `inset 0 1px 0 rgb(238 240 250 / 0.1)`): every night panel.
- **Star glow** (`box-shadow: 0 10px 26px -10px rgb(244 201 107 / 0.55)` plus `inset 0 1px 0 rgb(255 248 225 / 0.7)`): the star button only.
- **Well** (`box-shadow: inset 0 2px 6px rgb(0 0 0 / 0.45), inset 0 0 0 1px rgb(238 240 250 / 0.3)`): text inputs, recessed into the panel.
- **Header band** (`box-shadow: 0 10px 30px -18px rgb(0 0 0 / 0.9)`): the solid header after scroll.
- **Text on sky** (`text-shadow: 0 2px 14px rgb(2 3 12 / 0.9)`; h1 `0 4px 30px rgb(0 0 0 / 0.6)`): any text set directly on the sky rather than in a panel.

### Named Rules
**The Moon Is Above Rule.** Light falls from the top: panel washes and inner highlights sit on the top edge, shadows fall below. Never light a surface from the side or below.

**The Text On Sky Rule.** Text placed directly on the sky carries the soft dark text-shadow; text that cannot carry it goes in a panel.

## Shapes

Soft and round, with no sharp corners anywhere. Panels use a generous 24px radius; inputs a gentler 14px; every button, chip, tab, nav pill and toggle is a full pill (999px). Outlines are drawn as inset 1px box-shadows in moonlight at 0.28 to 0.35 alpha rather than borders, so they never shift layout. Dividers are dashed 1px moonlight hairlines (rgb(238 240 250 / 0.18 to 0.4)): between story and form, between verses, between roster rows, above the footer note. Solid hairlines appear only as accordion separators and menu rows. The one recurring silhouette is the town skyline: domes, wind towers and cypresses in two flat indigo rows with tiny gold windows.

## Components

### Buttons
Round, warm and unmistakable; there is only ever one gold one in view.
- **Shape:** full pill (999px), min-height 48px (52 to 56px in forms), 24px inline padding, Vazirmatn 750 at 1.0625rem, icon gap 8px.
- **Star (primary):** Star Gold fill, Star Ink text, Star glow shadow with a top inner highlight.
- **Hover / Focus / Active:** hover brightens to Bright Star; active nudges 1px down; focus shows the system ring (3px Bright Star, 3px offset). Disabled drops to 0.7 opacity with a progress cursor and a spinning loader icon.
- **Line (secondary):** transparent with an inset 1px Faint Moonlight edge and Moonlight text; hover adds an 8% moonlight wash and brightens the edge to Moonlight.
- **Quiet text link:** the header signup is a plain underlined link in Dim Moonlight, not a button.

### Chips
- **Style:** pill, 48px tall, faint night fill (rgb(7 10 24 / 0.35)), inset 1px moonlight edge at 0.35, Dim Moonlight text with a 20px line icon. A visually hidden radio covers the chip.
- **State:** hover brightens text and edge; selected fills solid Moonlight with Night Panel text and a small drop shadow; keyboard focus rings the whole chip.

### Tabs
- **Style:** pill, 44px, transparent with an inset Moon Halo edge at 0.35, Dim Moonlight text at 650.
- **Selected:** solid Moon Halo fill, Night Panel text, no edge. Halo for tabs, Moonlight for chips: selection is always a light fill on dark, never gold.

### Cards / Containers (Night Panel)
- **Corner Style:** 24px.
- **Background:** Night Panel at 0.9 alpha with a radial moon-halo wash (120% by 60% ellipse from just above the top edge, halo at 0.1).
- **Shadow Strategy:** Panel float (see Elevation).
- **Border:** 1px solid halo at 0.16.
- **Internal Padding:** 32px; 24px by 16px under 560px.
- Used for the story maker, the health panel, the reviewer roster and signup. There is no row of feature cards; panels are few and each holds a working tool or a real list.

### Inputs / Fields
- **Style:** Deep Well fill, 14px radius, 52 to 56px tall, inset well shadow with a 1px moonlight edge, Moonlight text (1.15 to 1.25rem, 650 in the story maker); placeholder in Faint Moonlight.
- **Focus:** the system ring (3px Bright Star; 2px offset on the story-maker field). The caret is Star Gold.
- **Error:** the edge becomes a 2px Soft Coral inset ring; the message below is Soft Coral with an alert icon, announced as an alert.

### Navigation
- **Header:** fixed 64px band; logo mark plus Lalezar wordmark at 1.75rem on the start side; nav links as pills (Dim Moonlight, 600, 0.9375rem, 44px, hover Moonlight with an 8% wash); motion toggle and quiet signup link on the end side.
- **Mobile (under 1080px):** a 44px circular menu button opens a full-screen Night sheet with Lalezar 2rem links separated by hairlines and a line button for signup.
- **Footer:** Town Ground background continuing the skyline, Dim Moonlight links at 44px, dashed hairline above the medical note.

### Motion Toggle
A pill (44px) on translucent Night with a 0.28 moonlight inset edge, play/pause line icon and label; collapses to a 44px circle under 720px. It pauses the live sky everywhere and persists the choice. Default is paused under reduced motion or save-data.

### Accordion (Health answers)
Native disclosure rows separated by 1px halo hairlines (0.18); question in Moonlight 700 at 1.0625rem, min 56px; a Moon Halo chevron rotates 180 degrees on open. Each answer ends with a Bright Star review note and icon.

### Story Maker (signature)
The first-viewport night panel: Bright Star Lalezar title, child's-name well, three hero chips, one star button. The finished story unfolds in place below a dashed moonlight divider: the Lalezar title and each paragraph reveal top-down (clip, blur 6px to 0, opacity, 900ms ease-out cubic-bezier(0.16, 1, 0.3, 1), staggered 260ms), then a star button to keep the story and a line button for another.

### Lullaby Verses
Figures without a container: Bright Star Lalezar title, Faint Moonlight origin line, lyric lines in Moonlight 1.2rem at line-height 2, columns divided by dashed hairlines. Pending verses drop to small Dim Moonlight text.

### Night Sky and Town (signature)
The fixed full-screen WebGL sky (low-power context, DPR capped at 1 on touch and 1.5 elsewhere) rendered over a webp poster of its own still frame and a Night-to-indigo gradient. Scroll eases the camera toward the horizon. The town is a flat SVG silhouette pinned to the bottom of the signup section in Far Town and Town Ground with Star Gold windows.

## Do's and Don'ts

### Do:
- **Do** keep exactly one filled Star Gold button per view; secondary actions are line buttons or quiet links.
- **Do** set every text color from moon-1, moon-2, moon-3 or star-hi; keep text at or above Faint Moonlight's contrast.
- **Do** put new content either on the sky with the soft dark text-shadow or in a Night Panel (24px radius, 1px halo edge, top-light wash, Panel float shadow).
- **Do** use Lalezar 400 for headlines and named titles, Vazirmatn for everything else, and Persian line-heights of 1.9 or more for reading text.
- **Do** make every button, chip, tab and toggle a pill with a 44px minimum target, outlined with inset box-shadows rather than borders.
- **Do** divide with dashed 1px moonlight hairlines.
- **Do** ship any moving background with a visible pause control and a still poster for reduced motion, save-data and no-WebGL.
- **Do** end long pages at the horizon: the town silhouette in Far Town and Town Ground running into the footer.

### Don't:
- **Don't** use Star Gold as a large fill, a section background, or on more than one filled control in view.
- **Don't** bring back the korsi world: no red quilt, no warm-red room, no rendered 3D props or modelled objects.
- **Don't** use pastel mother-and-baby palettes, split heroes with stock photography, or rows of feature cards.
- **Don't** bring back red or warm cloth tones; the user rejected the red korsi world.
- **Don't** use hard-edged or colored shadows; shadows are soft, dark and fall from above (the star glow is the one warm exception).
- **Don't** use pure gray or pure black text colors; neutrals stay indigo-tinted.
- **Don't** set labels, buttons or body copy in Lalezar.
