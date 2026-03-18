<?php
// ============================================================
// db.php - Database connection + Security helpers
// ============================================================

// ============================================================
// SECURITY AREA  : ENVIRONMENT CONFIGURATION (.env)
// FILE LOCATION  : includes/db.php
// ============================================================
// Loads DB credentials from the .env file in the project root.
// Credentials are NO LONGER hardcoded — they stay out of your
// source code and out of GitHub.
// ============================================================
function load_env($path) {
    if (!file_exists($path)) return;
    foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#' || strpos($line, '=') === false) continue;
        [$k, $v] = explode('=', $line, 2);
        $k = trim($k); $v = trim($v);
        if (!array_key_exists($k, $_ENV)) { $_ENV[$k] = $v; putenv("$k=$v"); }
    }
}
load_env(__DIR__ . '/../.env');
