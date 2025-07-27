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

    $username_user = isset($_POST['username-user']) ? $_POST['username-user'] : '';
    $password_user = isset($_POST['password-user']) ? $_POST['password-user'] : '';

    $sql = "SELECT  `password-user`, `role-user` FROM user WHERE `username-user` = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username_user);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($password_user === $row['password-user']) {
            $_SESSION['username-user'] = $username_user;
            header("Location: " . ($row['role-user'] === 0 ? "admin/admin.php" : "user.php"));
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
                <label class="col-4 accent" for="username-user">Tên đăng nhập:</label>
                <input class="col p-2 border-round" type="text" id="username-user" name="username-user" required>
            </div>
            <div class="d-flex flex-row align-items-center">
                <label class="col-4 accent" for="password-user">Mật khẩu:</label>
                <input class="col p-2 border-round" type="password" id="password-user" name="password-user" required>
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
                <a href="forgot-password.php">Quên mật khẩu</a>
                <p>Bạn chưa có tài khoản? <a href="register.php">Đăng ký ngay</a></p>
            </div>
        </form>
    </main>
</body>
</html>