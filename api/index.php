<?php

/**
 * Vercel Serverless Entry Point
 * This file allows Laravel to be served via Vercel's serverless functions by proxying all requests to the public directory.
 */

// Initialize /tmp/storage structure required by Vercel's read-only filesystem
$tmpStorage = '/tmp/storage';

if (!is_dir($tmpStorage)) {
    mkdir($tmpStorage, 0755, true);
    mkdir($tmpStorage . '/framework/cache', 0755, true);
    mkdir($tmpStorage . '/framework/views', 0755, true);
    mkdir($tmpStorage . '/framework/sessions', 0755, true);
    mkdir($tmpStorage . '/logs', 0755, true);
}

// Pass control to Laravel
require __DIR__ . '/../public/index.php';

