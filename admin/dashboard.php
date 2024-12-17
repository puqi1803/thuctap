<?php

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Quản lý website - Quản lý chung</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="description" content=""/>
    <meta http-equiv="refresh" content="900">
</head>

<body>
    <?php include '../header-blank.php'; ?>
    <main class="container admin">
        <?php
        if(isset($_SESSION['username-user'])) {
           $username = $_SESSION['username-user'];
        } else {
            $username = "Khách";
        };
        ?>
    <p>Bạn đang đăng nhập bằng tài khoản <span class="accent"><?php echo htmlspecialchars($username)?></span></p>
    <p>Bạn muốn sử dụng tài khoản khác? <a class="link" href="../logout">Đăng xuất</a></p>
    </main>
</body>
</html>
