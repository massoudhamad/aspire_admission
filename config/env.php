<?php
// Minimal .env loader. Reads KEY=VALUE pairs from <repo-root>/.env into $_ENV
// and exposes env(KEY, $default). Strips surrounding quotes; ignores comments.
// Loaded once per request — re-includes are a no-op.

if (!function_exists('env')) {
    function env($key, $default = null)
    {
        if (array_key_exists($key, $_ENV)) {
            $v = $_ENV[$key];
        } else {
            $v = getenv($key);
            if ($v === false) {
                return $default;
            }
        }
        // Booleans + null
        $lower = strtolower((string)$v);
        if ($lower === 'true')  return true;
        if ($lower === 'false') return false;
        if ($lower === 'null' || $lower === '') return $default;
        return $v;
    }
}

(function () {
    static $loaded = false;
    if ($loaded) return;
    $loaded = true;

    $path = dirname(__DIR__) . '/.env';
    if (!is_readable($path)) return;

    foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#') continue;
        if (!str_contains($line, '=')) continue;

        [$k, $v] = array_map('trim', explode('=', $line, 2));
        if ($k === '') continue;

        // Strip optional surrounding quotes
        $len = strlen($v);
        if ($len >= 2 && (($v[0] === '"' && $v[$len-1] === '"') || ($v[0] === "'" && $v[$len-1] === "'"))) {
            $v = substr($v, 1, -1);
        }
        $_ENV[$k] = $v;
        putenv("$k=$v");
    }
})();
