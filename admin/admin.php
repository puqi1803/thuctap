<?php
include '../partical/db_connect.php';
include '../includes/check-login.php';

$username_user = $_SESSION['username-user'];

$sql_user = "SELECT `role-user` FROM user WHERE `username-user` = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("s", $username_user);
$stmt_user->execute();
$result_user = $stmt_user->get_result();

if($result_user->num_rows>0) {
    $row_user = $result_user->fetch_assoc();
    $_SESSION['role-user'] = $row_user['role-user'];
}

$stmt_user->close();

if ($_SESSION['role-user'] !== 0) {
    header ('Location: ../user.php');
    exit;
}

$page = isset($_GET['page']) ? $_GET['page'] : 'admin';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Quản lý hệ thống</title>
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
                        <a class="menu-toggle">Dịch vụ</a>
                        <div class="sub-menu" style="display: none;">
                            <a class="list-group-item" href="?page=admin-service">Tất cả</a>
                            <a class="list-group-item" href="admin-new-service">Thêm dịch vụ</a>
                            <a class="list-group-item" href="?page=admin-location-service">Khu vực</a>
                            <a class="list-group-item" href="?page=admin-category-service">Loại hình</a>
                        </div>
                    </div>
                    <hr>
                    <div>
                        <a class="menu-toggle">Hợp đồng</a>
                        <div class="sub-menu" style="display: none;">
                            <a class="list-group-item" href="?page=admin-contract">Tất cả</a>
                            <a class="list-group-item" href="?page=admin-contract-type">Phân loại</a>
                        </div>
                    </div>
                    <hr>
                    <div>
                        <a class="menu-toggle">Tour</a>
                        <div class="sub-menu" style="display: none;">
                            <a class="list-group-item" href="?page=admin-tour">Tất cả</a>
                            <a class="list-group-item" href="?page=admin-new-tour">Thêm tour</a>
                            <a class="list-group-item" href="?page=admin-location">Địa điểm</a>
                        </div>
                    </div>
                    <hr>
                    <div>
                        <a class="menu-toggle">Nhân sự</a>
                        <div class="sub-menu" style="display: none;">
                            <a class="list-group-item" href="?page=admin-collaborator">Tất cả</a>
                            <a class="list-group-item" href="?page=admin-role-collaborator">Nhiệm vụ</a>
                        </div>
                    </div>
            </div>
            <div class="content col p-5">
                <?php
                switch ($page) {
                    case 'admin-service':
                        include 'admin-service.php';
                        break;
                    case 'admin-new-service':
                        include 'admin-new-service.php';
                        break;
                    case 'admin-category-service':
                        include 'admin-category-service.php';
                        break;               
                    case 'admin-location-service':
                        include 'admin-location-service.php';
                        break;
                    case 'admin-contract':
                        include 'admin-contract.php';
                        break;
                    case 'admin-contract-type':
                        include 'admin-contract-type.php';
                        break;
                    case 'admin-category-contract':
                        include 'admin-category-contract.php';
                        break;
                    case 'admin-collaborator':
                        include 'admin-collaborator.php';
                        break;
                    case 'admin-role-collaborator':
                        include 'admin-role-collaborator.php';
                        break;
                    case 'admin-tour':
                        include 'admin-tour.php';
                        break;
                    case 'admin-new-tour':
                        include 'admin-new-tour.php';
                        break;
                    case 'admin-location':
                        include 'admin-location.php';
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