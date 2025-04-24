<?php
include '../partical/db_connect.php';
include '../includes/functions.php';
include '../includes/delete.php';
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

        //Truy van loc
        $rq_location = $_GET['location-tour'] ?? '';
        $rq_budget = $_GET['budget'] ?? '';
        $rq_date = $_GET['date'] ?? '';
        $sort =  $_GET['sort'] ?? '';
        $order_by = 'ORDER BY `created-at` DESC';
        $status_tour = $_GET['status-tour'] ?? '';

        //Xay dung truy van
        $sql = "SELECT * FROM tour WHERE 1=1";
        if ($rq_location) {
            $sql .= " AND `location-tour` = '" . $conn->real_escape_string($rq_location) . "'";
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
        if ($status_tour) {
            $sql_id_status_tour = "SELECT `id-status` FROM `status` WHERE `name-status` = '" . $conn->real_escape_string($status_tour) . "'";
            $result_id_status_tour = $conn->query($sql_id_status_tour);
            if ($result_id_status_tour && $result_id_status_tour->num_rows > 0) {
                $row = $result_id_status_tour->fetch_assoc();
                $id_status_tour = $row['id-status'];
            }
            $sql .= " AND `id-status-tour` = $id_status_tour";
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
            $sql_count .= " AND `location-tour` = '" . $conn->real_escape_string($rq_location) . "'";
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
        if ($status_tour) {
            $sql_count .= " AND `id-status-tour` = '" . $conn->real_escape_string($id_status_tour) . "'";
        }
        $result_count = $conn->query($sql_count);
        $row_count = $result_count->fetch_assoc();
        $total_results = $row_count['total'];
        $total_pages = ceil($total_results/$results_per_page);

        $sql .= " " . $order_by . " LIMIT $start_form, $results_per_page";
        $result = $conn->query($sql);

        $sql_location = "SELECT `name-location`, `area-location` FROM `location` ORDER BY `area-location` ASC;";
        $result_location = $conn->query($sql_location);
    ?>            
    <main class="admin-tour">
        <h3 class="title-page">Tour</h3>
        <!--- Function delete, add new --->
        <div class="row justify-content-between mt-4">
            <div class="col-5 d-flex flex-row column-gap-2 align-items-center">
                <button class="button-light-background p-2 px-4" onclick="window.open('admin-new-tour', '_blank')">Thêm mới</button>
                <button class="button-light-background p-2 px-4" type="submit" form="delete-form" onclick="return confirm('Bạn có chắc chắn muốn xóa không?');">Xóa</button>
            </div>
            <div class="col-5 d-flex flex-row column-gap-2 align-items-center justify-content-end">
                <form method="POST" action="../admin-tour.php">
                    <input class="p-2 border-round" type="text">
                    <button class="button-light-background p-2" type="submit">Tìm</button>
                </form>
            </div>
        </div>
        <!--- Filter --->
        <div class="row justify-content-between mt-4">
            <!--- <div class="d-flex flex-row column-gap-2 align-items-center">--->
                <form method="GET" action="admin.php">
                    <input type="hidden" name="page" value="admin-tour">
                    <input type="hidden" name="location-tour" value="<?php echo htmlspecialchars($rq_location); ?>">
                    <input type="hidden" name="date-tour" value="<?php echo htmlspecialchars($rq_date); ?>">
                    <input type="hidden" name="budget" value="<?php echo htmlspecialchars($rq_budget); ?>">
                    <?php
                    $location_by_area=[];
                    if ($result_location) {
                        while ($location_tour=$result_location->fetch_assoc()) {
                             $area_location = $location_tour['area-location'];
                            $name_location = htmlspecialchars($location_tour['name-location']);
                            if (!isset($location_by_area[$area_location])) {
                                $location_by_area[$area_location]=[];
                            }
                            $location_by_area[$area_location][] = $name_location;
                        }
                    }
                    echo '<select id="location-tour" name="location-tour" class="col p-2 border-round">';
                        echo '<option value="">Lọc theo địa điểm</option>';
                        foreach ($location_by_area as $area_location => $locations) {
                            echo '<optgroup label="' . htmlspecialchars($area_location) . '">';
                            foreach ($locations as $location ) {
                                $selected_location = ($location === $rq_location) ? 'selected' : '';
                                echo '<option value="' . htmlspecialchars($location) . '" ' . $selected_location . '>' . htmlspecialchars($location) . '</option>';
                            }
                            echo '</optgroup>';
                        }
                    echo '</select>';
                    ?>
                    <input class="col p-2 border-round" type="date" value="" id="date-tour" name="date-tour"></input>
                    <select class="col p-2 border-round" id="budget" name="budget">
                        <option value="">Ngân sách chuyến đi</option>
                        <option value="duoi-5-trieu" <?php echo($rq_budget === 'duoi-5-trieu') ? 'selected' : '';?>>Dưới 5 triệu</option>
                        <option value="5-10-trieu" <?php echo($rq_budget === '5-10-trieu') ? 'selected' : '';?>>Từ 5 triệu - 10 triệu</option>
                        <option value="10-20-trieu" <?php echo($rq_budget === '10-20-trieu') ? 'selected' : '';?>>Từ 10 triệu - 20 triệu</option>
                        <option value="tren-20-trieu" <?php echo($rq_budget === 'tren-20-trieu') ? 'selected' : '';?>>Trên 20 triệu</option>
                    </select>
                    <?php
                    echo '<select id="status-tour" name="status-tour" class="px-2 py-2 border-accent">';
                    echo '<option value="">Trạng thái</option>';
                    $sql_status = "SELECT * FROM `status`;";
                    $result_name_status = $conn->query($sql_status);
                    if ($result_name_status) {
                        if ($result_name_status->num_rows > 0) {
                            while ($name_status = $result_name_status->fetch_assoc()) {
                                $selected_status = ($name_status['name-status']===$status_tour) ? 'selected' : '';
                                echo '<option value="' . htmlspecialchars($name_status['name-status']) . '"' . $selected_status . '>'
                                    . htmlspecialchars($name_status['name-status'])
                                    . '</option>';
                            }
                        }
                    }
                    echo '</select>';
                    ?>
                    <select id="sort" name="sort" class="px-2 py-2 border-accent">
                        <option value="">Sắp xếp</option>
                        <option value="gia-thap-den-cao"
                            <?php echo (isset($_GET['sort']) && $_GET['sort']==='gia-thap-den-cao') ? 'selected' : ''; ?>>Giá từ thấp đến cao
                        </option>
                        <option value="gia-cao-den-thap"
                            <?php echo (isset($_GET['sort']) && $_GET['sort']==='gia-cao-den-thap') ? 'selected' : ''; ?>>Giá từ cao đến thấp
                        </option>    
                    </select>
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
                } else {
                    echo '<span class="disable">«</span>';
                }
                for ($i = 1; $i <= $total_pages; $i++) {
                    if ($i == $page) {
                        echo '<span class="accent">' . $i . '</span>';
                    } else {
                        echo '<a  class="number" href="?page=admin-tour&tour-page=' . $i . '">' . $i . '</a>';
                    }
                }
                if ($page < $total_pages) {
                    echo '<a href="?page=admin-tour&tour-page=' . ($page + 1) . '">»</a>';
                } else {
                    echo '<span class="disable">»</span>';
                }
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
                    if($result) {
                        if($result->num_rows > 0) {
                            while ($tour = $result->fetch_assoc()) {
                                echo '<tr class="text-center">';
                                    echo '<td><input type="checkbox" name="tours[]" value="' . htmlspecialchars($tour['id-tour']) . '" class="tour-select"></td>';
                                    if (!empty($tour['title-tour'])) {
                                        echo '<td><div class="text-start">';
                                            $title =  htmlspecialchars($tour['title-tour']);
                                            $shortTitle = truncateTitle($title);
                                            echo '<a class="accent link" href="admin-edit-tour?id-tour=' .htmlspecialchars($tour['id-tour']) . '">' . $shortTitle . '  </a>';
                                            echo '<a href="../single-tour?id-tour=' . htmlspecialchars($tour['id-tour']) . '" target="_blank"><i class="icon fa-solid fa-eye"></i></a>';
                                            echo '</div>';
                                            echo '</td>';
                                        } else {
                                        echo '<td></td>';
                                    }
                                    
                                    echo '<td>' . (!empty($tour['id-tour']) ? htmlspecialchars($tour['id-tour']) : '') . '</td>';
                                    echo '<td>' . (!empty($tour['location-tour']) ? htmlspecialchars($tour['location-tour']) : '') . '</td>';
                                    echo '<td>' . (!empty($tour['price-tour']) ? number_format($tour['price-tour']) : '') . '</td>';
                                    echo '<td>' . (!empty($tour['date-tour']) ? formatDate($tour['date-tour']) : '') . '</td>';
                                    echo '<td class="text-start">' . (!empty($tour['starting-gate']) ? htmlspecialchars($tour['starting-gate']) : '') . '</td>';
                                    $sql_name_status = "SELECT * FROM `status` WHERE `id-status` = " . intval($tour['id-status-tour']) . ";";
                                    $result_status = $conn->query($sql_name_status);
                                    if($result_status && $result_status->num_rows > 0) {
                                        $name_status_for_tour = $result_status->fetch_assoc();
                                        echo '<td>' . htmlspecialchars($name_status_for_tour['name-status']) . '</td>';
                                    }
                                    //echo '<td>' . htmlspecialchars($tour['status-tour']) . '</td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="8">Không tìm thấy kết quả phù hợp</td></tr>';
                        }
                    } else {
                        echo '<tr><td colspan="8">Lỗi: ' . $conn->error . '</td></tr>'; 
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
                } else {
                    echo '<span class="disable">«</span>';
                }
                for ($i = 1; $i <= $total_pages; $i++) {
                    if ($i == $page) {
                        echo '<p class="accent">' . $i . '</p>';
                    } else {
                        echo '<a  class="number" href="?page=admin-tour&tour-page= ' . $i . '">' . $i . '</a>';
                    }
                }
                if ($page < $total_pages) {
                    echo '<a href="?page=admin-tour&tour-page= ' . ($page + 1) . '">»</a>';
                } else {
                    echo '<span class="disable">»</span>';
                }
            echo '</div>';
        echo '</div>';
        ?>
    </main>
    <script>
        document.getElementById ('select-all').onclick = function() {
            var checkboxes = document.getElementsByClassName ('tour-select');
            for (var checkbox of checkboxes) {
                checkbox.checked = this.checked;
            }
        };
    </script>        
</body>
<?php
    // Đóng kết nối
    $conn->close();
?>

</html>