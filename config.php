<?php
// ============================================================
// config.php - App-wide settings + Security
// ============================================================

define('BASE_URL', 'http://localhost/cap/');
define('APP_NAME', 'Dental Clinic Management System');
define('APP_VERSION', '1.0.0');

date_default_timezone_set('Asia/Manila');
define('SESSION_LIFETIME', 28800);

// ============================================================
// ERROR HANDLING — hide errors from browser, log silently
// ============================================================
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/error.log');
error_reporting(E_ALL);

if (!is_dir(__DIR__ . '/../logs')) {
    mkdir(__DIR__ . '/../logs', 0755, true);
}

// Global exception handler
set_exception_handler(function($e) {
    error_log('[EXCEPTION] ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
    if (!headers_sent()) http_response_code(500);
    $isApi = strpos($_SERVER['REQUEST_URI'] ?? '', '/api/') !== false;
    if ($isApi) {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'A server error occurred.']);
    } else {
        include dirname(__DIR__) . '/error.php';
    }
    exit();
});

// Fatal error handler
register_shutdown_function(function() {
    $e = error_get_last();
    if ($e && in_array($e['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        error_log('[FATAL] ' . $e['message'] . ' in ' . $e['file'] . ':' . $e['line']);
        if (!headers_sent()) {
            http_response_code(500);
            include dirname(__DIR__) . '/error.php';
        }
    }
});

// ============================================================
// SECURITY HEADERS
// ============================================================
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');
