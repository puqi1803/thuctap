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
    <script src="https://kit.fontawesome.com/bbc8bd235c.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="resources/style.css">
    <meta http-equiv="refresh" content="900">
</head>

<body>
    <?php include 'header-banner.php'; ?>
    <main>
        <!---------------------------------- 
        <div class="banner">
            <img class="size-full object-fit-cover" src="resources/img/home/banner.webp" alt="Banner">
        </div>
        --------------------------------------->
        
        <!---------------------------------- TIM KIEM --------------------------------------->
        <div class="timkiem container block mt-8 mb-5 px-5 py-5 border-normal align-items-center shadow-xl background-white">
            <h6>Bạn muốn đi đâu?</h6>
            <form action="" class="flex pt-3 justify-content-space-between">
                <select class="px-4 py-4 mr-4 w-full border-normal outline-none" name="Địa điểm" id="diadiem">
                    <option value="default">Chọn địa điểm</option>
                    <option value="cantho">Cần Thơ</option>
                </select>
                <input class="px-4 py-4 mr-4 w-full border-normal outline-none" type="number" placeholder="Số lượng" min="1">
                <?php $today = date('Y-d-m');?>
                <input class="px-4 py-4 mr-4 w-full border-normal outline-none" type="date" value="<?php echo $today;?>">
                <button class="button-secondary px-4 py-4 mr-4 w-20 outline-none" type="submit">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>
        </div>
        <!---------------------------------- TOUR NOI BAT --------------------------------------->
        <div class="container flex flex-col mt-8 text-center">
            <h2 class="title-page">TOUR NỔI BẬT</h2>
            <h6 class="mt-2">Nhanh tay nắm bắt cơ hội giảm giá cuối cùng. Đặt ngay để không bỏ lỡ!</h6>
            <?php
            $sql = "SELECT * FROM tour ORDER BY  `id-tour` DESC LIMIT 4";
            $result = $conn->query($sql);
            if ($result) {
                if ($result->num_rows > 0) {
                    echo '<div class="flex flex-row mt-5 column-gap-4">';
                        //Tour item
                        while ($row = $result->fetch_assoc()) {
                            echo '<div class="tour-item w-25 pb-2 border-normal shadow-md">';
                                echo '<img class="tour-img w-full object-fit-cover" src="' . htmlspecialchars($row["img-tour"]) . '">';
                                echo '<div class="tour content flex flex-col row-gap-4 mt-2 mx-2 px-2 text-left">';
                                    echo '<div class="flex flex-col flex-grow row-gap-3">';
                                        echo '<a href="single-tour.php?id-tour=' . htmlspecialchars($row["id-tour"]) . '">
                                        <h6>' . htmlspecialchars($row["title-tour"]) . '</h6></a>';
                                        echo '<div class="flex column-gap-2 w-full">
                                                <i class="icon fa-solid fa-location-dot"></i>
                                                <p> Khởi hành: '. htmlspecialchars($row["starting-gate"]) . '</p>
                                            </div>';
                                        echo '<div class="flex column-gap-2">
                                                <i class="icon fa-solid fa-calendar-days"></i>
                                                <p> Ngày khởi hành: '. formatDate($row["date-tour"]) . '</p>
                                            </div>';
                                        echo '<div class="highlight tour-price">' . number_format($row["price-tour"], 0, ',', '.') . ' đ</div>';
                                    echo '</div>';	
                                    echo '<button class="button-primary w-full py-2 px-2">Đặt tour</button>';
                                echo '</div>';
                            echo '</div>';
                        }
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
        <div class="flex flex-row w-full">
            <div class="highlight-post flex flex-col column-gap-2">
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
        <div class="container flex flex-col row-gap-5 mt-8 text-center">
            <h2 class="title-page">NHỮNG TRẢI NGHIỆM THÚ VỊ</h2>
            <div class="flex flex-row column-gap-4">
            <?php
            //Post column 1
            $sql = "SELECT * FROM post ORDER BY `id-post` DESC LIMIT 1";
            $result = $conn->query($sql);
            if ($result) {
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="post-column-1 flex flex-col w-40 border-normal shadow-md">';
                            echo '<img class="post-img object-fit-cover" src="' . htmlspecialchars($row["img-post"]) . '">'; 
                            echo '<div class="flex flex-col row-gap-2 my-2 px-4 text-left">';
                                echo '<a href="single-post?slug-post=' . htmlspecialchars($row["slug-post"]) . '">
                                <h6>' . htmlspecialchars($row["title-post"]) . '</h6></a>'; 
                                echo '<p>' . htmlspecialchars($row["expert-post"]) . '</p>';
                                echo '<div class="date-post flex flex-row column-gap-2 align-items-center justify-content-end">';
                                    echo '<i class="icon fa-regular fa-clock"></i> <p class="note">' . formatDate($row["date-post"]) . '</p>';
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
            WHERE `id-post` <> (SELECT `id-post` FROM `post` ORDER BY `id-post` DESC LIMIT 1)
            ORDER BY `id-post` DESC LIMIT 3";
            $result = $conn->query($sql);
            if ($result) {
                if ($result->num_rows > 0) {
                    echo '<div class="post-column-2 flex flex-col row-gap-4">';
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="flex flex-row column-gap-2 border-normal shadow-md">';
                            echo '<img class="post-img object-fit-cover" src="' . htmlspecialchars($row["img-post"]) . '">';
                            echo '<div class="flex flex-col row-gap-2 px-4 py-4 text-left w-full">';
                                echo '<a href="single-post?slug-post=' . htmlspecialchars($row["slug-post"]) . '">
                                <h6>' . htmlspecialchars($row["title-post"]) . '</h6></a>'; 
                                $expert = htmlspecialchars($row["expert-post"]);
                                $shortExpert = truncateExpert($expert);
                                echo '<p>' . $shortExpert . '</p>';
                                echo '<div class="date-post flex flex-row column-gap-2 align-items-center justify-content-end">';
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
        <img class="img-banner-footer mt-8 w-full object-fit-cover" src="resources/img/home/banner-footer-home.webp">

        <!---------------------------------- KHACH HANG --------------------------------------->
        <div class="container flex flex-col row-gap-5 mt-8 text-center">
            <h2 class="title-page">ĐƯỢC TIN TƯỞNG BỞI</h2>
            <div class="flex flex-row column-gap-8 justify-content-center">
                <img class="h-8 object-fit-cover" src="resources/img/home/logo-apc.webp">
                <img class="h-8 object-fit-cover" src="resources/img/home/logo-apc.webp">
                <img class="h-8 object-fit-cover" src="resources/img/home/logo-apc.webp">
                <img class="h-8 object-fit-cover" src="resources/img/home/logo-apc.webp">
                <img class="h-8 object-fit-cover" src="resources/img/home/logo-apc.webp">
                <img class="h-8 object-fit-cover" src="resources/img/home/logo-apc.webp">
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