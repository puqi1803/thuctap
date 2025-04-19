<?php
session_start();
if (!isset($_SESSION['username-customer'])) {
    header('Location: ../login.php');
    exit;
}
?>