<?php
    include 'partical/db_connect.php';
    include 'includes/functions.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <?php
        $pageTitle="Tin Tức"
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
    <main class="tin-tuc container">
        <div class="breadcrumb accent flex flex-row mt-4 column-gap-2">
            <li><a href="/nienluan.com/">Trang Chủ</a></li>
            <li><a class="current"><?php echo htmlspecialchars($pageTitle); ?></a></li>
        </div>
        <div class="title-page flex flex-col mt-6 text-center">
            <h1><?php echo htmlspecialchars($pageTitle); ?></h1>
        </div>
        <div class="flex flex-col pt-8 row-gap-4">
        <!---------------------------------- BAI VIET --------------------------------------->
            <div class="post flex flex-wrap w-full row-gap-4 justify-content-space-between">
                <?php
                $results_per_page = 3;

                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $start_from = ($page-1) * $results_per_page;

                $sql_count = "SELECT COUNT(*) AS total FROM post";
                $result_count = $conn->query($sql_count);
                $row_account = $result_count->fetch_assoc();
                $total_results = $row_account['total'];
                $total_pages = ceil($total_results/$results_per_page);

                $sql = "SELECT * FROM post ORDER BY `id-post` ASC LIMIT $start_from, $results_per_page";
                $result = $conn->query($sql);
                if ($result) {
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<div class="flex flex-col calc-width-3-col border-normal shadow-md">';
                                echo '<img class="post-img object-fit-cover" src="' . htmlspecialchars($row["img-post"]) . '">'; 
                                echo '<div class="flex flex-col row-gap-2 my-2 px-4 text-left">';
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
                    } else {
                        echo 'Không tìm thấy bài viết phù hợp.';
                    }
                } else {
                    echo 'Lỗi truy vấn: '. $conn->error;
                }
            echo '</div>';

            echo '<div>';
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
        <!---------------------------------- SIDEBAR 
            <div class="sidebar flex flex-col w-30 row-gap-5 px-4 py-6 border-normal background-gray">
                <form class="flex flex-row column-gap-1 justify-content-center">
                    <input type="text" class="w-80 px-4 py-4 border-normal background-white" placeholder="Tìm kiếm">
                    <button type="submit" class="button-primary px-4 py-4 w-20">Tìm</button>
                </form>
                <div class="flex flex-col row-gap-2">
                    <h6>CHUYÊN MỤC</h6>
                
                    $sql = "SELECT * FROM category ORDER BY `name-category` ASC";
                    $result = $conn->query($sql);
                    
                    if ($result) {
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<i class="icon fa-solid fa-circle-notch"></i><li>' . htmlspecialchars($row['name-category']) . '</li>';
                            }
                        } else {
                            echo 'Không tìm thấy chuyên mục phù hợp';
                        } 
                    } else {
                        echo 'Lỗi truy vấn: ' . $conn->error;
                    }
                    ?>
                </div>
            </div>--------------------------------------->
        </div>
    </main>
    <?php include "footer.php" ?>
</body>

<?php
    // Đóng kết nối
    $conn->close();
?>

</html>