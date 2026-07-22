---
name: Aero-Enterprise Minimalist
colors:
  surface: '#f7f9fd'
  surface-dim: '#d8dade'
  surface-bright: '#f7f9fd'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f2f4f8'
  surface-container: '#eceef2'
  surface-container-high: '#e6e8ec'
  surface-container-highest: '#e0e3e6'
  on-surface: '#181c1f'
  on-surface-variant: '#434655'
  inverse-surface: '#2d3134'
  inverse-on-surface: '#eff1f5'
  outline: '#737686'
  outline-variant: '#c3c6d7'
  surface-tint: '#0053db'
  primary: '#004ac6'
  on-primary: '#ffffff'
  primary-container: '#2563eb'
  on-primary-container: '#eeefff'
  inverse-primary: '#b4c5ff'
  secondary: '#505f76'
  on-secondary: '#ffffff'
  secondary-container: '#d0e1fb'
  on-secondary-container: '#54647a'
  tertiary: '#005a82'
  on-tertiary: '#ffffff'
  tertiary-container: '#0074a6'
  on-tertiary-container: '#e4f2ff'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#dbe1ff'
  primary-fixed-dim: '#b4c5ff'
  on-primary-fixed: '#00174b'
  on-primary-fixed-variant: '#003ea8'
  secondary-fixed: '#d3e4fe'
  secondary-fixed-dim: '#b7c8e1'
  on-secondary-fixed: '#0b1c30'
  on-secondary-fixed-variant: '#38485d'
  tertiary-fixed: '#c9e6ff'
  tertiary-fixed-dim: '#89ceff'
  on-tertiary-fixed: '#001e2f'
  on-tertiary-fixed-variant: '#004c6e'
  background: '#f7f9fd'
  on-background: '#181c1f'
  surface-variant: '#e0e3e6'
typography:
  display-lg:
    fontFamily: Inter
    fontSize: 48px
    fontWeight: '700'
    lineHeight: 56px
    letterSpacing: -0.02em
  headline-lg:
    fontFamily: Inter
    fontSize: 32px
    fontWeight: '600'
    lineHeight: 40px
    letterSpacing: -0.02em
  headline-md:
    fontFamily: Inter
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 32px
    letterSpacing: -0.01em
  headline-sm:
    fontFamily: Inter
    fontSize: 20px
    fontWeight: '600'
    lineHeight: 28px
  body-lg:
    fontFamily: Inter
    fontSize: 18px
    fontWeight: '400'
    lineHeight: 28px
  body-md:
    fontFamily: Inter
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 24px
  body-sm:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '400'
    lineHeight: 20px
  label-md:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '500'
    lineHeight: 20px
  label-sm:
    fontFamily: Inter
    fontSize: 12px
    fontWeight: '600'
    lineHeight: 16px
    letterSpacing: 0.05em
  headline-lg-mobile:
    fontFamily: Inter
    fontSize: 28px
    fontWeight: '600'
    lineHeight: 36px
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  unit: 8px
  xs: 4px
  sm: 8px
  md: 16px
  lg: 24px
  xl: 32px
  2xl: 48px
  3xl: 64px
  container-max: 1440px
  gutter: 24px
  margin-mobile: 16px
  margin-desktop: 32px
---

## Brand & Style
The design system is engineered for high-stakes aviation document management, prioritizing clarity, speed, and precision. It draws heavily from the **Modern Minimalist** movement, specifically influenced by the "utility-first" aesthetics of high-end developer tools. 

The emotional response should be one of "command and control"—providing users with a sense of calm reliability through vast whitespace, crisp typography, and a reduced cognitive load. The UI avoids unnecessary decorative elements, ensuring that mission-critical information is never obscured. Every interaction is purposeful, utilizing subtle motion and refined transitions to guide the user through complex workflows.

## Colors
The palette is anchored by **AirNav Blue (#2563EB)**, used strategically for primary actions and active states to maintain focus. The foundation of the interface is built on a "cool neutral" scale.

- **Background:** Uses a soft off-white (#F5F7FB) to reduce eye strain compared to pure white.
- **Surface:** Modal windows, sidebars, and primary content containers use pure white (#FFFFFF) to create a distinct layered effect.
- **Accents:** Tertiary blue is reserved for secondary informational badges or progress indicators.
- **Status:** Standard semantic colors apply: Success (#10B981), Warning (#F59E0B), and Error (#EF4444), but are used with low-saturation backgrounds and high-saturation text for a professional look.

## Typography
This design system utilizes **Inter** exclusively to achieve a systematic, utilitarian appearance. The hierarchy is established through weight and spacing rather than multiple typefaces. 

For large displays, negative letter spacing is applied to headings to maintain a tight, professional look. Labels for metadata and document IDs should use the `label-sm` style with increased letter spacing to differentiate them from body copy. All line heights are optimized for a 4px baseline grid to ensure vertical rhythm.

## Layout & Spacing
The system follows a strict **8px grid** rhythm. Layouts are primarily fluid within a maximum container width of 1440px to ensure legibility on wide aviation monitors.

- **Desktop:** A 12-column grid with 24px gutters. Sidebars are fixed at 280px.
- **Tablet:** An 8-column grid with 20px gutters. Sidebars collapse into a drawer.
- **Mobile:** A 4-column grid with 16px gutters. Padding is reduced to `md` (16px) for internal card containers.
- **Alignment:** All elements should align to the 8px baseline. Use `2xl` (48px) spacing between major sections to emphasize the minimalist "breathable" aesthetic.

## Elevation & Depth
Depth is communicated through **Tonal Layering** and **Glassmorphism**. 

1.  **Level 0 (Base):** Neutral background (#F5F7FB).
2.  **Level 1 (Surface):** White cards and sidebars with a 1px border (#E2E8F0). No shadow.
3.  **Level 2 (Interaction):** Hover states on cards use a subtle ambient shadow: `0px 4px 12px rgba(15, 23, 42, 0.05)`.
4.  **Level 3 (Floating/Overlay):** Modals and dropdowns utilize a glassmorphic effect—`backdrop-filter: blur(8px)` with a semi-transparent white background (`rgba(255, 255, 255, 0.8)`) and a 1px white border to simulate light catching the edge.

Shadows are never pitch black; they are always tinted with the text-primary color at very low opacities to maintain a clean, high-end feel.

## Shapes
The shape language is "Soft-Modern." The standard corner radius is **16px (1rem)** for primary containers like cards and modals. 

- **Buttons & Inputs:** Use a radius of 8px (`rounded-md`) to appear more functional and less "toy-like."
- **Badges/Chips:** Use a fully rounded pill shape.
- **Icons:** Use Lucide-inspired icons with a 2px stroke weight and slightly rounded caps to match the UI's geometry.

## Components
- **Buttons:** Primary buttons use AirNav Blue with white text. Secondary buttons use a white background with a 1px border (#E2E8F0). Teritary buttons are text-only with a subtle background appearing on hover.
- **Input Fields:** 40px height for standard, 48px for large search bars. Backgrounds are pure white with a 1px border. On focus, the border changes to AirNav Blue with a 2px soft glow.
- **Cards:** White background, 16px corner radius, 1px subtle border. No shadow by default; shadow appears only on hover or when "picked up."
- **Document List:** Dense layout with 12px vertical padding. Use `label-sm` for file extensions and `body-sm` for last-modified dates.
- **Status Chips:** High-contrast text on a low-opacity version of the status color (e.g., "In Review" uses a light blue background with dark blue text).
- **Navigation:** Vertical sidebar with icons on the left. Active state is indicated by a subtle blue vertical bar on the left edge and a light blue background tint.