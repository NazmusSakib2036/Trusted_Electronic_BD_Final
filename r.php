<?php
echo "Server IP: " . $_SERVER['SERVER_ADDR'] ?? 'Not available';
echo "<br>Public IP: ";
echo file_get_contents('https://api.ipify.org');
?>