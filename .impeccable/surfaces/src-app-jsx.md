---
version: 1
slug: "src-app-jsx"
primary_target: "src/App.jsx"
related_targets: []
---

# Homepage surface brief

Scope: homepage (`src/App.jsx` and its sections). Visitor mode: Persuade.

Audience and job: an Iranian parent at night on a phone, wanting tonight's story for a child, or an adult with a private health question. Action: make tonight's story right away, free, without signing up; sign up afterwards to save it. Proof on hand: the working story maker itself; health answers marked as awaiting doctor review; every testimonial or doctor name stays a labelled placeholder.

Constraint from the user: a moving, full-screen, cinematic background. The korsi direction (red quilt, rendered 3D room) was rejected by the user as "red, cluttered, amateur 3D"; they asked for the night, the moon and the stars instead. The background is a live procedural WebGL sky (no modelled objects), with a visible pause control and a still poster under reduced motion, save-data or no WebGL.

## Direction contract

THESIS: The homepage is one long night sky: the visitor starts under the moon, makes tonight's story, and as they scroll the camera descends past the stars to a sleeping town on the horizon where the signup waits. It refuses the category's pastel mother-and-baby landing with a split hero and a row of feature cards, and it refuses literal 3D props.

OWN-WORLD: Deep indigo night as the page ground; moonlight (cool cream) for text; star gold reserved for the one primary action. A full moon with soft maria and halo, three depths of twinkling stars, a faint Milky Way, thin moonlit clouds, a rare shooting star. Panels are dark indigo surfaces with a 1px moonlight edge and a soft halo from above. The page ends on a silhouette of a sleeping Iranian town (domes, wind towers, cypresses, lit windows). Lalezar sets every headline, Vazirmatn the rest.

STORY: The visitor sees the moon and reads that tonight's story can be made here, makes one with their child's name, reads it, learns that health answers are drafts awaiting a doctor and readable without signup, reads lullaby lyrics, sees honest reviewer placeholders, then reaches the town and signs up with a mobile number to keep the story.

FIRST VIEWPORT: Live night sky with the moon top-left. Lalezar headline on the right in RTL. The story maker sits on a night panel in the first viewport: child's name field, three hero chips, one star-gold "قصه را بساز" button; the finished story unfolds in place. Header is a thin dark band with nav, motion toggle and a quiet signup text link.

FORM: Night, moon and stars (user-directed replacement of korsi, position 4 of 7, seed key 2e2d4173). Signature interaction: the story is made inline in the first viewport; scroll drives a camera descent through the sky.

FINISH: unreviewed and undocumented is unfinished; this build ends with the finish review, the verdict, DESIGN.md, and every shipping raster carrying its provenance
