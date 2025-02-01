<?php
// Start the session if you need to maintain session data
session_start();

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    // If logged in, redirect to appropriate page (e.g., dashboard or home)
    header("Location: views/admin/dashboard.php");
    exit();
}

// If not logged in, redirect to login page
header("Location: views/auth/login.php");
exit();
