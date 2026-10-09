# Product

<!-- impeccable:product-schema 1 -->

## Platform

web

## Stack

React 18 + Vite, three.js for 3D (chosen earlier in this project; the user said any of it may change if needed).

## Users

Iranian mothers and fathers aged roughly 20 to 40, and couples about to marry. They mostly arrive on a phone, often at night: either with a health question they feel awkward asking anyone (sexual health, pregnancy, newborn care), or at a child's bedtime looking for a story or lullaby. Persian is their language; the interface is right-to-left.

## Product Purpose

A free companion for Iranian families from preparing for marriage to early childhood (about age six). It combines doctor-reviewed health guidance organised by life stage with a personal bedtime-story maker and a library of traditional Iranian lullabies. Success on the homepage: a visitor makes their first story right away, without signing up, then signs up to save it.

## Positioning

Competitors in Iran (Impo for women's health and cycles, Gahvare for pregnancy and parenting up to age six, Ninisite as a parent forum) charge for parts of their service and none offers a personal story maker. Dordooneh is entirely free, spans the whole journey from pre-marriage to childhood in one place, and its nightly story maker, where the child's own name and chosen hero shape the tale, is the habit-forming core no competitor has.

## Operating Context

- Bedtime: a parent beside a child's bed in a dark room, one-handed on a phone, wanting a story within a minute.
- Private questions: an adult searching anonymously about sexual or reproductive health, who needs to feel safe and unjudged.
- Stage-based reading: pregnancy week by week, newborn months, child years.

## Capabilities and Constraints

- Health articles reviewed by a named specialist (gynecologist, pediatrician, urologist, family counsellor), shown with reviewer name and review date.
- Story maker: choose child's name, hero, theme and length; AI writes the story. Daily limit per user to control cost (for example three stories a night).
- Lullaby library: traditional and regional Iranian lullabies with full lyrics; audio files not yet available.
- Signup: mobile number with SMS code only. No backend exists yet; the signup endpoint is configured via `VITE_SIGNUP_URL`.
- Online doctor consultation was explicitly removed from the first release; anonymous Q&A with specialists is optional and depends on having specialists available.
- Medical content never replaces a doctor; the emergency number is 115.

## Brand Commitments

- Name: «دردونه» (dordooneh, "pearl, dear one"). The user said nothing is fixed and anything may change if it improves the product.
- Message that every feature is free should stay clear.
- Tone: warm, calm, trustworthy, never judgmental; plain Persian, not clinical jargon.

## Evidence on Hand

- No real testimonials, doctor names, user counts, press or partners exist yet. Every such slot must stay a clearly marked placeholder; nothing may be invented.
- Real content available: sample health Q&A written in this project (must be marked as awaiting doctor review), story-maker flow, traditional lullaby lyrics in the public domain.

## Product Principles

1. The story comes first: a visitor should be able to make tonight's story before being asked for anything.
2. Safe to ask: sensitive health topics are reachable without signing up and without exposure.
3. Trust is shown, not claimed: reviewer names and dates, never invented proof.
4. Free means free: no paywalls, no dark patterns, no fake urgency.
5. One-handed at night: everything important works on a phone in a dark room.

## Accessibility & Inclusion

WCAG 2.1 AA, right-to-left Persian with Persian numerals, `prefers-reduced-motion` support, 44px touch targets, full keyboard operation, 3D never the only carrier of information.
