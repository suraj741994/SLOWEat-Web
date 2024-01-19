<?php
session_start();

// Destroy the current session
session_destroy();

// Redirect to the login page
header("Location: index.html");
exit();
?>