# Magic UI Registry Index

Generated from `registry.json` in `magicuidesign/magicui` at commit `52bc693`.
Regenerate whenever upstream adds components; do not hand-edit entries.

Every entry installs with:

```bash
npx shadcn@latest add @magicui/<slug>
```

and lands at `components/magicui/<slug>.tsx`, so the import is
`@/components/magicui/<slug>` when the project uses the `@/` alias.

Legend: **deps** = npm packages the installer pulls in; **css** = the component
also writes global CSS/keyframes or theme variables; **needs** = other registry
components it depends on.

75 components.

| Slug | Title | Description | deps | css | needs |
| --- | --- | --- | --- | --- | --- |
| `android` | Android | A mockup of an Android device. | — | — | — |
| `animated-beam` | Animated Beam | An animated beam of light which travels along a path. Useful for showcasing the integration features of a website. | `motion` | — | — |
| `animated-circular-progress-bar` | Animated Circular Progress Bar | Animated Circular Progress Bar is a component that displays a circular gauge with a percentage value. | — | — | — |
| `animated-gradient-text` | Animated Gradient Text | An animated gradient background which transitions between colors for text. | — | yes | — |
| `animated-grid-pattern` | Animated Grid Pattern | A animated background grid pattern made with SVGs, fully customizable using Tailwind CSS. | `motion` | — | — |
| `animated-list` | Animated List | A list that animates each item in sequence with a delay. Used to showcase notifications or events on your landing page. | `motion` | — | — |
| `animated-shiny-text` | Animated Shiny Text | A light glare effect which pans across text making it appear as if it is shimmering. | — | yes | — |
| `animated-subscribe-button` | Animated Subscribe Button | An animated subscribe button useful for showing a micro animation from intial to final result. | `motion` | — | — |
| `animated-theme-toggler` | Theme Toggler | A component for theme changing animation. | `lucide-react` | yes | — |
| `arc-timeline` | Arc Timeline | A curved timeline that elegantly visualizes key milestones, perfect for Web3 and AI roadmaps. | — | — | — |
| `aurora-text` | Aurora Text | A beautiful aurora text effect | — | yes | — |
| `avatar-circles` | Avatar Circles | Overlapping circles of avatars. | — | — | — |
| `bento-grid` | Bento Grid | Bento grid is a layout used to showcase the features of a product in a simple and elegant way. | `@radix-ui/react-icons` | — | `button` |
| `blur-fade` | Blur Fade | Blur fade in and out animation. Used to smoothly fade in and out content. | `motion` | — | — |
| `border-beam` | Border Beam | An animated beam of light which travels along the border of its container. | `motion` | — | — |
| `box-reveal` | Box Reveal Animation | Sliding box animation that reveals text behind it. | `motion` | — | — |
| `client-tweet-card` | Client Tweet Card | A client-side version of the tweet card that displays a tweet with the author's name, handle, and profile picture. | `react-tweet` | — | — |
| `code-comparison` | Code Comparison | A component which compares two code snippets. | `shiki`, `next-themes` | — | — |
| `comic-text` | Comic Text | Comic text animation | `motion` | — | — |
| `confetti` | Confetti | Confetti animations are best used to delight your users when something special happens | `canvas-confetti`, `@types/canvas-confetti` | — | `button` |
| `cool-mode` | Cool Mode | Cool mode effect for buttons, links, and other DOMs | — | — | — |
| `dock` | Dock | An implementation of the MacOS dock using react + tailwindcss + motion | `motion` | — | — |
| `dot-pattern` | Dot Pattern | A background dot pattern made with SVGs, fully customizable using Tailwind CSS. | — | — | — |
| `file-tree` | File Tree | A component used to showcase the folder and file structure of a directory. | — | — | — |
| `flickering-grid` | Flickering Grid | A flickering grid background made with SVGs, fully customizable using Tailwind CSS. | — | — | — |
| `flip-text` | Flip Text | Text flipping character animation | `motion` | — | — |
| `globe` | Globe | An autorotating, interactive, and highly performant globe made using WebGL. | `cobe`, `motion` | — | — |
| `glyph-matrix` | Glyph Matrix | An animated grid of subtly shifting glyphs with fade effect and theme support. | — | — | — |
| `grid-beams` | Grid Beams | A dynamic grid background with animated light beams rays and grid patterns. | `motion` | — | — |
| `grid-pattern` | Grid Pattern | A background grid pattern made with SVGs, fully customizable using Tailwind CSS. | — | — | — |
| `hero-video-dialog` | Hero Video Dialog | A hero video dialog component. | `motion` | — | — |
| `highlighter` | Highlighter | A text highlighter that mimics the effect of a human-drawn marker stroke. | `rough-notation` | — | — |
| `hyper-text` | Hyper Text | A text animation that scrambles letters before revealing the final text. | `motion` | — | — |
| `icon-cloud` | Icon Cloud | An interactive 3D tag cloud component | — | — | — |
| `interactive-grid-pattern` | Interactive Grid Pattern | A interactive background grid pattern made with SVGs, fully customizable using Tailwind CSS. | — | — | — |
| `interactive-hover-button` |  |  | — | — | — |
| `iphone-15-pro` | iPhone 15 Pro | A mockup of the iPhone 15 Pro | — | — | — |
| `lens` | Lens | A interactive component that enables zooming into images, videos and other elements. | `motion` | — | — |
| `line-shadow-text` | Line Shadow Text | A text component with a moving line shadow. | `motion` | yes | — |
| `magic-card` | Magic Card | A spotlight effect that follows your mouse cursor and highlights borders on hover. | `motion` | — | — |
| `marquee` | Marquee | An infinite scrolling component that can be used to display text, images, or videos. | — | yes | — |
| `meteors` | Meteors | A meteor shower effect. | — | yes | — |
| `morphing-text` | Morphing Text | A dynamic text morphing component for Magic UI. | — | — | — |
| `neon-gradient-card` | Neon Gradient Card | A beautiful neon card effect | — | yes | — |
| `number-ticker` | Number Ticker | Animate numbers to count up or down to a target number | `motion` | — | — |
| `orbiting-circles` | Orbiting Circles | A collection of circles which move in orbit along a circular path | — | yes | — |
| `particles` | Particles | Particles are a fun way to add some visual flair to your website. They can be used to create a sense of depth, movement, and interactivity. | — | — | — |
| `pixel-image` | Pixel Image | A component that displays an image with a pixelated effect, creating a retro aesthetic. | — | — | — |
| `pointer` | Pointer | A component that displays a pointer when hovering over an element | `motion` | — | — |
| `progressive-blur` | Progressive Blur | The Progressive Blur component adds a smooth blur gradient effect to scrollable content, indicating more content below or above. | — | — | — |
| `pulsating-button` | Pulsating Button | An animated pulsating button useful for capturing attention of users. | — | yes | — |
| `rainbow-button` | Rainbow Button | An animated button with a rainbow effect. | — | yes | — |
| `retro-grid` | Retro Grid | An animated scrolling retro grid effect | — | yes | — |
| `ripple` | Ripple | An animated ripple effect typically used behind elements to emphasize them. | — | yes | — |
| `ripple-button` | Ripple Button | An animated button with ripple useful for user engagement. | — | yes | — |
| `safari` | Safari | A safari browser mockup to showcase your website. | — | — | — |
| `scratch-to-reveal` | Scratch To Reveal | The ScratchToReveal component creates an interactive scratch-off effect with customizable dimensions and animations, revealing hidden content beneath. | `motion` | — | — |
| `script-copy-btn` | Script Copy Button | Copy code to clipboard | `motion`, `shiki`, `next-themes` | — | `button` |
| `scroll-based-velocity` | Scroll Based Velocity | Scrolling text whose speed changes based on scroll speed | `motion` | — | — |
| `scroll-progress` | Scroll Progress | Animated Scroll Progress for your pages | `motion` | — | — |
| `shimmer-button` | Shimmer Button | A button with a shimmering light which travels around the perimeter. | — | yes | — |
| `shine-border` | Shine Border | Shine border is an animated background border effect. | — | yes | — |
| `shiny-button` | Shiny Button | A shiny button component with dynamic styles in the dark mode or light mode. | `motion` | — | — |
| `smooth-cursor` |  | A customizable, physics-based smooth cursor animation component with spring animations and rotation effects | `framer-motion` | — | — |
| `sparkles-text` | Sparkles Text | A dynamic text that generates continuous sparkles with smooth transitions, perfect for highlighting text with animated stars. | `motion` | — | — |
| `spinning-text` | Spinning Text | The Spinning Text component animates text in a circular motion with customizable speed, direction, color, and transitions for dynamic and engaging effects. | `motion` | — | — |
| `striped-pattern` | Striped Pattern | A background striped pattern made with SVGs, fully customizable using Tailwind CSS. | — | — | — |
| `terminal` | Terminal | A terminal component | — | — | — |
| `text-animate` | Text Animate | A text animation component that animates text using a variety of different animations. | `motion` | — | — |
| `text-reveal` | Text Reveal | Fade in text as you scroll down the page. | `motion` | — | — |
| `tweet-card` | Tweet Card | A card that displays a tweet with the author's name, handle, and profile picture. | `react-tweet` | — | — |
| `typing-animation` | Typing Animation | Characters appearing in typed animation | `motion` | — | — |
| `video-text` | Video Text | A component that displays text with a video playing in the background. | — | — | — |
| `warp-background` | Warp Background | A card with a time warping background effect. | `motion` | — | — |
| `word-rotate` | Word Rotate | A vertical rotation of words | `motion` | — | — |

## Notes

- `motion` is by far the most common dependency (31 components). Install it once;
  the shadcn CLI handles it per component.
- 16 components ship global CSS. If a component renders but looks inert, the
  keyframes block is the first thing to check.
- `bento-grid`, `confetti`, and `script-copy-btn` pull in the shadcn `button`,
  so run `npx shadcn@latest init` before adding them.
