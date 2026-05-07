<?php
echo "Test file is working!";
echo "<br>Current directory: " . __DIR__;
echo "<br>Is public/index.php exists? " . (file_exists('index.php') ? 'Yes' : 'No');
