<?php

/**
 * Vercel Serverless Entry Point
 * This file allows Laravel to be served via Vercel's serverless functions by proxying all requests to the public directory.
 */

require __DIR__ . '/../public/index.php';
