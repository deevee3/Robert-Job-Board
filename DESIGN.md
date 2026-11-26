# Apply — Design

Committed world: **Caligra** (caligra.com), pinned by D'Vaughn 2026-11-25. Landing surface mode: **Persuade**. App surfaces (board, dashboard, billing): **Operate**.

## Tokens (measured from caligra.com, 2026-11-25)

- `--iron: #2d2e2b` — ink / dark text
- Dark ground: `#2D312F` (hero band)
- `--aluminium: #e9ebe2` — pale surface tint (hover fills)
- `--silver: #b5b7ae`
- `--palladium: #71726d` — muted text (4.9:1 on white, passes AA body)
- `--accent: #d65822` — Caligra's accent; Apply does not use it yet
- Type: Söhne Buch 400 on Caligra; Apply self-hosts **Liberation Sans** (neo-grotesque stand-in, matches the wordmark letterforms). Tracking −0.03em display, −0.01em headings.
- Shape: flat, sharp 0-radius buttons with "→", hairline rules (1px), no shadows, no gradients.
- Motion: Caligra ships none; Apply allows 0.2s ease-out hover transitions only, behind `prefers-reduced-motion: no-preference`.

## Patterns

- Hero: one big headline, no paragraph block. Hairline, product name, one-liner, then the product visual (the real board, freshest rows).
- Giant lowercase sign-off ("apply early") closes the page.
- Thin pages: hero first, condensed; dense content in controls, never a wall of text at the top.
- Wordmark: "Apply" as SVG paths from Liberation Sans Regular, ink #2D2E2B. Favicon: white A on #2D312F.
