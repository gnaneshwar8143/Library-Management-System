<?php
session_start();

// Regenerate session ID for security
session_regenerate_id(true);

// Unset all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect to login page
header("Location: ../login.php");
exit;
?>

