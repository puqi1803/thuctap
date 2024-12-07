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
            $_SESSION['username'] = $username;
            header("Location: admin.php");
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
    <link rel="stylesheet" href="resources/style.css">
</head>

<body>
    <main class="container login flex flex-col my-8 align-items-center">
        <h2 class="title-page text-center">Đăng nhập</h2>
        <form class="flex flex-col mt-4 row-gap-4 border-normal px-8 py-8" action="login.php" method="post">
            <div class="flex flex-row column-gap-2 align-items-center">
                <label class="accent w-50" for="username">Tên đăng nhập:</label>
                <input class="px-2 py-2 border-normal" type="text" id="username" name="username" required>
            </div>
            <div class="flex flex-row column-gap-2 align-items-center">
                <label class="accent w-50" for="password">Mật khẩu:</label>
                <input class="px-2 py-2 border-normal" type="password" id="password" name="password" required>
            </div>
            <?php if (!empty($error_message)) : ?>
                <p class="danger"><?php echo $error_message; ?></p>
            <?php endif; ?>
            <div class="text-center mt-4">
                <button class="button-primary w-full px-2 py-2" type="submit">Đăng nhập</button>
            </div>
            <div class="note mt-2">
                <p>Bạn chưa có tài khoản? <a href="register.php">Đăng ký ngay</a></p>
            </div>
        </form>
    </main>
</body>
</html>