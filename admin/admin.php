<?php
session_start();

if (!isset($_SESSION['username-user'])) {
    header('Location: ../login.php');
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
    <?php include '../header-blank.php'; ?>
    <main class="admin">
        <div class="taskbar d-flex flex-row py-2 px-4 justify-content-between">
			<div class="d-flex flex-row column-gap-4">
				<a href="/nienluan.com/"><i class="icon fa-solid fa-house"></i>  Trang chủ</a>
				<a href="/nienluan.com/tour"><i class="icon fa-solid fa-suitcase-rolling"></i>  Tour</a>
				<a href="/nienluan.com/tin-tuc"><i class="icon fa-solid fa-newspaper"></i>  Tin tức</a>
			</div>
			<div>
			<a href="../logout"><i class="icon fa-solid fa-right-from-bracket"></i>  Đăng xuất</a>
			</div>
		</div>

        <div class="row">
            <div class="sidebar col-2 p-5 d-flex flex-column justify-content-normal border background-gray">
                    <div>
                        <a class="menu-toggle" href="?page=dashboard">Quản lý</a>
                    </div>
                    <hr>
                    <div>
                        <a class="menu-toggle">Bài viết</a>
                        <div class="sub-menu" style="display: none;">
                            <a class="list-group-item" href="?page=admin-post">Tất cả bài viết</a>
                            <a class="list-group-item" href="admin-new-post">Thêm bài viết</a>
                        </div>
                    </div>
                    <hr>
                    <div>
                        <a class="menu-toggle">Tour</a>
                        <div class="sub-menu" style="display: none;">
                            <a class="list-group-item" href="?page=admin-tour">Tất cả  tour</a>
                            <a class="list-group-item" href="admin-new-tour">Thêm tour</a>
                        </div>
                    </div>
                    <hr>
                    <!------
                    <div>
                        <a class="menu-toggle">Khách hàng</a>
                        <div class="sub-menu" style="display: none;">
                            <a class="list-group-item" href="?page=admin-customer">Tất cả khách hàng</a>
                            <a class="list-group-item" href="admin-new-customer">Thêm khách hàng</a>
                        </div>
                    </div>
                    ----->
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
                subMenu.style.display = (subMenu.style.display === "block") ? "none" : "block";
                event.preventDefault();
            });
        });
        </script>
    </main>
</body>
</html>