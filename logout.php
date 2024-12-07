<?php
session_start();
$_SESSION = []; // Xóa tất cả dữ liệu phiên
session_destroy(); // Hủy phiên
header('Location: login.php'); // Chuyển hướng về trang đăng nhập
exit;
?>