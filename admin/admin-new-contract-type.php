<?php
    include '../includes/check-login.php';
    include '../partical/db_connect.php';
    include '../includes/functions.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name_contract_type = $_POST['name-contract-type'];
        $template_contract_type = $_POST['template-contract-type'];
        $description_contract_type = $_POST['description-contract-type'];
        $templateID_contract_type = $_POST['templateID-contract-type'];

    $sql = "INSERT INTO `contract-type` (
        `name-contract-type`,
        `template-contract-type`,
        `description-contract-type`,
        `templateID-contract-type`
        ) VALUES (?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss",
        $name_contract_type,
        $template_contract_type,
        $description_contract_type,
        $templateID_contract_type
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
            <div class="mt-5 d-flex flex-column row-gap-2">
                <div class="d-flex flex-row column-gap-2 align-items-center">
                    <label class="w-25" for="name-contract-type">Tên hợp đồng</label>
                    <input type="text" name="name-contract-type">
                </div>
                <div class="d-flex flex-row column-gap-2 align-items-center">
                    <label class="w-25" for="description-contract-type">Mô tả</label>
                    <textarea rows="5" id="description-contract-type" name="description-contract-type"></textarea>
                </div>
                <div class="d-flex flex-row column-gap-2 align-items-center">
                    <label class="w-25" for="template-contract-type">Mẫu</label>
                    <input type="text" id="template-contract-type" name="template-contract-type">
                </div>
                <div class="d-flex flex-row column-gap-2 align-items-center">
                    <label class="w-25" for="templateID-contract-type">ID</label>
                    <input type="text" id="templateID-contract-type" name="templateID-contract-type">
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
</body>
<?php
    // Đóng kết nối
    $conn->close();
?>

</html>