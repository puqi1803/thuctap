<?php
include '../partical/db_connect.php';
include '../includes/functions.php';
include '../includes/delete.php';

include '../header-blank.php';
$error_message = '';
//Truy van loc
$rq_location = $_GET['location-tour'] ?? '';
$rq_budget = $_GET['budget'] ?? '';
$rq_date = $_GET['date'] ?? '';
$sort =  $_GET['sort'] ?? '';
$order_by = 'ORDER BY `created-at` DESC';
$status_tour = $_GET['status-tour'] ?? '';

//Xay dung truy van
$sql = "SELECT * FROM tour WHERE 1=1";

$sql_location = "SELECT * FROM `location` ORDER BY `id-area-location` ASC;";
$result_location = $conn->query($sql_location);
$location_by_area=[];
if ($result_location && $result_location->num_rows > 0) {
    while ($row_location = $result_location->fetch_assoc()) {
        $locations[] = $row_location;
    }
}
foreach ($locations as $location) {
    if ($location['name-location'] == $rq_location) {
        $id_location = $location['id-location'];
        break;
    }
}   
if ($rq_location) {
    $sql .= " AND `id-location-tour` = '" . $id_location . "'";
}

$sql_area_location = "SELECT * FROM `area-location`";
$result_area_location = [];
$result_area_location = $conn->query($sql_area_location);
if ($result_area_location && $result_area_location->num_rows > 0) {
    while ($row_area_location = $result_area_location->fetch_assoc()) {
        $area_locations[] = $row_area_location;
    }
}

/*if ($rq_start_date) {
    $sql .= " AND `date-tour` = '" . $conn->real_escape_string($rq_start_date) . '";
}*/
if ($rq_budget) {
    switch ($rq_budget) {
        case 'duoi-5-trieu':
            $sql .= " AND `price-tour` < 5000000";
            break;
        case '5-10-trieu':
            $sql .= " AND  `price-tour` >= 5000000 AND `price-tour` < 10000000";
            break;
        case '10-20-trieu':
            $sql .= " AND  `price-tour` >= 10000000 AND `price-tour` < 20000000";
            break;
        case 'tren-20-trieu':
            $sql .= " AND  `price-tour` >= 20000000";
            break;
    }
}
if ($sort) {
    switch($sort) {
        case 'gia-thap-den-cao':
            $order_by = 'ORDER BY `price-tour` ASC';
            break;
        case 'gia-cao-den-thap':
            $order_by = 'ORDER BY `price-tour` DESC';
            break;
    }
}

$sql_status = "SELECT * FROM `status`;";
$result_status = [];
$result_status = $conn->query($sql_status);
if ($result_status && $result_status->num_rows > 0) {
    while ($row_status = $result_status->fetch_assoc()) {
        $status_list[] = $row_status; 
    }
}
foreach ($status_list as $status) {
    if ($status['name-status'] == $status_tour) {
        $id_status_tour = $status['id-status'];
        break;
    }
}   
if (!empty($id_status_tour)) {
    $sql .= " AND `id-status-tour` = " . $id_status_tour;
}

//Phan trang
$results_per_page = 10;

$page = isset($_GET['tour-page']) ? (int)$_GET['tour-page'] : 1;
if ($page < 1) {
    $page = 1;
}
$start_form = ($page - 1) * $results_per_page;

