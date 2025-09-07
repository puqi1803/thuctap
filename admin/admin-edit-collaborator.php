<?php
    include '../includes/check-login.php';
    include '../partical/db_connect.php';
    include '../includes/functions.php';

    if ($_GET['id-collaborator']) {
        $id_collaborator = ($_GET['id-collaborator']);

        $sql = "SELECT * FROM collaborator WHERE `id-collaborator` = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $id_collaborator); 
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $collaborator = $result->fetch_assoc();
        } else {
            $error_message = "Lỗi, không tồn tại";
            exit;
        }
    }

    $sql_role_collaborator = "SELECT `name-role-collaborator` FROM `role-collaborator`";
    $result_role_collaborator = $conn->query($sql_role_collaborator);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name_collaborator = $_POST['name-collaborator'];
        $dob_collaborator = $_POST['dob-collaborator'];
        $sex_collaborator = $_POST['sex-collaborator'];
        $address_collaborator = $_POST['address-collaborator'];
        $identify_collaborator = $_POST['identify-collaborator'];
        $poi_collaborator = $_POST['poi-collaborator'];
        $doi_collaborator = $_POST['doi-collaborator'];
        $tax_collaborator = $_POST['tax-collaborator'];
        $bank_collaborator = $_POST['bank-collaborator'];
        $number_bank_collaborator = $_POST['number-bank-collaborator'];
        $phone_collaborator = $_POST['phone-collaborator'];
        $nog_collaborator = $_POST['nog-collaborator'];
        $role_collaborator = $_POST['role-collaborator'];
        $img_collaborator = $collaborator['img-collaborator'] ?? NULL;
        
        if (isset($_FILES['img-collaborator']) && $_FILES['img-collaborator']['error'] == 0) {
            include '../includes/check-image.php';
        }

        $sql = "UPDATE collaborator SET
            `name-collaborator` = ?,
            `dob-collaborator` = ?,
            `sex-collaborator` = ?,
            `address-collaborator` = ?,
            `identify-collaborator` = ?,
            `poi-collaborator` = ?,
            `doi-collaborator` = ?,
            `tax-collaborator` = ?,
            `bank-collaborator` = ?,
            `number-bank-collaborator` = ?,    
            `phone-collaborator` = ?,
            `nog-collaborator` = ?,
            `role-collaborator` = ?,
            `img-collaborator` = ?
            
        WHERE `id-collaborator` = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssssssssi",
            $name_collaborator,
            $dob_collaborator,
            $sex_collaborator,
            $address_collaborator,
            $identify_collaborator,
            $poi_collaborator,
            $doi_collaborator,
            $tax_collaborator,
            $bank_collaborator,
            $number_bank_collaborator,
            $phone_collaborator,
            $nog_collaborator,
            $role_collaborator,
            $img_collaborator,
            $id_collaborator
        );
        if ($stmt->execute()) {
            header("Location: admin-edit-collaborator?id-collaborator=$id_collaborator");
        exit();
    } else {
        echo 'Lỗi: ' . $stmt->error;
    }
    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Sửa khách hàng</title>
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
                    {window.location.href='admin?page=admin-collaborator';}">
                    <i class="icon fa-solid fa-arrow-left"></i>&nbsp;&nbsp;Trở về
                </button>
            </div>
            <div class="d-flex flex-row column-gap-4"> 
                <a href="/nienluan.com/"><i class="icon fa-solid fa-house"></i>  Trang chủ</a>
            </div>
        </div>
    </div>
    <main class="container admin-edit-collaborator">
        <form method="POST" enctype="multipart/form-data">
            <div class="row my-5 column-gap-4">
                <div class="col-9 d-flex flex-column row-gap-2">
                    <div class="d-flex flex-row column-gap-2 align-items-center">
                        <label class="w-25" for="name-collaborator">Họ tên</label>
                        <input type="text" name="name-collaborator" value="<?php echo htmlspecialchars($collaborator['name-collaborator']) ?>">
                    </div>
                    <div class="d-flex flex-row column-gap-2 align-items-center">
                        <label class="w-25" for="dob-collaborator">Ngày tháng năm sinh</label>
                        <input type="date" id="dob-collaborator" name="dob-collaborator" value="<?php echo htmlspecialchars($collaborator['dob-collaborator']) ?>">
                    </div>
                    <div class="d-flex flex-row column-gap-2 align-items-center">
                        <label class="w-25" for="sex-collaborator">Giới tính</label>
                        <?php $sex_collaborator = isset($collaborator['sex-collaborator']) ? $collaborator['sex-collaborator'] : ''; ?>
                        <select id="sex-collaborator" name="sex-collaborator">
                            <option value="" <?php echo ($sex_collaborator === '' || is_null($sex_collaborator)) ? 'selected' : ''; ?>>Chọn giới tính</option>
                            <option value="Ông" <?php echo ($sex_collaborator === 'Ông') ? 'selected' : ''; ?>>Nam</option>
                            <option value="Bà" <?php echo ($sex_collaborator === 'Bà') ? 'selected' : ''; ?>>Nữ</option>
                        </select>
                    </div>
                    <div class="d-flex flex-row column-gap-2 align-items-center">
                        <label class="w-25" for="address-collaborator">Địa chỉ</label>
                        <input type="text" id="address-collaborator" name="address-collaborator" value="<?php echo htmlspecialchars($collaborator['address-collaborator']) ?>">
                    </div>
                    <div class="d-flex flex-row column-gap-2 align-items-center">
                        <label class="w-25" for="identify-collaborator">Số CMND/CCCD</label>
                        <input type="text" id="identify-collaborator" name="identify-collaborator" value="<?php echo htmlspecialchars($collaborator['identify-collaborator']) ?>">
                    </div>
                    <div class="d-flex flex-row column-gap-2 align-items-center">
                        <label class="w-25" for="doi-collaborator">Ngày cấp</label>
                        <input type="text" id="doi-collaborator" name="doi-collaborator" value="<?php echo htmlspecialchars($collaborator['doi-collaborator']) ?>">
                    </div>
                    <div class="d-flex flex-row column-gap-2 align-items-center">
                        <label class="w-25" for="poi-collaborator">Nơi cấp</label>
                        <input type="text" id="poi-collaborator" name="poi-collaborator" value="<?php echo htmlspecialchars($collaborator['poi-collaborator']) ?>">
                    </div>
                    <div class="d-flex flex-row column-gap-2 align-items-center">
                        <label class="w-25" for="nog-collaborator">Số thẻ HDV</label>
                        <input type="text" id="nog-collaborator" name="nog-collaborator" value="<?php echo htmlspecialchars($collaborator['nog-collaborator']) ?>">
                    </div>
                    <div class="d-flex flex-row column-gap-2 align-items-center">
                        <label class="w-25" for="tax-collaborator">Mã số thuế cá nhân</label>
                        <input type="text" id="tax-collaborator" name="tax-collaborator" value="<?php echo htmlspecialchars($collaborator['tax-collaborator']) ?>">
                    </div>
                    <div class="d-flex flex-row column-gap-2 align-items-center">
                        <label class="w-25" for="number-bank-collaborator">Số tài khoản</label>
                        <input type="text" id="number-bank-collaborator" name="number-bank-collaborator" value="<?php echo htmlspecialchars($collaborator['number-bank-collaborator']) ?>">
                    </div>
                    <div class="d-flex flex-row column-gap-2 align-items-center">
                        <label class="w-25" for="bank-collaborator">Ngân hàng</label>
                        <input type="text" id="bank-collaborator" name="bank-collaborator" value="<?php echo htmlspecialchars($collaborator['bank-collaborator']) ?>">
                    </div>
                    <div class="d-flex flex-row column-gap-2 align-items-center">
                        <label class="w-25" for="phone-collaborator">Số điện thoại</label>
                        <input type="text" id="phone-collaborator" name="phone-collaborator" value="<?php echo htmlspecialchars($collaborator['phone-collaborator']) ?>">
                    </div>
                    <div class="d-flex flex-row column-gap-2 align-items-center">
                        <label class="w-25" for="role-collaborator">Nhiệm vụ</label>
                        <?php $role_collaborator = isset($collaborator['role-collaborator']) ? $collaborator['role-collaborator'] : ''; ?>
                        <select id="role-collaborator" name="role-collaborator">
                            <option value="">Chọn nhiệm vụ</option>
                            <?php 
                            if ($result_role_collaborator) {
                                if ($result_role_collaborator->num_rows > 0) {
                                    while ($name_role_collaborator = $result_role_collaborator->fetch_assoc()) {
                                        $selected_role_collaborator = ($name_role_collaborator['name-role-collaborator'] === $role_collaborator) ? 'selected' : '';
                                        echo '<option value="' . htmlspecialchars($name_role_collaborator['name-role-collaborator']) . '" ' . $selected_role_collaborator . '>'
                                            . htmlspecialchars($name_role_collaborator['name-role-collaborator']) .
                                            '</option>';
                                    }
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col d-flex flex-column row-gap-2">
                    <div class="d-flex flex-column justify-content-end"> 
                        <label for="img-post">Ảnh</label>
                        <?php if (!empty($collaborator['img-collaborator'])) : ?>
                            <img id="old-image" src="../resources/uploads/<?php echo htmlspecialchars($collaborator['img-collaborator']);?>" alt="<?php echo htmlspecialchars($collaborator['img-collaborator']); ?>">
                        <?php endif; ?>
                        <div id="image-preview"></div>
                        <input type="file" id="img-collaborator" name="img-collaborator" accept="image/*" onchange="previewImage(event)">
                    </div>
                    <div class="d-flex flex-row column-gap-4 justify-content-end mt-2"> 
                        <button class="button-primary px-3 py-2" type="submit" name="submit">
                            <i class="icon fa-solid fa-floppy-disk"></i>&nbsp;&nbsp;Lưu
                        </button>
                    </div>
                </div>
                <div class="">
                    <?php if (!empty($error_message)) : ?>
                        <p class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></p>
                    <?php endif; ?>
                    <?php if (!empty($success_message)) : ?>
                        <p class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </main>
</body>
<?php
    // Đóng kết nối
    $conn->close();
?>

</html>