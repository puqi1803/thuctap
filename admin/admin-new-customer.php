<?php
    include '../includes/check-login.php';
    include '../partical/db_connect.php';
    include '../includes/functions.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username_customer = $_POST['username-customer'];
        $password_customer = $_POST['password-customer'];
        $name_customer = $_POST['name-customer'];
        $birthday_customer = $_POST['birthday-customer'];
        $address_customer = $_POST['address-customer'];
        $phone_customer = $_POST['phone-customer'];
        $avatar_customer = null;

        include '../includes/check-image.php';

        $sql = "SELECT * FROM customer WHERE `username-customer` = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username_customer); 
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error_message = 'Tên đăng nhập đã tồn tại';
        } else {
            //$hashed_password = password_hash($password_customer, PASSWORD_DEFAULT);
            $sql = "INSERT INTO customer (
                `username-customer`,
                `password-customer`,
                `name-customer`,
                `birthday-customer`,
                `address-customer`,
                `phone-customer`,
                `avatar-customer`
                ) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssss",
                $username_customer,
                $password_customer,
                $name_customer,
                $birthday_customer,
                $address_customer,
                $phone_customer,
                $avatar_customer
            );
            if ($stmt->execute()) {
                $id_customer = $conn->insert_id;
                header("Location: admin-edit-customer?id-customer=$id_customer");
                exit();
            } else {
                echo 'Lỗi: ' . $stmt->error;
            }
            $stmt->close();
        }
    }

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Thêm khách hàng</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="description" content=""/>
    <meta http-equiv="refresh" content="900">
</head>

<body>
    <?php include '../header-blank.php'; ?>
    <div class="container-fluid taskbar">
    <div class="container d-flex flex-row py-2 px-4 justify-content-between">
        <div class="d-flex flex-row column-gap-4">
            <button class="button-none"
                onclick= "if(confirm('Bạn sẽ rời khỏi trang này khi bài viết chưa được lưu. Bạn chắc chắn chứ?'))
                {window.location.href='admin?page=admin-post';}">
                <i class="icon fa-solid fa-arrow-left"></i>&nbsp;&nbsp;Trở về
            </button>
        </div>
        <div class="d-flex flex-row column-gap-4"> 
            <a href="/nienluan.com/"><i class="icon fa-solid fa-house"></i>  Trang chủ</a>
        </div>
    </div>
    </div>
    <main class="container admin-new-customer">
        <form method="POST" enctype="multipart/form-data">
            <div class="row mt-5 column-gap-4">
                <div class="col-8 d-flex flex-column mt-4 row-gap-4">
                    <div class="d-flex flex-column row-gap-2">
                        <div class="d-flex flex-row column-gap-2 align-items-center">
                            <label class="w-25" for="name-customer">Họ tên</label>
                            <input type="text" name="name-customer"></input>
                        </div>
                        <div class="d-flex flex-row column-gap-2 align-items-center">
                            <label class="w-25" for="username-customer">Tài khoản</label>
                            <input type="text" id="username-customer" name="username-customer" require></input>
                        </div>
                        <div class="d-flex flex-row column-gap-2 align-items-center">
                            <label class="w-25" for="password-customer">Mật khẩu</label>
                            <input type="password" id="password-customer" name="password-customer" require></input>
                        </div>
                        <div class="d-flex flex-row column-gap-2 align-items-center">
                            <label class="w-25" for="birthday-customer">Ngày tháng năm sinh</label>
                            <input type="date" id="birthday-customer" name="birthday-customer"></input>
                        </div>
                        <div class="d-flex flex-row column-gap-2 align-items-center">
                            <label class="w-25" for="address-customer">Địa chỉ</label>
                            <input type="text" id="address-customer" name="address-customer"></input>
                        </div>
                        <div class="d-flex flex-row column-gap-2 align-items-center">
                            <label class="w-25" for="phone-customer">Số điện thoại</label>
                            <input type="number" id="phone-customer" name="phone-customer"></input>
                        </div>
                        <div class="d-flex flex-row column-gap-2 align-items-center">
                            <label class="w-25" for="email-customer">Email</label>
                            <input type="email" id="email-customer" name="email-customer"></input>
                        </div>
                    </div>
                </div>
                <div class="col d-flex flex-column mt-4 row-gap-4">
                    <div class="d-flex flex-column row-gap-2">
                        <label for="avatar-customer">Ảnh đại diện</label>
                        <div id="image-preview"></div>
                        <input type="file" id="avatar-customer" name="avatar-customer" accept="image/*" onchange="previewImage(event)">
                    </div>
                    <?php if (!empty($error_message)) : ?>
                        <p class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></p>
                    <?php endif; ?>
                    <?php if (!empty($success_message)) : ?>
                        <p class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></p>
                    <?php endif; ?>
                    <div class="d-flex flex-row column-gap-4 justify-content-end"> 
                        <button class="button-primary px-3 py-2" type="submit" name="submit">
                            <i class="icon fa-solid fa-floppy-disk"></i>&nbsp;&nbsp;Lưu
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </main>
    <script>
        function previewImage(event) {
            const imagePreview = document.getElementById('image-preview');
            imagePreview.innerHTML = '';
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = 'Thumbnail';
                    img.style.maxWidth = '200px';
                    img.style.maxHeight = '150px';
                    img.style.objectFit = 'cover';
                    imagePreview.appendChild(img);
                }
            }
        }
    </script>
</body>
<?php
    // Đóng kết nối
    $conn->close();
?>

</html>