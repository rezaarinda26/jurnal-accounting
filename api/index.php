<?php

/**
 * Vercel Serverless Entry Point
 * This file allows Laravel to be served via Vercel's serverless functions by proxying all requests to the public directory.
 */

// Initialize /tmp/storage structure required by Vercel's read-only filesystem
$tmpStorage = '/tmp/storage';
$tmpCache = '/tmp/bootstrap/cache';

if (!is_dir($tmpStorage)) {
    mkdir($tmpStorage, 0755, true);
    mkdir($tmpStorage . '/framework/cache', 0755, true);
    mkdir($tmpStorage . '/framework/views', 0755, true);
    mkdir($tmpStorage . '/framework/sessions', 0755, true);
    mkdir($tmpStorage . '/logs', 0755, true);
}

if (!is_dir($tmpCache)) {
    mkdir($tmpCache, 0755, true);
}

// Redirect all Laravel Cache Manifests to the writable /tmp folder
$_ENV['APP_SERVICES_CACHE'] = $tmpCache . '/services.php';
$_ENV['APP_PACKAGES_CACHE'] = $tmpCache . '/packages.php';
$_ENV['APP_CONFIG_CACHE'] = $tmpCache . '/config.php';
$_ENV['APP_ROUTES_CACHE'] = $tmpCache . '/routes.php';
$_ENV['APP_EVENTS_CACHE'] = $tmpCache . '/events.php';

// Pass control to Laravel
require __DIR__ . '/../public/index.php';

