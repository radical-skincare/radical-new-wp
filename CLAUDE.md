# Radical Skincare — Plain PHP WordPress Theme

## What this is
A plain PHP refactor of `projects/radical-wp` (Sage 9 / Blade / Webpack).
Stack: PHP templates, flat CSS, jQuery, no Composer, no build tools.

## Source codebase (read-only reference)
Path: ~/Documents/ViresBot/projects/radical-wp
- Blade views:   resources/views/
- PHP app layer: app/
- SCSS:          resources/assets/styles/
- JS:            resources/assets/scripts/
- ACF JSON:      resources/acf-json/

## Target codebase (write here)
Path: ~/Documents/ViresBot/projects/radical-new-wp (current directory)
Follow the exact directory structure in docs/development/03-target-architecture.md.

## Cardinal rules
- NEVER create .blade.php files — all templates are plain .php
- NEVER use Composer — zero require_once for vendor/autoload.php
- NEVER use asset_path() — replace with get_template_directory_uri() . '/assets/...'
- NEVER use sage(), config(), template() helper functions
- NEVER reference App\ namespace or Sober\Controller
- NEVER modify any file in the source codebase (radical-wp)
- All get_template_directory() calls in inc/ files are correct — do not change them

## Blade → PHP conversion cheat sheet
@extends('layouts.app')            → REMOVE (get_header/get_footer handles this)
@section('content') @endsection    → REMOVE (content goes directly in file)
@include('partials.foo.bar')       → get_template_part('template-parts/foo/bar')
@include('partials.header')        → get_template_part('template-parts/header/header')
{{ $var }}                         → <?php echo esc_html($var); ?>
{!! $var !!}                       → <?php echo $var; ?>
@php ... @endphp                   → <?php ... ?>
@if(...) @endif                    → <?php if(...): ?> <?php endif; ?>
@foreach(...) @endforeach          → <?php foreach(...): ?> <?php endforeach; ?>
@asset('styles/main.css')          → get_template_directory_uri() . '/assets/css/main.css'
$siteName (App controller var)     → get_bloginfo('name')
$title (App controller var)        → radical_page_title() [defined in inc/helpers.php]

## WooCommerce email templates
resources/woocommerce/emails/ → woocommerce/emails/
These are already plain PHP — copy without conversion.

## Disabled integrations (do not enable)
- user-coupons.php — intentionally commented out in source, keep disabled
- NOTUSED_subscription-features-skip_code.php — do not port

## JS approach
Single concatenated assets/js/main.js — no import/export, no bundler.
All modules use IIFE or class pattern with jQuery. Initialize on jQuery(document).ready.

## Wave order
Work strictly wave-by-wave. Commit after each wave. Do not skip ahead.
Wave 1: Foundation → Wave 2: Core Templates → Wave 3: PHP Includes →
Wave 4: Custom Templates → Wave 5: Template Parts → Wave 6: WooCommerce →
Wave 7: Assets → Wave 8: Cleanup
