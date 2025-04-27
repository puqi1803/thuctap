<?php
include '../includes/check-login.php';
include '../partical/db_connect.php';
include '../includes/functions.php';


if (isset($_GET['id-location'])) {
    $id_location = $_GET['id-location'];
    $sql = "SELECT * FROM `location` WHERE `id-location` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_location);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result && $result->num_rows > 0 ) {
        $location = $result->fetch_assoc();
        }
    } else {
        echo 'Bài viết không tồn tại.';
        exit;
    }

    $sql_area_location = "SELECT * FROM `area-location`;";
    $result_area_location = $conn->query($sql_area_location);
    $area_locations = [];
    if ($result_area_location && $result_area_location->num_rows > 0) {
        while ($row = $result_area_location->fetch_assoc()) {
            $area_locations[] = $row;
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name_location = $_POST['name-location'];
        $slug_location = $_POST['slug-location'];
        $id_area_location = $_POST['area-location'];
        $slug = createSlugLocation($slug_location);
        $description_location = $_POST['description-location'];

        $sql = "UPDATE `location` SET 
            `name-location` = ?, 
            `slug-location` = ?, 
            `id-area-location` = ?, 
            `description-location` = ?
            WHERE `id-location` = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssisi",
            $name_location,
            $slug,
            $id_area_location,
            $description_location,
            $id_location
            );

        if ($stmt->execute()) {
            header("Location: admin-edit-location?id-location=$id_location");
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
    <title>Sửa địa điểm</title>
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
                    {window.location.href='admin?page=admin-location';}">
                    <i class="icon fa-solid fa-arrow-left"></i>&nbsp;&nbsp;Trở về
                </button>
            </div>
            <div class="d-flex flex-row column-gap-4"> 
                <a href="/nienluan.com/" target="_blank"><i class="icon fa-solid fa-house"></i>  Trang chủ</a>
            </div>
        </div>
    </div>
    <main class="container admin-edit-location" style="max-width: 700px; padding: 40px 0px">
    <form method="POST" enctype="multipart/form-data">
        <div class="row row-gap-2">
            <div>
                <label for="name-location">Tên địa điểm</label>
                <input type="text" id="name-location" name="name-location"
                value="<?php echo htmlspecialchars($location['name-location']) ?>"> 
            </div>
            <div>
                <label for="slug-location">Đường dẫn</label>
                <input type="text" id="slug-location" name="slug-location"
                value="<?php echo htmlspecialchars($location['slug-location']) ?>"> 
            </div>
            <div>
                <label for="slug-location">Khu vực</label>
                <select id="area-location" name="area-location" required>
                    <?php
                    foreach ($area_locations as $area_location) {
                        $selected_area_location = ($area_location['id-area-location'] == $id_area_location) ? ' selected' : '';
                        echo '<option value="' . htmlspecialchars($area_location['id-area-location']) . $selected_area_location . '">'
                            . htmlspecialchars($area_location['name-area-location'])
                            . '</option>';
                        }
                    ?>
                </select>
            </div>
            <div>
                <label for="description-location">Mô tả</label>
                <textarea id="description-location" name="description-location" rows="5"><?php echo htmlspecialchars($location['description-location']); ?></textarea>
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

