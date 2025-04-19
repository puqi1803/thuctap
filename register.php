<?php
session_start();
$error_message = "";
$success_message = "";

if($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'partical/db_connect.php';

    $username_customer = isset($_POST['username-customer']) ? $_POST['username-customer'] : '';
    $password_customer = isset($_POST['password-customer']) ? $_POST['password-customer'] : '';
    $confirm_password_customer = isset($_POST['confirm-password-customer']) ? $_POST['confirm-password-customer'] : '';

    if ($password_customer !== $confirm_password_customer) {
        $error_message = 'Mật khẩu và xác nhận mật khẩu chưa khớp! Vui lòng thử lại';
    } else {
        $sql = "SELECT * FROM customer WHERE `username-customer` = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username_customer); 
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error_message = 'Tên đăng nhập đã tồn tại';
        } else {
            //$hashed_password = password_hash($password_customer, PASSWORD_DEFAULT);
            $sql = "INSERT INTO customer (`username-customer`,`password-customer`) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $username_customer, $password_customer);
            if ($stmt->execute()) {
                $_SESSION['success_message'] = 'Đăng ký thành công! Đăng nhập ngay!';
                header ("Location: login.php");
                exit;
            } else {
                $error_message = 'Đã có lỗi xảy ra, vui lòng thử lại!';
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Đăng ký</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="description" content=""/>
    <script src="https://kit.fontawesome.com/bbc8bd235c.js" crossorigin="anonymous"></script>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="resources/style.css">
    <meta http-equiv="refresh" content="900">
</head>

<body>
    <main class="container register d-flex flex-column my-5 align-items-center">
        <h2 class="title-page text-center">Đăng ký</h2>
        <form class="register-content d-flex flex-column mt-4 row-gap-4 border-round p-5" action="register.php" method="post">
            <div class="d-flex flex-row align-items-cente">
                <label class="col-5 accent" for="username-customer">Tên đăng nhập:</label>
                <input class="col p-2 border-round" type="text" id="username-customer" name="username-customer" required>
            </div>
            <div class="d-flex flex-row align-items-center">
                <label class="col-5 accent" for="password">Mật khẩu:</label>
                <input class="col p-2 border-round" type="password" id="password-customer" name="password-customer" required>
            </div>
            <div class="d-flex flex-row align-items-center">
                <label class="col-5 accent" for="confirm_password">Xác nhận mật khẩu:</label>
                <input class="col p-2 border-round" type="password" id="confirm-password-customer" name="confirm-password-customer" required>
            </div>
            <div class="text-center">
                <button class="button-primary w-100 p-2"type="submit">Đăng ký</button>
            </div>
            <div class="note">
                <p>Bạn đã có tài khoản? <a href="login.php">Đăng nhập</a></p>
            </div>
        </form>
    </main>
</body>
</html>
