# Source and provenance

This skill is vendored from the official Magic UI repository.

- Upstream: https://github.com/magicuidesign/magicui
- Upstream path: `skills/magic-ui/`
- Commit: `52bc69354621e5cd7c9bc84a0e42b42f2d0c07b1`
- License: MIT (Copyright (c) Magic UI) — see https://github.com/magicuidesign/magicui/blob/main/LICENSE.md

## Local changes

`SKILL.md` and `references/components.md` are upstream files with three edits:

1. **Import path corrected.** Upstream states components land at
   `@/components/ui/<slug>`. The registry manifest (`registry.json`) targets
   `components/magicui/<slug>.tsx` for every one of the 75 UI components, so both
   files now say `@/components/magicui/<slug>`.
2. **Pointer to `references/registry-index.md`** added in the on-demand
   references section and in the components shortlist.
3. **Troubleshooting entry** added for components whose animation depends on a
   global CSS keyframes block.

`references/recipes.md` is unmodified.

`references/registry-index.md` is not from upstream — it is generated from
upstream `registry.json` at the commit above.

## Updating

Re-clone upstream, copy `skills/magic-ui/` over this directory, re-apply the
three edits above, and regenerate `references/registry-index.md` from
`registry.json` (one table row per `registry:ui` item: slug, title, description,
`dependencies`, whether `css`/`cssVars` is present, `registryDependencies`).
