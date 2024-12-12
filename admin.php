<?php
session_start();

if (!isset($_SESSION['username-user'])) {
    header('Location: login.php');
    exit;
}

$page = isset($_GET['page']) ? $_GET['page'] : 'admin';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Quản lý website</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="description" content=""/>
    <meta http-equiv="refresh" content="900">
</head>

<body>
    <?php include 'header-blank.php'; ?>
    <main class="admin">
        <!----------------
        <div class="d-flex flex-row mt-5 px-5 justify-content-between align-items-center">
            <h2 class="title-page">Quản lý website</h2>
            <a href="logout.php"><i class="logout icon fa-solid fa-right-from-bracket"></i></a>
        </div>
        ------------>
        <div class="row">
            <div class="sidebar col-3 p-5 d-flex flex-column justify-content-normal border-normal background-gray">
                    <div>
                        <a class="menu-toggle" href="?page=dashboard">Quản lý</a>
                    </div>
                    <hr>
                    <div>
                        <a class="menu-toggle">Bài viết</a>
                        <div class="sub-menu" style="display: none;">
                            <a class="list-group-item" href="?page=admin-post">Tất cả bài viết</a>
                            <a class="list-group-item" href="?page=admin-new-post">Thêm bài viết</a>
                        </div>
                    </div>
                    <hr>
                    <div>
                        <a class="menu-toggle">Tour</a>
                        <div class="sub-menu" style="display: none;">
                            <a class="list-group-item" href="?page=admin-tour">Tất cả  tour</a>
                            <a class="list-group-item" href="?page=admin-new-tour">Thêm tour</a>
                        </div>
                    </div>
                    <hr>
                    <div>
                        <a class="menu-toggle">Khách hàng</a>
                        <div class="sub-menu" style="display: none;">
                            <a class="list-group-item" href="?page=admin-customer">Tất cả khách hàng</a>
                            <a class="list-group-item" href="?page=admin-new-customer">Thêm khách hàng</a>
                        </div>
                    </div>
                    <hr>
                    <div>
                        <a class="menu-toggle" href="logout.php">Đăng xuất</a>
                    </div>
            </div>
            <div class="content col p-5">
                <?php
                switch ($page) {
                    case 'admin-post':
                        include 'admin-post.php';
                        break;
                    case 'admin-new-post':
                        include 'admin-new-post.php';
                        break;                        
                    case 'admin-tour':
                        include 'admin-tour.php';
                        break;
                    case 'admin-new-tour':
                        include 'admin-new-tour.php';
                        break;
                    case 'admin-customer':
                        include 'admin-customer.php';
                        break;
                    case 'admin-new-customer':
                        include 'admin-new-customer.php';
                        break;
                    default:
                        include 'dashboard.php';
                        break;
                }
                ?> 
            </div>
        </div>

        <script>
        document.querySelectorAll('.menu-toggle').forEach(item => {
            item.addEventListener('click', event => {
                const subMenu = item.nextElementSibling;
                if (subMenu.style.display === "block") {
                    subMenu.style.display = "none";
                } else {
                    subMenu.style.display = "block";
                }
                event.preventDefault();
            });
        });
        </script>
    </main>
</body>
</html>