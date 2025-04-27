<?php
include '../partical/db_connect.php';
include '../includes/functions.php';
include '../includes/delete.php';

$sql_location = "SELECT * FROM `location` ORDER BY `id-area-location` ASC;";
$result_location = $conn->query($sql_location);
$locations=[];
if ($result_location && $result_location->num_rows > 0) {
    while ($row_location = $result_location->fetch_assoc()) {
        $locations[] = $row_location;
    }
}

$sql_area_location = "SELECT * FROM `area-location`;";
$result_area_location = $conn->query($sql_area_location);
$area_locations = [];
if ($result_area_location && $result_area_location->num_rows > 0) {
    while ($row = $result_area_location->fetch_assoc()) {
        $area_locations[] = $row;
    }
}

$rq_area_location = isset($_GET['rq-area-location']) ? (int)$_GET['rq-area-location'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name_location = $_POST['name-location'];
    $slug_location = $_POST['slug-location'];
    $id_area_location = $_POST['area-location'];
    $slug = createSlugLocation($slug_location);
    $description_location = $_POST['description-location'];

$sql = "INSERT INTO `location` (
    `name-location`,
    `slug-location`,
    `id-area-location`,
    `description-location`
    ) VALUES (?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssis",
    $name_location,
    $slug,
    $id_area_location,
    $description_location
    );

if ($stmt->execute()) {
    $id_category_post = $conn->insert_id;
    header("Location: ?page=admin-location");
    exit();
} else {
    echo 'Lỗi: ' . $stmt->error;
}
$stmt->close();
}

//Phan trang
$results_per_page = 8;
$page = isset($_GET['location-page']) ? (int)$_GET['location-page'] : 1;
if ($page < 1) {
    $page = 1;
}
$start_form = ($page - 1) * $results_per_page;

$sql_count = "SELECT COUNT(*) AS total FROM `location` WHERE 1=1";
if ($rq_area_location > 0) {
    $sql_count .= " AND `id-area-location` = $rq_area_location";
}
$result_count = $conn->query($sql_count);
$row_count = $result_count->fetch_assoc();
$total_results = $row_count['total'];
$total_pages = ceil($total_results / $results_per_page);

// Lấy dữ liệu hiển thị
$sql = "SELECT * FROM `location` WHERE 1=1";
if ($rq_area_location > 0) {
    $sql .= " AND `id-area-location` = $rq_area_location";
}
$sql .= " ORDER BY `id-area-location` ASC, `name-location` ASC";
$sql .= " LIMIT $start_form, $results_per_page";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Quản lý địa điểm</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="description" content=""/>
    <meta http-equiv="refresh" content="900">
</head>

