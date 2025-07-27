<?php
session_start();
if (!isset($_SESSION['username-user'])) {
    header('Location: ../login.php');
    exit;
}
?>