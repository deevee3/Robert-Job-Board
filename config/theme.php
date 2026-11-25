<?php

return [

    /*
    |--------------------------------------------------------------------------
    | App branding (preproduction step)
    |--------------------------------------------------------------------------
    |
    | Branding is a PREPRODUCTION step: it happens before the build, not
    | after it. The branding step produces a brand package — app name, logo
    | file, colors, fonts — and this config carries those values. The base
    | pages (landing, sign-in, dashboard, settings, billing) read this
    | config, so each app built from the kit looks like itself without
    | forking the kit.
    |
    | The kit stays brand-neutral until the branding step's values are
    | applied. To brand an app:
    |
    |   1. Set APP_NAME / APP_TAGLINE (or edit the values below).
    |   2. Drop the app's logo file at public/images/logo.svg
    |      (or set APP_LOGO to its path).
    |   3. Set the THEME_* color and font values.
    |
    | Done. No per-app code branches, no copied layouts.
    |
    */

    'name' => env('APP_NAME', 'Factory App'),

    'tagline' => env('APP_TAGLINE', ''),

    // Path to the logo file, relative to the public directory.
    'logo' => env('APP_LOGO', '/images/logo.svg'),

    'colors' => [
        'primary' => env('THEME_PRIMARY', '#1a56db'),
        'accent' => env('THEME_ACCENT', '#0e9f6e'),
        'background' => env('THEME_BACKGROUND', '#ffffff'),
        'surface' => env('THEME_SURFACE', '#f9fafb'),
        'text' => env('THEME_TEXT', '#111827'),
        'muted' => env('THEME_MUTED', '#6b7280'),
    ],

    'fonts' => [
        'sans' => env('THEME_FONT_SANS', "system-ui, -apple-system, 'Segoe UI', sans-serif"),
        'display' => env('THEME_FONT_DISPLAY', "system-ui, -apple-system, 'Segoe UI', sans-serif"),
    ],

];
