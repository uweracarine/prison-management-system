<?php
// Quick script to generate password hash for "123"
$password = "123";
$hash = password_hash($password, PASSWORD_DEFAULT);
echo "Password: " . $password . "\n";
echo "Hash: " . $hash . "\n";
?>

