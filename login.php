<?php
session_start();
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'partical/db_connect.php';

    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    $sql = "SELECT `password-user` FROM user WHERE `username-user` = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($password === $row['password-user']) {
            $_SESSION['username-user'] = $username;
            header("Location: admin/admin.php");
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
                <label class="col-4 accent" for="username">Tên đăng nhập:</label>
                <input class="col p-2 border-round" type="text" id="username" name="username" required>
            </div>
            <div class="d-flex flex-row align-items-center">
                <label class="col-4 accent" for="password">Mật khẩu:</label>
                <input class="col p-2 border-round" type="password" id="password" name="password" required>
            </div>
            <?php if (!empty($error_message)) : ?>
                <p class="danger"><?php echo $error_message; ?></p>
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