$sql_count = "SELECT COUNT(*) AS total FROM tour WHERE 1=1";
if ($rq_location) {
    $sql_count .= " AND `id-location-tour` = '" . $id_location . "'";
}
/*if ($rq_start_date) {
    $sql .= " AND `date-tour` = '" . $conn->real_escape_string($rq_start_date) . "'";
}*/
if ($rq_budget) {
    switch ($rq_budget) {
        case 'duoi-5-trieu':
            $sql_count .= " AND `price-tour` < 5000000";
            break;
        case '5-10-trieu':
            $sql_count .= " AND  `price-tour` >= 5000000 AND `price-tour` < 10000000";
            break;
        case '10-20-trieu':
            $sql_count .= " AND  `price-tour` >= 10000000 AND `price-tour` < 20000000";
            break;
        case 'tren-20-trieu':
            $sql_count .= " AND  `price-tour` >= 20000000";
            break;
    }
}
if (!empty($id_status_tour)) {
    $sql_count .= " AND `id-status-tour` = " . $id_status_tour;
}
$result_count = $conn->query($sql_count);
$row_count = $result_count->fetch_assoc();
$total_results = $row_count['total'];
$total_pages = ceil($total_results/$results_per_page);

$sql .= " " . $order_by . " LIMIT $start_form, $results_per_page";
$results = [];
$result = $conn->query($sql);
if ($result) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $results[] = $row;
        }
    } else {
        $error_message = 'Không tìm thấy kết quả phù hợp';
    }
} else {
    $error_message = 'Lỗi: ' . $conn->error . ''; 
}


?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Quản lý tour</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="description" content=""/>
    <meta http-equiv="refresh" content="900">
</head>

