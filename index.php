<?php
session_start();

if (isset($_SESSION['username'])) {
    // If a session is set, redirect to the main page (dashboard).
    require_once './pages/main.php';
    //header('Location: ./pages/main.php');
    exit();
} else {
    // If no session is set, redirect to the login page.
    //header('Location: ./pages/login.php');
    require_once './pages/login.php';
    exit();
}
?>