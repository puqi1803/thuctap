<?php
session_start();

if(!isset($_SESSION['username'])) {
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
    <script src="https://kit.fontawesome.com/bbc8bd235c.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="resources/style.css">
    <meta http-equiv="refresh" content="900">
</head>

<body>
    <main class="admin">
        <div class="flex flex-row column-gap-6 mt-8">
            <div class="slidebar flex flex-col w-20 row-gap-4 px-4 py-4 border-normal background-gray">
                <a href="?page=admin">Quản lý</a>
                <hr></hr>
                <a href="?page=admin-post">Bài viết</a>
                <hr></hr>
                <a href="?page=admin-tour">Tour</a>
                <hr></hr>
                <a href="?page=admin-customer">Khách hàng</a>
                <hr></hr>
                <a href="logout.php">Đăng xuất</a>
            </div>
            <div>
                <?php
                switch ($page) {
                    case 'admin-post':
                        include 'admin-post.php';
                        break;
                    case 'admin-tour':
                        include 'admin-tour.php';
                        break;
                    case 'admin-customer':
                        include 'admin-customer.php';
                        break;
                    default:
                        include 'dashboard.php';
                        break;
                }
                ?>
            </div>
        </div>
    </main>
</body>
</html>
