<?php
session_start();

// Redirect to account.php if not logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: account.php");
    exit;
} else {
    header("Location: home.php");
    exit;
}
?>
