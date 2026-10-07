<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Sunset Filament Asset Auto-Fixer</h2>";

function copy_dir($src, $dst) {
    if (!file_exists($src)) {
        echo "<p style='color:red;'>Source $src does not exist.</p>";
        return;
    }
    $dir = opendir($src);
    @mkdir($dst, 0777, true);
    while (false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            if (is_dir($src . '/' . $file)) {
                copy_dir($src . '/' . $file, $dst . '/' . $file);
            } else {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}

// Copy public/css and public/js to root css and js
$sourceCss = __DIR__ . '/public/css';
$destCss   = __DIR__ . '/css';

$sourceJs  = __DIR__ . '/public/js';
$destJs    = __DIR__ . '/js';

if (file_exists($sourceCss)) {
    copy_dir($sourceCss, $destCss);
    echo "<p style='color:green; font-weight:bold;'>✅ Copied CSS assets from public/css to /css successfully!</p>";
} else {
    echo "<p style='color:orange;'>public/css not found at $sourceCss</p>";
}

if (file_exists($sourceJs)) {
    copy_dir($sourceJs, $destJs);
    echo "<p style='color:green; font-weight:bold;'>✅ Copied JS assets from public/js to /js successfully!</p>";
} else {
    echo "<p style='color:orange;'>public/js not found at $sourceJs</p>";
}

echo "<hr><p>Now refresh <a href='/admin/login' style='font-size:18px; font-weight:bold;'>https://admin.shacrawi.com/admin/login</a></p>";
