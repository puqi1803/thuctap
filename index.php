<?php
include 'partical/db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
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
    <?php include 'header.php'; ?>
    <main>
        <div class="banner">
            <img src="resources/img/home/banner.webp" alt="Banner">
        </div>
        <!---------------------------------- TIM KIEM --------------------------------------->
        <div class="timkiem container block mt-8 mb-5 px-5 py-5 border-normal shadow-xl">
            <h6>Bạn muốn đi đâu?</h6>
            <form action="" class="flex pt-3">
                <select class="px-4 py-4 mr-4 w-full border-normal outline-none" name="Địa điểm" id="diadiem">
                    <option value="default">Chọn địa điểm</option>
                    <option value="cantho">Cần Thơ</option>
                </select>
                <input class="px-4 py-4 mr-4 w-full border-normal outline-none" type="number" placeholder="Số lượng" min="1">
                <input class="px-4 py-4 mr-4 w-full border-normal outline-none" type="date" placeholder="Ngày khởi hành">
                <button class="button-secondary px-4 py-4 mr-4 outline-none" type="submit">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>
        </div>
        <!---------------------------------- TOUR NOI BAT --------------------------------------->
		<div class="title-page container pt-8 pb-0 text-center">
			<h2>TOUR NỔI BẬT</h2>
		</div>
		<div class="container pt-2 text-center">
			<h6>Nhanh tay nắm bắt cơ hội giảm giá cuối cùng. Đặt ngay để không bỏ lỡ!</h6>
		</div>
        <?php
		// Truy vấn
		$sql = "SELECT * FROM tour ORDER BY  `id-tour` DESC LIMIT 4";
		$result = $conn->query($sql);
        if ($result) {
            if ($result->num_rows > 0) {
				echo '<div class="container flex flex-row my-5 column-gap-4">';
					while ($row = $result->fetch_assoc()) {
						echo '<div class="tour-item w-25 pb-2 border-normal">';
							echo '<img class="tour-img w-full" src="' . htmlspecialchars($row["img-tour"]) . '">';
							echo '<div class="tour item flex flex-col row-gap-4 mx-2">';
								echo '<div class="flex flex-col flex-grow row-gap-3">';
									echo '<h6 class="tour-title">' . htmlspecialchars($row["title-tour"]) . '</h6>';
									echo '<span class="flex column-gap-2 w-full">
										<i class="icon fa-solid fa-location-dot"></i> <p> Khởi hành: '. htmlspecialchars($row["starting-gate"]) . '</p></span>';
									echo '<span class="flex column-gap-2">
										<i class="icon fa-solid fa-calendar-days"></i> <p> Ngày khởi hành: '. htmlspecialchars($row["date-tour"]) . '</p></span>';
									echo '<div class="highlight">' . number_format($row["price-tour"], 0, ',', '.') . ' đ</div>';
								echo '</div>';	
								echo '<button class="button-primary w-full py-2 px-2">Đặt tour</button>';
							echo '</div>';
						echo '</div>';
					}
				} else {
					echo "Không có kết quả nào.";
				}
				echo '</div>';
			} else {
				echo "Lỗi truy vấn: " . $conn->error;
			}
        ?>

        <!---------------------------------- BAI VIET MOI NHAT --------------------------------------->
    </main>

    <?php include 'footer.php' ?> 

    <?php
    // Đóng kết nối
    $conn->close();
    ?>
</body>