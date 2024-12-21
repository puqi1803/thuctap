<?php
include 'partical/db_connect.php';
include 'includes/functions.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <title>HaiAu Tourist</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="description" content=""/>
    <meta http-equiv="refresh" content="900">
</head>

<body>
    <?php include 'header-banner.php'; 
    $sql_location = "SELECT `name-location` FROM `location`  ORDER BY `name-location` ASC ";
    $result_location = $conn->query($sql_location);
    ?>
    <main class="index">
        <!---------------------------------- 
        <div class="banner">
            <img class="size-full object-fit-cover" src="resources/img/home/banner.webp" alt="Banner">
        </div>
        --------------------------------------->
        
        <!---------------------------------- TIM KIEM --------------------------------------->
        <div class="search container mx-auto my-5 p-5 border-round shadow-sm background-white">
            <h6>Bạn muốn đi đâu?</h6>
            <form action="" class="row column-gap-2 flex-nowrap">
                <select id="location-tour" name="location-tour" class="col p-3 border-round" >
                    <?php
                    if ($result_location) {
                        if($result_location->num_rows > 0) {
                            while($location_tour = $result_location->fetch_assoc()) {
                                echo '<option value="' . htmlspecialchars($location_tour['name-location']) . '">'
                                . htmlspecialchars($location_tour['name-location']) . '</option>';
                            }
                        }
                    }
                    ?>
                </select>
                <input class="col p-3 border-round" type="date" value="<?php echo date('Y-m-d');?>" id="ngaydi">
                <button class="col-1 px button-primary p-3" type="submit">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>
        </div>
        <!---------------------------------- TOUR NOI BAT --------------------------------------->
        <div class="tour-noi-bat container mx-auto py-5">
            <a href="tour"><h2 class="title-page text-center">TOUR NỔI BẬT</h2></a>
            <h6 class="mt-2 text-center">Nhanh tay nắm bắt cơ hội giảm giá cuối cùng. Đặt ngay để không bỏ lỡ!</h6>
            <?php
            $sql = "SELECT * FROM tour WHERE `status-tour`='Published' ORDER BY  `created-at` DESC LIMIT 4";
            $result = $conn->query($sql);
            if ($result) {
                if ($result->num_rows > 0) {
                    echo '<div class="tour-container row mt-4 justify-content-between">';
                        //Tour item
                        while ($row = $result->fetch_assoc()) {
                            echo '<div class="col-3">';
                                echo '<div class="tour-item d-flex flex-column pb-2 text-left border-round shadow-sm">';
                                    echo '<img class="tour-img w-100 object-fit-cover" src="resources/uploads/' . htmlspecialchars($row["img-tour"]) . '">';
                                    echo '<div class="tour-content d-flex flex-column p-3 row-gap-3 text-left">';
                                        echo '<a href="single-tour.php?id-tour=' . htmlspecialchars($row["id-tour"]) . '">
                                        <h6>' . htmlspecialchars($row["title-tour"]) . '</h6></a>';
                                        echo '<div class="d-flex flex-row column-gap-2 justify-space-center">
                                            <i class="icon fa-solid fa-location-dot"></i>
                                            <p> Khởi hành: '. htmlspecialchars($row["starting-gate"]) . '</p>
                                            </div>';
                                        echo '<div class="d-flex flex-row column-gap-2 justify-space-center">
                                            <i class="icon fa-solid fa-calendar-days"></i>
                                            <p> Ngày khởi hành: '. formatDate($row["date-tour"]) . '</p>
                                            </div>';
                                        echo '<div class="highlight tour-price">' . number_format($row["price-tour"], 0, ',', '.') . ' đ</div>';
                                        echo '<button class="button-primary w-full py-2 px-2">Đặt tour</button>';
                                    echo '</div>';
                                echo '</div>';
                            echo '</div>';
                        }
                    echo '</div>';
                    echo '<div class="d-flex justify-content-center mt-4">';
                        echo '<a href="tour"><button class="button-light-background py-2 px-4">Tất cả tour</button></a>';
                    echo '</div>';
                    } else {
                        echo 'Không tìm thấy tour phù hợp';
                    }
                    echo '</div>';
                } else {
                    echo 'Lỗi truy vấn: ' . $conn->error;
                }
            ?>
        </div>
        <!---------------------------------- BAI VIET MOI NHAT 
        <div class="d-flex flex-row w-full">
            <div class="highlight-post d-flex flex-column column-gap-2">
                <h2>Tin tức</h2>
                <h3>BẠN ĐÃ SẴN SÀNG MỞ KHÓA CUỘC VUI CHƯA?</h3>
                <p>
                    Cuối năm đến gần, cũng là lúc các công ty tất bật chuẩn bị tổ chức tiệc Tất niên (Year End Party - YEP) để tổng kết một năm đầy nỗ lực và thành công.
                    Nhưng nếu bạn cảm thấy hoang mang, không biết bắt đầu từ đâu giữa hàng loạt công việc phải lo, thì đừng lo!
                    HaiAu Tourist chính là "vị cứu tinh" giúp bạn tổ chức một buổi tiệc hoành tráng, mang đậm dấu ấn riêng của công ty mình.
                </p>
            </div>
        </div>--------------------------------------->

        <!---------------------------------- NHUNG TRAI NGHIEM THU VI --------------------------------------->
        <div class="container mx-auto post py-5 text-center">
            <a href="tin-tuc"><h2 class="title-page">NHỮNG TRẢI NGHIỆM THÚ VỊ</h2></a>
            <div class="post-content row mt-4">
            <?php
            //Post column 1
            $sql = "SELECT * FROM post WHERE `status-post` = 'Published' ORDER BY `id-post` DESC LIMIT 1";
            $result = $conn->query($sql);
            if ($result) {
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="post-column-1 col-5">';
                            echo '<div class="d-flex flex-column border-round shadow-sm">';
                                echo '<img class="post-img object-fit-cover" src="resources/uploads/' . htmlspecialchars($row["img-post"]) . '">'; 
                                echo '<div class="post-content d-flex flex-column p-4 row-gap-1 text-start">';
                                    echo '<a href="single-post?slug-post=' . htmlspecialchars($row["slug-post"]) . '">
                                    <h6>' . htmlspecialchars($row["title-post"]) . '</h6></a>'; 
                                    $expert = htmlspecialchars($row["expert-post"]);
                                    $shortExpert = truncateExpert($expert);
                                    echo '<p>' . $shortExpert . '</p>';
                                    echo '<div class="date-post d-flex flex-row column-gap-2 align-items-center justify-content-end">';
                                        echo '<i class="icon fa-regular fa-clock"></i> <p class="note">' . formatDate($row["date-post"]) . '</p>';
                                    echo '</div>';
                                echo '</div>';
                            echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo 'Không tìm thấy bài viết phù hợp.';
                }
            } else {
                echo 'Lỗi truy vấn: '. $conn->error;
            }
            ?>
            <?php
            //Post column 2
            $sql = "SELECT * FROM `post`
            WHERE `status-post` = 'Published' AND `id-post` <> (SELECT `id-post` FROM `post` ORDER BY `id-post` DESC LIMIT 1)
            ORDER BY `id-post` DESC LIMIT 3";
            $result = $conn->query($sql);
            if ($result) {
                if ($result->num_rows > 0) {
                    echo '<div class="post-column-2 col d-flex flex-column row-gap-2">';
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="d-flex flex-row border-round shadow-sm">';
                            echo '<img class="post-img object-fit-cover" src="resources/uploads/' . htmlspecialchars($row["img-post"]) . '">';
                            echo '<div class="post-content d-flex flex-column row-gap-2 p-4 text-start">';
                                echo '<a href="single-post?slug-post=' . htmlspecialchars($row["slug-post"]) . '">
                                <h6>' . htmlspecialchars($row["title-post"]) . '</h6></a>'; 
                                $expert = htmlspecialchars($row["expert-post"]);
                                $shortExpert = truncateExpert($expert);
                                echo '<p>' . $shortExpert . '</p>';
                                echo '<div class="date-post d-flex flex-row column-gap-2 align-items-center justify-content-end">';
                                    echo '<i class="icon fa-regular fa-clock"></i> <p class="note">' . formatDate($row["date-post"]) . '</p>';
                                echo '</div>';
                            echo '</div>';
                            echo '</div>';
                    }
                    echo '</div>';
                } else {
                    echo 'Không tìm thấy bài viết phù hợp.';
                }
            } else {
                echo 'Lỗi truy vấn: ' . $conn->error;
            }
            ?>
            </div>
        </div>

        <!---------------------------------- BANNER FOOTER --------------------------------------->
        <img class="img-banner-footer py-5 w-100 object-fit-cover" src="resources/img/home/banner-footer-home.webp">

        <!---------------------------------- KHACH HANG --------------------------------------->
        <div class="our-customer container mx-auto py-5 text-center">
            <h2 class="title-page">ĐƯỢC TIN TƯỞNG BỞI</h2>
            <div class="logo-customer d-flex flex-row mt-4 column-gap-8 justify-content-center">
                <img src="resources/img/home/logo-apc.webp">
                <img src="resources/img/home/logo-vas.svg">
                <img src="resources/img/home/logo-ctu.png">
                <img src="resources/img/home/logo-huflit.webp">
                <img src="resources/img/home/logo-fpt.png">
            </div>
        </div>
    </main>
    <?php include 'footer.php' ?> 
</body>

<?php
    // Đóng kết nối
    $conn->close();
?>

</html>