<body>           
    <?php  
        include '../header-blank.php';
    ?>
    <main class="admin-tour">
        <h3 class="title-page">Tour</h3>
        <!--- Function delete, add new --->
        <div class="row justify-content-between mt-4">
            <div class="col-5 d-flex flex-row column-gap-2 align-items-center">
                <button class="button-light-background p-2 px-4" onclick="window.open('admin-new-tour', '_blank')">Thêm mới</button>
                <button class="button-light-background p-2 px-4" type="submit" form="delete-form" onclick="return confirm('Bạn có chắc chắn muốn xóa không?');">Xóa</button>
            </div>
            <!---
            <div class="col-3">
                <form class="d-flex flex-row column-gap-2 align-items-center justify-content-end">
                    <input class="col p-2 border-round" type="text">
                    <button class="col-2 button-light-background p-2" type="submit">Tìm</button>
                
            </div></form>--->
        </div>
        <!--- Filter --->
        <div class="row mt-4">
                <form method="GET" action="admin.php" class="d-flex flex-row column-gap-2 align-items-center">
                    <input type="hidden" name="page" value="admin-tour">
                    <input type="hidden" name="location-tour" value="<?php echo htmlspecialchars($rq_location); ?>">
                    <input type="hidden" name="date-tour" value="<?php echo htmlspecialchars($rq_date); ?>">
                    <input type="hidden" name="budget" value="<?php echo htmlspecialchars($rq_budget); ?>">
                    <?php
                    $locations_by_area_name = [];
                    foreach ($locations as $location) {
                        $id_area_location = $location['id-area-location'];
                        $name_location = htmlspecialchars($location['name-location']);
                        $area_name = '';

                        foreach ($area_locations as $area_location_item) {
                            if ($area_location_item['id-area-location'] == $id_area_location) {
                                $area_name = $area_location_item['name-area-location'];
                                break;
                            }
                        }
                        if (!isset($locations_by_area_name[$area_name])) {
                            $locations_by_area_name[$area_name] = [];
                        }
                        $locations_by_area_name[$area_name][] = $name_location;
                    }
                    echo '<div class="col">';
                        echo '<select id="location-tour" name="location-tour" class="col p-2 border-round">';
                        echo '<option value="">Lọc theo địa điểm</option>';
                        foreach ($locations_by_area_name as $area_name => $locations_list) {
                            echo '<optgroup label="' . htmlspecialchars($area_name) . '">';
                            foreach ($locations_list as $location_name) {
                                $selected_location = ($location_name === $rq_location) ? 'selected' : '';
                                echo '<option value="' . htmlspecialchars($location_name) . '" ' . $selected_location . '>'
                                    . htmlspecialchars($location_name) . '</option>';
                            }
                            echo '</optgroup>';
                        }
                        echo '</select>';
                    echo '</div>';
                    ?>
                    <!---
                    <div class="col">
                        <input class="col p-2 border-round" type="date" value="" id="date-tour" name="date-tour"></input>
                    </div> --->
                    <div class="col">
                        <select class="p-2 border-round" id="budget" name="budget">
                            <option value="" >Ngân sách chuyến đi</option>
                            <option value="duoi-5-trieu" <?php echo($rq_budget === 'duoi-5-trieu') ? 'selected' : '';?>>Dưới 5 triệu</option>
                            <option value="5-10-trieu" <?php echo($rq_budget === '5-10-trieu') ? 'selected' : '';?>>Từ 5 triệu - 10 triệu</option>
                            <option value="10-20-trieu" <?php echo($rq_budget === '10-20-trieu') ? 'selected' : '';?>>Từ 10 triệu - 20 triệu</option>
                            <option value="tren-20-trieu" <?php echo($rq_budget === 'tren-20-trieu') ? 'selected' : '';?>>Trên 20 triệu</option>
                        </select>
                    </div>
                    <?php
                    echo '<div class="col">';
                        echo '<select id="status-tour" name="status-tour" class="px-2 py-2 border-accent">';
                        echo '<option value="">Trạng thái</option>';
                        foreach ($status_list as $status) {
                            $selected_status = ($status['name-status']===$status_tour) ? 'selected' : '';
                            echo '<option value="' . htmlspecialchars($status['name-status']) . '"' . $selected_status . '>'
                                . htmlspecialchars($status['name-status'])
                                . '</option>';
                            }
                        echo '</select>';
                    echo '</div>';
                    ?>
                    <div class="col">
                    <select id="sort" name="sort" class="px-2 py-2 border-accent">
                        <option value="">Sắp xếp</option>
                        <option value="gia-thap-den-cao"
                            <?php echo (isset($_GET['sort']) && $_GET['sort']==='gia-thap-den-cao') ? 'selected' : ''; ?>>Giá từ thấp đến cao
                        </option>
                        <option value="gia-cao-den-thap"
                            <?php echo (isset($_GET['sort']) && $_GET['sort']==='gia-cao-den-thap') ? 'selected' : ''; ?>>Giá từ cao đến thấp
                        </option>    
                    </select>
                    </div>
                    <button class="px-4 button-light-background p-2" type="submit">Lọc</button>
                </form>
            </div>
        <!---</div>--->
        <?php
        //Phan trang
        echo '<div class="d-flex flex-row mt-5 column-gap-5 justify-content-between">';
            echo '<p class="accent">Tổng: ' . $total_results . ' mục</p>';
            echo '<div class="pagination-admin d-flex flex-row column-gap-4">';
                if ($page > 1) {
                    echo '<a href="?page=admin-tour&tour-page=' . ($page - 1) . '">«</a>';
                }/* else {
                    echo '<span class="disable">«</span>';
                }*/
                for ($i = 1; $i <= $total_pages; $i++) {
                    if ($total_pages == 1) {
                        echo '<span>' . $total_pages . '</span>';
                    } else if ($i == $page) {
                        echo '<span class="accent">' . $i . '</span>';
                    } else {
                        echo '<a  class="number" href="?page=admin-tour&tour-page=' . $i . '">' . $i . '</a>';
                    }
                }
                if ($page < $total_pages) {
                    echo '<a href="?page=admin-tour&tour-page=' . ($page + 1) . '">»</a>';
                }/* else {
                    echo '<span class="disable">»</span>';
                }*/
            echo '</div>';
        echo '</div>';
        ?>
        <hr>
        <form id="delete-form" method="POST">
        <input type="hidden" name="deleted-tour" value="1">
        <div class="mt-2">
            <table class="table table-striped">
                <thead>
                    <tr class="text-center text-uppercase">
                        <th scope="col" class="col"><input type="checkbox" id="select-all"></th>
                        <th scope="col" class="col-3">Tên tour</th>
                        <th scope="col" class="col">Mã tour</th>
                        <th scope="col" class="col">Địa điểm</th>
                        <th scope="col" class="col">Giá</th>
                        <th scope="col" class="col">Ngày khởi hành</th>
                        <th scope="col" class="col">Khởi hành</th>
                        <th scope="col" class="col">Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($error_message)) {
                        echo '<tr><td colspan="8">' . htmlspecialchars($error_message) . '</td></tr>';
                    }
                    foreach ($results as $result) {
                        $id_status_tour = $result['id-status-tour'];
                        echo '<tr class="text-center">';
                            echo '<td><input type="checkbox" name="tours[]" value="' . htmlspecialchars($result['id-tour']) . '" class="tour-select"></td>';
                            if (!empty($result['title-tour'])) {
                                echo '<td><div class="text-start">';
                                    $title =  htmlspecialchars($result['title-tour']);
                                    $shortTitle = truncateTitle($title);
                                    echo '<a class="accent link" href="admin-edit-tour?id-tour=' .htmlspecialchars($result['id-tour']) . '">' . $shortTitle . '  </a>';
                                    echo '<a href="../single-tour?id-tour=' . htmlspecialchars($result['id-tour']) . '" target="_blank"><i class="icon fa-solid fa-eye"></i></a>';
                                echo '</div>';
                                echo '</td>';
                            } else {
                                echo '<td></td>';
                            }                       
                            echo '<td>' . (!empty($result['id-tour']) ? htmlspecialchars($result['id-tour']) : '') . '</td>';
                            if (!empty($result['id-location-tour'])) {
                                foreach ($locations as $location) {
                                    if ($location['id-location'] == $result['id-location-tour']) {
                                        echo '<td>' . htmlspecialchars($location['name-location']) . '</td>';
                                        break;
                                    } 
                                }  
                            } else echo '<td></td>';
                            echo '<td>' . (!empty($result['price-tour']) ? number_format($result['price-tour']) : '') . '</td>';
                            echo '<td>' . (!empty($result['date-tour']) ? formatDate($result['date-tour']) : '') . '</td>';
                            echo '<td class="text-start">' . (!empty($result['starting-gate']) ? htmlspecialchars($result['starting-gate']) : '') . '</td>';
                            if (!empty($id_status_tour)) {
                                foreach ($status_list as $status) {
                                    if ($status['id-status'] == $id_status_tour) {
                                        echo '<td>' . htmlspecialchars($status['name-status']) . '</td>';
                                        break;
                                    }
                                }
                            }  else echo '<td></td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
        </form>
        <hr>
        <?php
        //Phan trang
        echo '<div class="d-flex flex-row column-gap-5 justify-content-between">';
            echo '<p class="accent">Tổng: ' . $total_results . ' mục</p>';
            echo '<div class="pagination-admin d-flex flex-row column-gap-4">';
                if ($page > 1) {
                    echo '<a href="?page=admin-tour&tour-page= ' . ($page - 1) . '">«</a>';
                }
                for ($i = 1; $i <= $total_pages; $i++) {
                    if ($total_pages == 1) {
                        echo '<p>' . $total_pages . '</p>';
                    } else if ($i == $page) {
                        echo '<p class="accent">' . $i . '</p>';
                    } else {
                        echo '<a  class="number" href="?page=admin-tour&tour-page= ' . $i . '">' . $i . '</a>';
                    }
                }
                if ($page < $total_pages) {
                    echo '<a href="?page=admin-tour&tour-page= ' . ($page + 1) . '">»</a>';
                }
            echo '</div>';
        echo '</div>';
        ?>
    </main>
    <script>
        document.getElementById('select-all').onclick = function() {
            let checkboxes = document.querySelectorAll('.tour-select');
            for (let checkbox of checkboxes) {
                checkbox.checked = this.checked;
            }
        }
    </script>    
</body>
<?php
    // Đóng kết nối
    $conn->close();
?>

</html>