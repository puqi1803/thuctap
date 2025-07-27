<?php
include '../includes/check-login.php';
include '../partical/db_connect.php';
include '../includes/functions.php';

if (isset($_GET['id-contract-type'])) {
    $id_contract_type = $_GET['id-contract-type'];
    $sql = "SELECT * FROM `contract-type` WHERE `id-contract-type` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_contract_type);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0 ) {
        $contract_type = $result->fetch_assoc();
        }
    } else {
        echo 'Lỗi, không tồn tại.';
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name_contract_type = $_POST['name-contract-type'];
        $template_contract_type = $_POST['template-contract-type'];
        $description_contract_type = $_POST['description-contract-type'];

        $sql = "UPDATE `contract-type` SET
            `name-contract-type` = ?,
            `description-contract-type` = ?,
            `template-contract-type` = ?
            WHERE `id-contract-type` = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi",
            $name_contract_type,
            $description_contract_type,
            $template_contract_type,
            $id_contract_type
            );

        if ($stmt->execute()) {
            header("Location: admin-edit-contract-type?id-contract-type=$id_contract_type");
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
    <title>Sửa chuyên mục</title>
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
                    {window.location.href='admin?page=admin-contract-type';}">
                    <i class="icon fa-solid fa-arrow-left"></i>&nbsp;&nbsp;Trở về
                </button>
            </div>
            <div class="d-flex flex-row column-gap-4"> 
            <a href="/nienluan.com/"><i class="icon fa-solid fa-house"></i>  Trang chủ</a>
        </div>
        </div>
    </div>
    <main class="container admin-edit-contract-type" style="max-width: 700px; padding: 40px 0px">
    <form method="POST" enctype="multipart/form-data">
        <div class="row row-gap-2">
            <div>
                <label for="name-contract-type">Tên hợp đồng</label>
                <input type="text" id="name-contract-type" name="name-contract-type"
                value="<?php echo htmlspecialchars($contract_type['name-contract-type']) ?>"> 
            </div>
            <div>
                <label for="description-contract-type">Mô tả</label>
                <textarea id="description-contract-type" name="description-contract-type" rows="5"><?php echo htmlspecialchars($contract_type['description-contract-type']); ?></textarea>
            </div>
            <div>
                <label class="w-25" for="template-contract_type">Mẫu</label>
                <input type="template" id="template-contract_type" name="template-contract-type"
                value="<?php echo htmlspecialchars($contract_type['template-contract-type']) ?>"> 
            </div>
            <div class="d-flex justify-content-end">
                <button class="button-primary px-3 py-2" type="submit" name="submit">
                    <i class="icon fa-solid fa-floppy-disk"></i>&nbsp;&nbsp;Lưu
                </button>
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

