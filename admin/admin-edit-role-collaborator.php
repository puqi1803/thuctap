<?php
include '../includes/check-login.php';
include '../partical/db_connect.php';
include '../includes/functions.php';

if (isset($_GET['id-role-collaborator'])) {
    $id_role_collaborator = $_GET['id-role-collaborator'];
    $sql = "SELECT * FROM `role-collaborator` WHERE `id-role-collaborator` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_role_collaborator);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0 ) {
        $role_collaborator = $result->fetch_assoc();
        }
    } else {
        echo 'Lỗi, không tồn tại.';
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name_role_collaborator = $_POST['name-role-collaborator'];
        $description_role_collaborator = $_POST['description-role-collaborator'];

        $sql = "UPDATE `role-collaborator` SET
            `name-role-collaborator` = ?,
            `description-role-collaborator` = ?
            WHERE `id-role-collaborator` = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi",
            $name_role_collaborator,
            $description_role_collaborator,
            $id_role_collaborator
            );

        if ($stmt->execute()) {
            header("Location: admin-edit-role-collaborator?id-role-collaborator=$id_role_collaborator");
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
                    {window.location.href='admin?page=admin-role-collaborator';}">
                    <i class="icon fa-solid fa-arrow-left"></i>&nbsp;&nbsp;Trở về
                </button>
            </div>
            <div class="d-flex flex-row column-gap-4"> 
            <a href="/nienluan.com/"><i class="icon fa-solid fa-house"></i>  Trang chủ</a>
        </div>
        </div>
    </div>
    <main class="container admin-edit-role-collaborator" style="max-width: 700px; padding: 40px 0px">
    <form method="POST" enctype="multipart/form-data">
        <div class="row row-gap-2">
            <div>
                <label for="name-role-collaborator">Tên chuyên mục</label>
                <input type="text" id="name-role-collaborator" name="name-role-collaborator"
                value="<?php echo htmlspecialchars($role_collaborator['name-role-collaborator']) ?>"> 
            </div>
            <div>
                <label for="description-role-collaborator">Mô tả</label>
                <textarea id="description-role-collaborator" name="description-role-collaborator" rows="5"><?php echo htmlspecialchars($role_collaborator['description-role-collaborator']); ?></textarea>
                </textarea>
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

