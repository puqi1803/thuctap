<?php
session_start();
$error_message = "";
$success_message = "";

if($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'partical/db_connect.php';

    $username_customer = isset($_POST['username-customer']) ? $_POST['username-customer'] : '';
    $new_password_customer = isset($_POST['new-password-customer']) ? $_POST['new-password-customer'] : '';
    $old_password_customer = isset($_POST['old-password-customer']) ? $_POST['old-password-customer'] : '';

        $sql = "SELECT * FROM customer WHERE `username-customer` = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username_customer); 
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($old_password_customer == $row['password-customer']) {
                $sql_update = "UPDATE customer SET
                `username-customer` = ?,
                `password-customer`= ?
                WHERE `username-customer` = ?";

                $stmt_update = $conn->prepare($sql_update);
                $stmt_update->bind_param("sss",
                $username_customer,
                $new_password_customer,
                $username_customer);

                if ($stmt_update->execute()) {
                    $_SESSION['success_message'] = 'Thay đổi mật khẩu thành công!';
                    header ("Location: login.php");
                    exit;
                } else {
                    $error_message = 'Đã có lỗi xảy ra, vui lòng thử lại!';
                }
                $stmt_update->close();
            } else if ($old_password_customer != $row['password-customer']) {
                $error_message = 'Mật khẩu cũ và mật khẩu mới chưa khớp! Vui lòng thử lại';
            }
        } else {
            $error_message = 'Tài khoản chưa có trên hệ thống';
        }
        $stmt->close();
        $conn->close();
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
    <main class="container forgot-password d-flex flex-column my-5 align-items-center">
        <h2 class="title-page text-center">Đăng ký</h2>
        <form class=" forgot-password-content d-flex flex-column mt-4 row-gap-4 border-round p-5" action="forgot-password.php" method="post">
            <div class="d-flex flex-row align-items-cente">
                <label class="col-5 accent" for="username-customer">Tên đăng nhập:</label>
                <input class="col p-2 border-round" type="text" id="username-customer" name="username-customer" required>
            </div>
            <div class="d-flex flex-row align-items-center">
                <label class="col-5 accent" for="password">Mật khẩu cũ:</label>
                <input class="col p-2 border-round" type="password" id="old-password-customer" name="old-password-customer" required>
            </div>
            <div class="d-flex flex-row align-items-center">
                <label class="col-5 accent" for="confirm_password">Mật khẩu mới:</label>
                <input class="col p-2 border-round" type="password" id="new-password-customer" name="new-password-customer" required>
            </div>
            <?php if (!empty($error_message)) : ?>
                <p class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></p>
            <?php endif; ?>
            <?php if (!empty($success_message)) : ?>
                <p class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></p>
            <?php endif; ?>
            <div class="text-center">
                <button class="button-primary w-100 p-2"type="submit">Lưu</button>
            </div>
            <div class="note">
                <p>Bạn đã có tài khoản? <a href="login.php">Đăng nhập</a></p>
            </div>
        </form>
    </main>
</body>
</html>
