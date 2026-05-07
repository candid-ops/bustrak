<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Debug Mode</h1>";
echo "<p>PHP is working!</p>";
echo "<p>PHP Version: " . phpversion() . "</p>";

// Check if .env exists
if (file_exists(__DIR__ . '/../.env')) {
    echo "<p style='color:green'>? .env file found</p>";
} else {
    echo "<p style='color:red'>? .env file NOT found</p>";
}

// Check database
if (file_exists(__DIR__ . '/../database/database.sqlite')) {
    echo "<p style='color:green'>? Database file found</p>";
} else {
    echo "<p style='color:red'>? Database file NOT found</p>";
}

// Check if vendor exists
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    echo "<p style='color:green'>? Vendor/autoload.php found</p>";
} else {
    echo "<p style='color:red'>? Vendor/autoload.php NOT found</p>";
}

// Try to load Laravel
echo "<h2>Testing Laravel Bootstrap:</h2>";
try {
    require_once __DIR__ . '/../vendor/autoload.php';
    echo "<p style='color:green'>? Autoloader loaded</p>";
    
     = require_once __DIR__ . '/../bootstrap/app.php';
    echo "<p style='color:green'>? App loaded</p>";
    
    echo "<p style='color:green'>? Laravel is working! Check /public/index.php</p>";
} catch (Exception ) {
    echo "<p style='color:red'>Error: " . ->getMessage() . "</p>";
    echo "<pre>" . ->getTraceAsString() . "</pre>";
}

// Check index.php
echo "<h2>Testing index.php:</h2>";
if (file_exists(__DIR__ . '/index.php')) {
    echo "<p style='color:green'>? index.php exists</p>";
    echo "<p>Attempting to include index.php...</p>";
    try {
        require_once __DIR__ . '/index.php';
    } catch (Exception ) {
        echo "<p style='color:red'>Error in index.php: " . ->getMessage() . "</p>";
    }
} else {
    echo "<p style='color:red'>? index.php NOT found</p>";
}
