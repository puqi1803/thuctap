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
    <script src="https://kit.fontawesome.com/bbc8bd235c.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="resources/style.css">
    <meta http-equiv="refresh" content="900">
</head>

<body>
    <?php include "header-main.php" ?>
    <main class="tour container">
        <div class="breadcrumb accent flex flex-row mt-4 column-gap-2">
            <li><a href="/nienluan.com/">Trang Chủ</a>
            <li class="current"><?php echo htmlspecialchars($pageTitle); ?></li>
        </div>
        <div class="title-page flex flex-col mt-6 text-center">
            <h1><?php echo htmlspecialchars($pageTitle); ?></h1>
        </div>
        <div class="flex flex-row pt-8 column-gap-6 justify-content-space-between">
        <!---------------------------------- BO LOC --------------------------------------->
        <div class="flex flex-col w-30">
            <h5>BỘ LỌC TÌM KIẾM</h5>
            <div class="flex flex-col border-normal mt-4 px-4 py-4 row-gap-5 background-gray">
                <div class="tieu-chi-loc flex flex-col row-gap-2">
                    <h6>Địa điểm</h6>
                    <select id="location" class="px-2 py-2 border-normal background-white">
                        <option value="default">Chọn địa điểm</option>
                        <option value="cantho">Cần Thơ</option>
                    </select>
                </div>   
                <div class="tieu-chi-loc flex flex-col row-gap-2">
                    <h6>Ngân sách</h6>
                    <div class="options inline-grid row-gap-2 column-gap-2">
                        <div class="px-2 py-2 text-center border-normal background-white">
                            <p>Từ 5 triệu</p>
                        </div>
                        <div class="px-2 py-2 text-center border-normal background-white">
                            <p>Từ 5 - 10 triệu</p>
                        </div>
                        <div class="px-2 py-2 text-center border-normal background-white">
                            <p>Từ 10 - 15 triệu</p>
                        </div>
                        <div class=" px-2 py-2 text-center border-normal background-white">
                            <p>Trên 20 triệu</p>
                        </div>
                    </div>
                </div> 
                <div class="tieu-chi-loc flex flex-col row-gap-2">
                    <h6>Ngày khởi hành</h6>
                    <?php $today = date('Y-d-m');?>
                    <input class="px-2 py-2 border-normal background-white" type="date" value="<?php echo $today;?>">
                </div>
                <div class="flex flex-col row-gap-2">
                    <button class="button-light-background px-2 py-2">Làm mới</button>
                    <button class="button-primary px-3 py-3">Áp dụng</button>
                </div>
            </div>
        </div>
        <!---------------------------------- KET QUA --------------------------------------->
        <div class="ket-qua-loc flex flex-col w-full">
            <div class="flex flex-row justify-content-space-between align-items-center">
                <p class="accent">Chúng tôi tìm thấy XXX chương trình tour cho Quý khách</p>
                <div class="flex flex-row align-items-center column-gap-2">
                    <p class="accent">Sắp xếp theo</p>
                    <select id="bo-loc" class="px-2 py-2 border-accent">
                     <option value="tat-ca">Tất cả</option>
                        <option value="gia-thap-den-cao">Giá từ thấp đến cao</option>
                        <option value="gia-cao-den-thap">Giá từ cao đếnn thấp</option>    
                    </select>
                </div>
            </div>
            <hr class="mt-4"></hr>
            <div class="flex flex-col row-gap-4 mt-4">
                <?php
                    $results_per_page = 3;

                    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $start_from = ($page-1) * $results_per_page;
                    
                    $sql_count = "SELECT COUNT(*) AS total FROM tour";
                    $result_count = $conn->query($sql_count);
                    $row_account = $result_count->fetch_assoc();
                    $total_results = $row_account['total'];
                    $total_pages = ceil($total_results/$results_per_page);

                    $sql = "SELECT * FROM tour ORDER BY `id-tour` DESC LIMIT $start_from, $results_per_page";
                    $result = $conn->query($sql);

                    if($result) {
                        if($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<div class="flex flex-row border-normal">';
                                    echo '<img class="tour-img object-fit-cover" src="' . htmlspecialchars($row["img-tour"]) . '">';
                                    echo '<div class="flex flex-col w-full row-gap-2 px-4 py-4">';
                                        echo '<a href="single-tour.php?id-tour=' . htmlspecialchars($row["id-tour"]) . '">
                                            <h5>' . htmlspecialchars($row["title-tour"]) . '</h5></a>';
                                            echo '<div class="flex flex-row column-gap-4 justify-content-space-between">';
                                            echo '<div class="flex flex-row column-gap-2 align-items-center">
                                                <i class="icon fa-classic fa-solid fa-circle-info fa-fw"></i>
                                                <p>Mã chương trình: </p>
                                                <p class="id-tour accent">' . htmlspecialchars($row["id-tour"]) . '</p>
                                            </div>';
                                            echo '<div class="flex flex-row column-gap-2 align-items-center">
                                                <i class="icon fa-solid fa-location-dot"></i>
                                                <p>Khởi hành: </p>
                                                <p class="accent">' . htmlspecialchars($row["starting-gate"]) . '</p>
                                            </div>';
                                        echo '</div>';
                                        
                                        echo '<div class="flex flex-row column-gap-4 justify-content-space-between">';
                                            echo '<div class="flex flex-row column-gap-2 align-items-center">
                                                <i class="icon fa-regular fa-clock"></i>
                                                <p>Thời gian: </p>
                                                <p class="accent">' . htmlspecialchars($row["duration-tour"]) . '</p>
                                            </div>';
                                            echo '<div class="flex flex-row column-gap-2">
                                                <i class="icon fa-solid fa-calendar-days"></i>
                                                <p>Ngày khởi hành: </p>
                                                <p class="accent">' . formatDate($row["date-tour"]) . '</p>
                                            </div>';
                                        echo '</div>';

                                        echo '<div class="flex flex-row column-gap-4 mt-6 justify-content-space-between align-items-center">';
                                            echo '<div class="flex flex-col">
                                                <p>Giá chỉ từ: </p>
                                                <p class="highlight">' . number_format($row["price-tour"]) . '</p>
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
                    } else {
                        echo '<span class="disable">« Trang trước</span>';
                    }
                    for ($i = 1; $i <= $total_pages; $i++) {
                        if ($i == $page) {
                            echo '<p class="accent">' . $i . '</p>';
                        } else {
                            echo '<a  class="number" href="?page=' . $i . '">' . $i . '</a>';
                        }
                    }
                    if ($page < $total_pages) {
                        echo '<a href="?page=' . ($page + 1) . '">Trang sau »</a>';
                    } else {
                        echo '<span class="disable">« Trang trước</span>';
                    }
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