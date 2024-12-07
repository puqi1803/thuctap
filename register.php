<?php
session_start();
$error_message = "";
$success_message = "";

if($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'partical/db_connect.php';

    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

    if ($password !== $confirm_password) {
        $error_message = 'Mật khẩu và xác nhận mật khẩu chưa khớp! Vui lòng thử lại';
    } else {
        $sql = "SELECT * FROM user WHERE `username-user` = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username); 
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error_message = 'Tên đăng nhập đã tồn tại';
        } else {
            //$hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO user (`username-user`,`password-user`) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $username, $password);
            if ($stmt->execute()) {
                $success_message = 'Đăng ký thành công!';
                header ("Location: noti-register-success.php");
                exit;
            } else {
                $error_message = 'Đã có lỗi xảy ra, vui lòng thử lại!';
            }
        }
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
    <link rel="stylesheet" href="resources/style.css">
    <meta http-equiv="refresh" content="900">
</head>

<body>
    <main class="container login flex flex-col my-8 align-items-center">
        <h2 class="title-page text-center">Đăng ký</h2>
        <form class="flex flex-col mt-4 row-gap-4 border-normal px-8 py-8" action="register.php" method="post">
            <div class="flex flex-row column-gap-2 align-items-center">
                <label class="accent w-50" for="username">Tên đăng nhập:</label>
                <input class="px-2 py-2 border-normal" type="text" id="username" name="username" required>
            </div>
            <div class="flex flex-row column-gap-2 align-items-center">
                <label class="accent w-50" for="password">Mật khẩu:</label>
                <input class="px-2 py-2 border-normal" type="password" id="password" name="password" required>
            </div>
            <div class="flex flex-row column-gap-2 align-items-center">
                <label class="accent w-50" for="confirm_password">Xác nhận mật khẩu:</label>
                <input class="px-2 py-2 border-normal" type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <?php if (!empty($error_message)) : ?>
                <p class="danger"><?php echo $error_message; ?></p>
            <?php endif; ?>
            <?php if (!empty($success_message)) : ?>
                <p class="accent"><?php echo $success_message; ?></p>
            <?php endif; ?>
            <div class="text-center mt-4">
                <button class="button-primary w-full px-2 py-2"type="submit">Đăng nhập</button>
            </div>
        </form>
    </main>
</body>
</html>
