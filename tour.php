<?php
    include 'partical/db_connect.php';
    include 'includes/functions.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <?php
        $pageTitle="Tour Du Lịch"
    ?>
    <title>
        <?php
            echo htmlspecialchars($pageTitle);
        ?>
    </title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="description" content=""/>
    <meta http-equiv="refresh" content="900">
</head>

<body>
    <?php 
        include "header-main.php";
        
        //Khoi tao bien mac dinh
        $order_by = 'ORDER BY `created-at` DESC';
        $status_tour = 'Published';

        //Phan trang
        $results_per_page = 5;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $start_from = ($page-1) * $results_per_page;

        //Lay du lieu tu request
        $rq_location = isset($_GET['location-tour']) ? $_GET['location-tour'] : '';
        $rq_start_date = $_GET['date-tour'] ?? date('Y-m-d');
        $rq_budget = $_GET['budget'] ? $_GET['budget'] : '';

        //Xay dung truy van
        $sql = "SELECT * FROM tour WHERE `status-tour`=?";
        if ($rq_location) {
            $sql .= " AND `location-tour` = '" . $conn->real_escape_string($rq_location) . "'";
        }
        /*if ($rq_start_date) {
            $sql .= " AND `date-tour` = '" . $conn->real_escape_string($rq_start_date) . "'";
        }*/
        if ($rq_budget) {
            switch($rq_budget) {
                case 'duoi-5-trieu':
                    $sql .= " AND  `price-tour` < 5000000";
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

        //Sap xep theo gia
        if (isset($_GET['sort'])) {
            switch($_GET['sort']) {
                case 'gia-thap-den-cao':
                    $order_by = 'ORDER BY `price-tour` ASC';
                    break;
                case 'gia-cao-den-thap':
                    $order_by = 'ORDER BY `price-tour` DESC';
                    break;
            }
        }

        //Phan trang
        $sql .= " " . $order_by . " LIMIT $start_from, $results_per_page";

        //Dem ket qua phan trang
        $sql_count = "SELECT COUNT(*) AS total FROM tour WHERE `status-tour`=?";
        if ($rq_location) {
            $sql_count .= " AND `location-tour` = '" . $conn->real_escape_string($rq_location) . "'";
        }
        /*if ($rq_start_date) {
            $sql_count .= " AND `date-tour` = '" . $conn->real_escape_string($rq_start_date) . "'";
        }*/
        if ($rq_budget) {
            switch($rq_budget) {
                case 'duoi-5-trieu':
                    $sql_count .= " AND  `price-tour` < 5000000";
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

        $stmt_count = $conn->prepare($sql_count);
        $stmt_count->bind_param("s", $status_tour);
        $stmt_count->execute();
        $result_count = $stmt_count->get_result();
        $row_account = $result_count->fetch_assoc();
        $total_results = $row_account['total'];
        $total_pages = ceil($total_results/$results_per_page);
        $stmt_count->close();
        
        //Thuc thi truy van
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $status_tour);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        //Lay danh sach dia diem cho bo loc
        $sql_location = "SELECT `name-location`, `area-location` FROM `location` ORDER BY `area-location` ASC ";
        $result_location = $conn->query($sql_location);
    ?>
    <main class="tour container">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb accent">
                <li class="breadcrumb-item"><a href="/nienluan.com/">Trang Chủ</a></li>
                <li class="breadcrumb-item active" breadcrumb-item active="page"><?php echo htmlspecialchars($pageTitle); ?></li>
            </ol>
        </nav>
        <div class="title-page mt-5 text-center">
            <h1><?php echo htmlspecialchars($pageTitle); ?></h1>
        </div>
        <div class="tour-container row mt-5 gx-5 justify-content-between flex-nowrap">
        <!---------------------------------- BO LOC --------------------------------------->
        <div class="filter col-3">
            <h5>BỘ LỌC TÌM KIẾM</h5>
            <form action="tour.php" method="GET"> 
            <div class="filter-content d-flex flex-column mt-4 p-4 row-gap-4 border-round background-gray">
                <div class="filter-criteria">
                    <h6>Địa điểm</h6>
                    <select id="location-tour" name="location-tour" class="w-100 p-2 border-round" >
                        <?php
                        $location_by_area = [];
                        //Nhom dia diem theo khu vuc
                        if($result_location) {
                            while( $location_tour = $result_location->fetch_assoc()) {
                                $area_location = $location_tour['area-location'];
                                $name_location = $location_tour['name-location'];
                                //$selected_location = ($location_tour['name-location'] == $rq_location) ? 'selected' : '';
                            
                            if (!isset($location_by_area[$area_location])) {
                                $location_by_area[$area_location] = [];
                            }
                            $location_by_area[$area_location][] = $name_location;
                            }
                        }
                        //Hien thi dia diem theo khu vuc
                        echo '<option value="">Tất cả</option>';
                        foreach ($location_by_area as $area_location => $locations) {
                            echo '<optgroup label="' . htmlspecialchars($area_location) . '">';
                            foreach ($locations as $location) {
                                $selected_location = ($location == $rq_location) ? 'selected' : ''; 
                                echo '<option value="' . htmlspecialchars($location) . '" ' . $selected_location . '>' . htmlspecialchars($location) . '</option>';
                            }
                            echo '</optgroup>';
                        }
                        ?>
                    </select>
                </div>   
                <div class="filter-criteria">
                    <h6>Ngân sách</h6>
                    <select class="col w-100 p-2 border-round" id="budget" name="budget">
                        <option value="">Tất cả</option>
                        <option value="duoi-5-trieu" <?php echo ($rq_budget === 'duoi-5-trieu') ? 'selected' : ''; ?>>Dưới 5 triệu</option>
                        <option value="5-10-trieu" <?php echo ($rq_budget === '5-10-trieu') ? 'selected' : ''; ?>>Từ 5 triệu - 10 triệu</option>
                        <option value="10-20-trieu" <?php echo ($rq_budget === '10-20-trieu') ? 'selected' : ''; ?>>Từ 10 triệu - 20 triệu</option>
                        <option value="tren-20-trieu" <?php echo ($rq_budget === 'tren-20-trieu') ? 'selected' : ''; ?>>Trên 20 triệu</option>
                    </select>
                </div> 
                <div class="filter-criteria">
                    <h6>Ngày khởi hành</h6>
                    <input class="p-2 w-100 border-round background-white" type="date" value="<?php echo htmlspecialchars($rq_start_date); ?>" name="start-date">
                </div>
                <div>
                    <!---- <button class="button-light-background w-100 p-2">Làm mới</button> --->
                    <button class="button-primary w-100 p-2 mt-2" type=submit>Áp dụng</button>
                </div>
                </div>
            </div>
            </form>
        <!---------------------------------- KET QUA --------------------------------------->
        <div class="result col">
            <div class="d-flex flex-row justify-content-between align-items-center">
                <p class="accent">Chúng tôi tìm thấy <?php echo htmlspecialchars($total_results); ?> chương trình tour cho Quý khách</p>
                <div class="d-flex flex-row align-items-center column-gap-2">
                    <p class="accent">Sắp xếp theo</p>
                    <form action="tour.php" method="GET">
                           <input type="hidden" name="location-tour" value="<?php echo htmlspecialchars($rq_location);?>"> 
                           <input type="hidden" name="budget" value=" <?php echo htmlspecialchars($rq_budget);?>"> 
                        <select id="sort" name="sort" class="px-2 py-2 border-accent" onchange="this.form.submit()">
                            <option value="">Tất cả</option>
                            <option value="gia-thap-den-cao"
                                <?php echo (isset($_GET['sort']) && $_GET['sort']==='gia-thap-den-cao') ? 'selected' : ''; ?>>Giá từ thấp đến cao
                            </option>
                            <option value="gia-cao-den-thap"
                                <?php echo (isset($_GET['sort']) && $_GET['sort']==='gia-cao-den-thap') ? 'selected' : ''; ?>>Giá từ cao đến thấp
                            </option>    
                        </select>
                    </form>
                </div>
            </div>
            <hr class="mt-4">
            <div class="d-flex flex-column row-gap-4 mt-4">
                <?php
                    if($result) {
                        if($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<div class="result-content d-flex flex-row border-round">';
                                    echo '<img class="tour-img object-fit-cover w-50" src="resources/uploads/' . htmlspecialchars($row["img-tour"]) . '">';
                                    echo '<div class="tour-content d-flex flex-column w-50 row-gap-2 p-4">';
                                        echo '<a href="single-tour.php?id-tour=' . htmlspecialchars($row["id-tour"]) . '">
                                            <h5>' . htmlspecialchars($row["title-tour"]) . '</h5></a>';
                                            echo '<div class="d-flex flex-wrap column-gap-4 justify-content-between">';
                                                echo '<div class="d-flex flex-row column-gap-2 align-items-center">
                                                    <i class="icon fa-classic fa-solid fa-circle-info fa-fw"></i>
                                                    <p>Mã chương trình: </p>
                                                    <p class="id-tour accent">' . htmlspecialchars($row["id-tour"]) . '</p>
                                                </div>';
                                        echo '</div>';

                                        echo '<div class="d-flex flex-wrap column-gap-4 justify-content-between">';
                                            echo '<div class="d-flex flex-row column-gap-2 align-items-center">
                                                <i class="icon fa-solid fa-plane"></i>
                                                <p>Khởi hành: </p>
                                                <p class="id-tour accent">' . htmlspecialchars($row["starting-gate"]) . '</p>
                                            </div>';
                                            echo '<div class="d-flex flex-row column-gap-2 align-items-center">
                                                <i class="icon fa-solid fa-location-dot"></i>
                                                <p>Địa điểm: </p>
                                                <p class="accent">' . htmlspecialchars($row["location-tour"]) . '</p>
                                            </div>';
                                        echo '</div>';
                                        
                                        echo '<div class="d-flex flex-wrap column-gap-4 justify-content-between">';
                                            echo '<div class="d-flex flex-row column-gap-2 align-items-center">
                                                <i class="icon fa-regular fa-clock"></i>
                                                <p>Thời gian: </p>
                                                <p class="accent">' . htmlspecialchars($row["duration-tour"]) . '</p>
                                            </div>';
                                            echo '<div class="d-flex flex-row column-gap-2">
                                                <i class="icon fa-solid fa-calendar-days"></i>
                                                <p>Ngày khởi hành: </p>
                                                <p class="accent">' . formatDate($row["date-tour"]) . '</p>
                                            </div>';
                                        echo '</div>';

                                        echo '<div class="d-flex flex-row column-gap-4 mt-6 justify-content-between align-items-center">';
                                            echo '<div class="d-flex flex-column">
                                                <p>Giá chỉ từ: </p>
                                                <p class="highlight tour-price">' . number_format($row["price-tour"]) . '</p>
                                            </div>';
                                            echo '<div>
                                                <button class="button-primary px-2 py-2">Xem chi tiết</button>
                                            </div>';
                                        echo '</div>';                         
                                    echo '</div>';
                                echo '</div>';    
                            }
                        } else {
                            echo "Không tìm thấy kết quả phù hợp";
                        }
                    } else {
                        echo "Lỗi truy vấn" . $conn->error;
                    }

                    //Phan trang
                    echo '<div class="pagination flex flex-row mt-8 column-gap-4 justify-content-center align-items-center">';
                    if ($page > 1) {
                        echo '<a href="?page=' . ($page - 1) . '">« Trang trước</a>';
                    } /*else {
                        echo '<span class="disable">« Trang trước</span>';
                    }*/
                    if ($total_pages==1) {
                        echo '<span>1</span>';
                    }
                    else {
                        for ($i = 1; $i <= $total_pages; $i++) {
                            if ($i == $page) {
                                echo '<span class="accent">' . $i . '</span>';
                            } else {
                                echo '<a  class="number" href="?page=' . $i . '">' . $i . '</a>';
                            }
                        }
                    }
                    if ($page < $total_pages) {
                        echo '<a href="?page=' . ($page + 1) . '">Trang sau »</a>';
                    } /*else {
                        echo '<span class="disable">« Trang sau</span>';
                    }*/
                    echo '</div>';
                ?>
            </div>
        </div>
    </div>
    </div>
    </main>
    <?php include "footer.php" ?>
</body>

<?php
    // Đóng kết nối
    $conn->close();
?>

</html>