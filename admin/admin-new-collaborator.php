<?php
    include '../includes/check-login.php';
    include '../partical/db_connect.php';
    include '../includes/functions.php';

    $sql_contract_type = "SELECT `name-role-collaborator` FROM `role-collaborator`";
    $result_contract_type = $conn->query($sql_contract_type);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name_contract_type = $_POST['name-contract-type'];
        $template_contract_type = $_POST['template-contract-type'];
        $description_contract_type = $_POST['description-contract-type'];

    $sql = "INSERT INTO `contract-type` (
        `name-contract-type`,
        `template-contract-type`,
        `description-contract-type`
        ) VALUES (?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss",
        $name_collaborator,
        $template_contract_type,
        $description_contract_type
    );

    if ($stmt->execute()) {
        $id_contract_type = $conn->insert_id;
        header("Location: admin-edit-contract-type?id-contract-type= $id_contract_type");
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
    <title>Thêm mới hợp đồng</title>
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
    <main class="container admin-new-post">
        <form method="POST" enctype="multipart/form-data">
            <div class="row mt-5 column-gap-4">
                <div class="col-9 d-flex flex-column row-gap-2">
                    <div class="d-flex flex-row column-gap-2 align-items-center">
                        <label class="w-25" for="name-collaborator">Họ tên</label>
                        <input type="text" name="name-collaborator">
                    </div>
                    <div class="d-flex flex-row column-gap-2 align-items-center">
                        <label class="w-25" for="dob-collaborator">Ngày tháng năm sinh</label>
                        <input type="date" id="dob-collaborator" name="dob-collaborator">
                    </div>
                    <div class="d-flex flex-row column-gap-2 align-items-center">
                        <label class="w-25" for="sex-collaborator">Giới tính</label>
                        <?php $sex_collaborator = isset($collaborator['sex-collaborator']) ? $collaborator['sex-collaborator'] : ''; ?>
                        <select id="sex-collaborator" name="sex-collaborator">
                            <option value="" <?php echo ($sex_collaborator === '' || is_null($sex_collaborator)) ? 'selected' : ''; ?>>Chọn giới tính</option>
                            <option value="Nam" <?php echo ($sex_collaborator === 'Nam') ? 'selected' : ''; ?>>Nam</option>
                            <option value="Nữ" <?php echo ($sex_collaborator === 'Nữ') ? 'selected' : ''; ?>>Nữ</option>
                        </select>
                    </div>
                    <div class="d-flex flex-row column-gap-2 align-items-center">
                        <label class="w-25" for="address-collaborator">Địa chỉ</label>
                        <input type="text" id="address-collaborator" name="address-collaborator">
                    </div>
                    <div class="d-flex flex-row column-gap-2 align-items-center">
                        <label class="w-25" for="identify-collaborator">Số CMND/CCCD</label>
                        <input type="text" id="identify-collaborator" name="identify-collaborator">
                    </div>
                    <div class="d-flex flex-row column-gap-2 align-items-center">
                        <label class="w-25" for="poi-collaborator">Nơi cấp</label>
                        <input type="text" id="poi-collaborator" name="poi-collaborator">
                    </div>
                    <div class="d-flex flex-row column-gap-2 align-items-center">
                        <label class="w-25" for="nog-collaborator">Số thẻ HDV</label>
                        <input type="text" id="nog-collaborator" name="nog-collaborator">
                    </div>
                    <div class="d-flex flex-row column-gap-2 align-items-center">
                        <label class="w-25" for="phone-collaborator">Số điện thoại</label>
                        <input type="text" id="phone-collaborator" name="phone-collaborator">
                    </div>
                    <div class="d-flex flex-row column-gap-2 align-items-center">
                        <label class="w-25" for="role-collaborator">Nhiệm vụ</label>
                        <?php $contract_type = isset($collaborator['role-collaborator']) ? $collaborator['role-collaborator'] : ''; ?>
                        <select id="role-collaborator" name="role-collaborator">
                            <option value="">Chọn nhiệm vụ</option>
                            <?php 
                            if ($result_contract_type) {
                                if ($result_contract_type->num_rows > 0) {
                                    while ($name_contract_type = $result_contract_type->fetch_assoc()) {
                                        $selected_contract_type = ($name_contract_type['name-role-collaborator'] === $contract_type) ? 'selected' : '';
                                        echo '<option value="' . htmlspecialchars($name_contract_type['name-role-collaborator']) . '" ' . $selected_contract_type . '>'
                                            . htmlspecialchars($name_contract_type['name-role-collaborator']) .
                                            '</option>';
                                    }
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col d-flex flex-column row-gap-2">
                    <label for="img-post">Ảnh</label>
                    <?php if (!empty($collaborator['img-collaborator'])) : ?>
                        <img id="old-image" src="../resources/uploads/<?php echo htmlspecialchars($collaborator['img-collaborator']);?>" alt="<?php echo htmlspecialchars($collaborator['img-collaborator']); ?>">
                    <?php endif; ?>
                    <div id="image-preview"></div>
                    <input type="file" id="img-collaborator" name="img-collaborator" accept="image/*" onchange="previewImage(event)">
                </div>
                <div class="">
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