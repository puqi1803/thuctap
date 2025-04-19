<?php
session_start();
$error_message = "";
$success_message = "";

if(isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}
if(isset($_SESSION['error_message'])) {
    $error_message = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'partical/db_connect.php';

    /*$username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    $sql = "SELECT `password-user` FROM user WHERE `username-user` = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();*/

    $username_customer = isset($_POST['username-customer']) ? $_POST['username-customer'] : '';
    $password_customer = isset($_POST['password-customer']) ? $_POST['password-customer'] : '';

    $sql = "SELECT  `password-customer`, `role-customer` FROM customer WHERE `username-customer` = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username_customer);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        //if (password_verify($password_customer, $row['password-customer'])) {
        if ($password_customer === $row['password-customer']) {
            $_SESSION['username-customer'] = $username_customer;
            header("Location: " . ($row['role-customer'] === 0 ? "admin/admin.php" : "customer.php"));
            exit;
        } else {
            $error_message = 'Mật khẩu không chính xác.';
        }
    } else {
        $error_message = 'Tên đăng nhập không tồn tại.';
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Đăng nhập</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="description" content=""/>
    <script src="https://kit.fontawesome.com/bbc8bd235c.js" crossorigin="anonymous"></script>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="resources/style.css">
    <meta http-equiv="refresh" content="900">
</head>

<body>
    <main class="login container d-flex flex-column my-5 align-items-center">
        <h2 class="title-page text-center">Đăng nhập</h2>
        <form class="login-content d-flex flex-column mt-4 row-gap-4 border-round p-5" action="login.php" method="post">
            <div class="d-flex flex-row align-items-center">
                <label class="col-4 accent" for="username-customer">Tên đăng nhập:</label>
                <input class="col p-2 border-round" type="text" id="username-customer" name="username-customer" required>
            </div>
            <div class="d-flex flex-row align-items-center">
                <label class="col-4 accent" for="password-customer">Mật khẩu:</label>
                <input class="col p-2 border-round" type="password" id="password-customer" name="password-customer" required>
            </div>
            <?php if (!empty($error_message)) : ?>
                <p class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></p>
            <?php endif; ?>
            <?php if (!empty($success_message)) : ?>
                <p class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></p>
            <?php endif; ?>
            <div class="text-center">
                <button class="button-primary w-100 p-2" type="submit">Đăng nhập</button>
            </div>
            <div class="note">
                <p>Bạn chưa có tài khoản? <a href="register.php">Đăng ký ngay</a></p>
            </div>
        </form>
    </main>
</body>
</html>