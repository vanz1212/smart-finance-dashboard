---
name: Nexio Financial
colors:
  surface: '#f7f9fb'
  surface-dim: '#d8dadc'
  surface-bright: '#f7f9fb'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f2f4f6'
  surface-container: '#eceef0'
  surface-container-high: '#e6e8ea'
  surface-container-highest: '#e0e3e5'
  on-surface: '#191c1e'
  on-surface-variant: '#45464d'
  inverse-surface: '#2d3133'
  inverse-on-surface: '#eff1f3'
  outline: '#76777d'
  outline-variant: '#c6c6cd'
  surface-tint: '#565e74'
  primary: '#000000'
  on-primary: '#ffffff'
  primary-container: '#131b2e'
  on-primary-container: '#7c839b'
  inverse-primary: '#bec6e0'
  secondary: '#0051d5'
  on-secondary: '#ffffff'
  secondary-container: '#316bf3'
  on-secondary-container: '#fefcff'
  tertiary: '#000000'
  on-tertiary: '#ffffff'
  tertiary-container: '#002113'
  on-tertiary-container: '#009668'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#dae2fd'
  primary-fixed-dim: '#bec6e0'
  on-primary-fixed: '#131b2e'
  on-primary-fixed-variant: '#3f465c'
  secondary-fixed: '#dbe1ff'
  secondary-fixed-dim: '#b4c5ff'
  on-secondary-fixed: '#00174b'
  on-secondary-fixed-variant: '#003ea8'
  tertiary-fixed: '#6ffbbe'
  tertiary-fixed-dim: '#4edea3'
  on-tertiary-fixed: '#002113'
  on-tertiary-fixed-variant: '#005236'
  background: '#f7f9fb'
  on-background: '#191c1e'
  surface-variant: '#e0e3e5'
typography:
  display-lg:
    fontFamily: Plus Jakarta Sans
    fontSize: 48px
    fontWeight: '700'
    lineHeight: 56px
    letterSpacing: -0.02em
  display-lg-mobile:
    fontFamily: Plus Jakarta Sans
    fontSize: 32px
    fontWeight: '700'
    lineHeight: 40px
    letterSpacing: -0.02em
  headline-md:
    fontFamily: Plus Jakarta Sans
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 32px
  headline-sm:
    fontFamily: Plus Jakarta Sans
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
    fontSize: 12px
    fontWeight: '600'
    lineHeight: 16px
    letterSpacing: 0.05em
  stats-num:
    fontFamily: Inter
    fontSize: 28px
    fontWeight: '700'
    lineHeight: 32px
    letterSpacing: -0.01em
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  base: 4px
  container-max: 1280px
  gutter: 24px
  margin-desktop: 40px
  margin-mobile: 16px
  stack-sm: 8px
  stack-md: 16px
  stack-lg: 32px
---

## Brand & Style
The design system is engineered to project expertise, security, and absolute clarity. The visual narrative centers on "Financial Transparency"—the idea that complex data should be easily digestible and actionable. 

The aesthetic is **Modern Minimalism** with a focus on data density and high legibility. It utilizes a card-based architecture to compartmentalize different financial streams (Taxation, Planning, Targets) into logical, focused modules. The atmosphere is quiet and professional, avoiding decorative elements in favor of functional precision and intentional whitespace. This approach ensures the user feels in control and secure while navigating high-stakes financial information.

## Colors
The palette is rooted in established financial trust and growth.

*   **Primary (Deep Navy):** Used for structural navigation, primary headings, and high-level background containers to establish a solid foundation.
*   **Secondary (Trust Blue):** The action color. Used for primary buttons, active states, and interactive data points.
*   **Tertiary (Emerald Green):** Specifically reserved for positive financial indicators, growth metrics, and "on-track" status for financial targets.
*   **Background (Slate White):** A cool-toned neutral that reduces eye strain and provides a sterile, professional backdrop for data visualization.
*   **Error/Alert:** A crisp Crimson (#E11D48) is used sparingly for tax overdues or negative market movements.

## Typography
The system employs a dual-font strategy to balance character with utility. **Plus Jakarta Sans** is used for headlines to provide a modern, slightly softer professional edge. **Inter** is utilized for all body text, data tables, and financial figures due to its exceptional legibility at small sizes and its neutral, systematic tone.

For financial statistics and "Stata" views, use the `stats-num` style to ensure monetary values are the most prominent elements on the page. Numeric data should always use tabular lining figures to ensure columns of numbers align perfectly in reports and tax tables.

## Layout & Spacing
The design system utilizes a **12-column fluid grid** for desktop and a **single-column vertical stack** for mobile. 

The layout logic is governed by a strict 4px baseline grid. Components like financial cards and data tables are separated by `stack-lg` (32px) to provide significant breathing room, preventing the UI from feeling cluttered despite high data density. 

*   **Desktop:** 12 columns, 24px gutters, 40px side margins.
*   **Tablet:** 8 columns, 16px gutters, 24px side margins.
*   **Mobile:** 4 columns, 16px gutters, 16px side margins.

## Elevation & Depth
Hierarchy is established through **Tonal Layering** and **Ambient Shadows**. 

The main background is the lowest layer (Slate White). Interactive cards and content modules sit on a Pure White surface elevated by a very soft, diffused shadow (0px 4px 20px rgba(15, 23, 42, 0.05)). This subtle lift distinguishes content from the background without creating visual noise.

Navigation bars and sticky headers use a "Glassmorphic" blur (20px backdrop-filter) with 95% opacity to maintain context of the content scrolling beneath them while ensuring legibility.

## Shapes
The shape language is "Professional Rounded." By using a consistent 0.5rem (8px) radius, the system feels approachable and modern while maintaining a structured, grid-aligned discipline.

*   **Small Elements:** Checkboxes and small tags use `rounded-sm` (4px).
*   **Standard Elements:** Buttons, Input fields, and standard Cards use the base `roundedness` (8px).
*   **Large Containers:** Feature hero sections or large modal overlays use `rounded-lg` (16px).

## Components
Consistent implementation of these components ensures the financial platform remains intuitive:

*   **Cards:** The primary container. Must have a white background, 1px border (#E2E8F0), and the standard ambient shadow. Headers within cards should use `headline-sm`.
*   **Buttons:** 
    *   *Primary:* Deep Navy background, white text, 8px radius. 
    *   *Success:* Emerald Green background for "Finalize Tax" or "Meet Target."
*   **Input Fields:** Clear, 1px bordered boxes with 12px horizontal padding. Active states must use a 2px Trust Blue outline.
*   **Data Visualization (Stata):** Use thin-stroke line charts and clean bar graphs. Avoid 3D effects. Use Trust Blue for general data and Emerald Green for growth trends.
*   **Financial Progress Bars:** Used for Financial Targets. A thick 8px track (#F1F5F9) with a Trust Blue or Emerald Green fill indicating percentage completion.
*   **Status Chips:** Small, pill-shaped indicators with low-opacity backgrounds (e.g., 10% Emerald Green background with 100% Emerald Green text for "Paid").