<body>
    <?php
        include '../header-blank.php';
    ?>
    <main class="location">
        <h3 class="title-page">Địa điểm</h3>
        <!---
        <div class="col justify-content-end">
            <form class="d-flex flex-row column-gap-2 align-items-center justify-content-end">
                <input class="p-2 border-round w-25" type="text">
                <button class="button-light-background p-2" type="submit">Tìm</button>
            </form>
        </div> --->
        <hr>
        <div class="d-flex flex-row column-gap-6">
            <div class="col-3">
            <form method="POST" enctype="multipart/form-data">
                <div class="row row-gap-2">
                    <div>
                        <label for="name-category-post">Tên địa điểm</label>
                        <input type="text" id="name-location" name="name-location" required>
                    </div>
                    <div>
                        <label for="slug-location">Khu vực</label>
                        <select id="area-location" name="area-location" required>
                            <option value="" disabled selected>Chọn khu vực</option>
                        <?php
                        foreach ($area_locations as $area_location) {
                            echo '<option value="' . htmlspecialchars($area_location['id-area-location']) . '">'
                                . htmlspecialchars($area_location['name-area-location'])
                                . '</option>';
                        }
                        ?>
                        </select>
                    </div>
                    <div>
                        <label for="slug--location">Đường dẫn</label>
                        <input type="text" id="slug-location" name="slug-location">
                    </div>
                    <div>
                        <label for="description-location">Mô tả</label>
                        <textarea type="text" rows="5" id="description-location" name="description-location"></textarea>
                    </div>  
                    <div class="d-flex justify-content-end">
                        <button class="button-primary px-3 py-2" type="submit" name="submit">
                            <i class="icon fa-solid fa-floppy-disk"></i>&nbsp;&nbsp;Lưu
                        </button>
                    </div>
                </div>
            </form>
            </div>

            <!--- Category --->
            <div class="col d-flex flex-column row-gap-2">
                <!--- Filter --->
                <div class="d-flex flex-row align-items-center">
                    <!---Action--->
                    <div class="col d-flex flex-row column-gap-4">
                        <form class="flex d-flex column-gap-2">
                            <select class="col" id="action-location" name="action-location">
                                <option value="" disabled selected>Thao tác</option>
                                <option value="delete">Xóa</option>
                            </select>
                            <button class="button-light-background p-2" type="submit" onclick="return confirmAction();">Thực hiện</button>
                        </form>
                        <form method="GET" class="flex d-flex column-gap-2">
                            <input type="hidden" name="page" value="admin-location">
                            <select class="col" id="rq-area-location" name="rq-area-location">
                                <option value="0">Khu vực</option>
                                <?php
                                foreach ($area_locations as $area_location) {
                                    $selected = ($rq_area_location == $area_location['id-area-location']) ? 'selected' : '';
                                    echo '<option value="' . htmlspecialchars($area_location['id-area-location']) . '" ' . $selected . '>'
                                        . htmlspecialchars($area_location['name-area-location'])
                                        . '</option>';
                                }
                                ?>
                            </select>
                            <button class="button-light-background p-2" type="submit">Lọc</button>
                        </form>
                    </div>
                    <!---Phan trang --->
                    <div class="col-2">
                        <?php
                        echo '<div class="pagination-admin d-flex flex-row column-gap-2 justify-content-end">';
                        $filter_query = ($rq_area_location > 0) ? '&filter-area-location=' . $rq_area_location : '';
                        if ($page > 1) {
                            echo '<a href="?page=admin-location' .$filter_query . '&location-page=' . ($page - 1) . '">«</a>';
                        }
                        for ($i = 1; $i <= $total_pages; $i++) {
                            if ($total_pages == 1) {
                                echo '';
                            }
                            else if ($i == $page) {
                                    echo '<span class="accent">' . $i .'</span>';
                            } else {
                                echo '<a class="number" href="?page=admin-location' .$filter_query . '&location-page=' . $i . '">' . $i . '</a>';
                            }
                        }
                        if ($page < $total_pages) {
                            echo '<a href="?page=admin-location' .$filter_query . '&location-page=' . ($page + 1) . '">»</a>';
                        }
                        echo '</div>';  
                        ?>
                    </div>
                </div>

                <!--- Show Category --->    
                <div>
                    <form id="delete-form" method="POST">
                        <input type="hidden" name="deleted-location" value="1">
                        <div class="mt-2">
                            <table class="table table-striped">
                                <thead>
                                    <tr class="text-center text-uppercase">
                                        <th scope="col" class="col"><input type="checkbox" id="select-all"></th>
                                        <th scope="col" class="col-3">Tên địa điểm</th>
                                        <th scope="col" class="col">Đường dẫn</th>
                                        <th scope="col" class="col-3">Khu vực</th>
                                        <th scope="col" class="col-3">Mô tả</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                if ($result) {
                                    if ($result->num_rows > 0) {
                                        while ($location = $result->fetch_assoc()) {
                                            echo '<tr class="text-center">';
                                                echo '<td><input type="checkbox" name="locations[]" value="'
                                                . htmlspecialchars($location['id-location']) . '" class="location-select"></td>';
                                                if (!empty($location['name-location'])) {
                                                    echo '<td><a class="accent link" href="admin-edit-location?id-location=' . htmlspecialchars($location['id-location']) . '">'
                                                        . htmlspecialchars($location['name-location']) . '  </a></td>';
                                                } else {
                                                    echo '<td></td>';
                                                }
                                                echo '<td>' . (!empty($location['slug-location']) ? htmlspecialchars($location['slug-location']) : '') . '</td>'; 
                                                $sql_area_from_id = "SELECT `name-area-location` FROM `area-location` WHERE `id-area-location`=" . $location['id-area-location'];
                                                $result_area_from_id = $conn->query($sql_area_from_id);
                                                if ($result_area_from_id && $result_area_from_id->num_rows > 0) {
                                                    while ($name_area = $result_area_from_id->fetch_assoc()) {
                                                        echo '<td>' . (!empty($location['id-area-location']) ? htmlspecialchars($name_area['name-area-location']) : '') . '</td>';
                                                    }
                                                }
                                                $description = htmlspecialchars($location['description-location']);
                                                $short_description = truncateExpertShort($description);
                                                echo '<td>' . (!empty($location['description-location']) ? htmlspecialchars($short_description) : '') . '</td>';
                                            echo '</tr>';
                                            }
                                        } else {
                                            echo '<tr><td colspan="6">Không tìm thấy kết quả phù hợp</td></tr>';
                                        }
                                    } else {
                                        echo '<tr><td colspan="6">Lỗi: ' . $conn->error . '</td></tr>'; 
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>

                <!--- Phan trang --->
                <div>
                <?php 
                echo '<div class="d-flex flex-row column-gap-5 justify-content-between">';
                    echo '<p class="accent">Tổng: ' . $total_results . ' mục</p>';
                    echo '<div class="pagination-admin d-flex flex-row column-gap-2">';
                        if ($page > 1) {
                            echo '<a href="?page=admin-location' .$filter_query . '&location-page=' . ($page - 1) . '">«</a>';
                        }
                        for ($i = 1; $i <= $total_pages; $i++) {
                            if ($total_pages == 1) {
                                echo '';
                            }
                            else if ($i == $page) {
                                echo '<span class="accent">' . $i .'</span>';
                            } else {
                                echo '<a class="number" href="?page=admin-location' .$filter_query . '&location-page=' . $i . '">' . $i . '</a>';
                            }
                        }
                        if ($page < $total_pages) {
                            echo '<a href="?page=admin-location' .$filter_query . '&location-page=' . ($page + 1) . '">»</a>';
                        }
                    echo '</div>';
                echo '</div>';  
                ?>
                </div>
            </div>
        </div>
    </main>
    <script>
        document.getElementById('select-all').onclick = function() {
            var checkboxes = document.getElementsByClassName('location-select');
            for (var checkbox of checkboxes) {
                checkbox.checked = this.checked;
            }
        };
        function confirmAction() {
            const action = document.getElementById("action-location").value;
            if (action === "delete") {
                return confirm("Bạn có chắc chắn muốn xóa không?");
            }
            return false;
        }
    </script>  
</body>
<?php
    // Đóng kết nối
    $conn->close();
?>

</html>