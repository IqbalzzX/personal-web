<?php
if (!function_exists('base_url')) {
    function base_url($path = '') {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
        $host = $_SERVER['HTTP_HOST'];
        $script = $_SERVER['SCRIPT_NAME'];
        $folder = rtrim(dirname($script), '/\\');
        return $protocol . "://" . $host . $folder . '/' . ltrim($path, '/');
    }
}
?>