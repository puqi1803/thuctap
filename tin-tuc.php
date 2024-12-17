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
    <meta http-equiv="refresh" content="900">
</head>

<body>
    <?php 
        include "header-main.php";
        $results_per_page = 6;

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $start_from = ($page-1) * $results_per_page;

        $sql_count = "SELECT COUNT(*) AS total FROM post WHERE `status-post` = 'Published'";
        $result_count = $conn->query($sql_count);
        $row_account = $result_count->fetch_assoc();
        $total_results = $row_account['total'];
        $total_pages = ceil($total_results/$results_per_page);

        $sql = "SELECT * FROM post WHERE `status-post` = 'Published' ORDER BY `id-post` DESC LIMIT $start_from, $results_per_page";
        $result = $conn->query($sql);
    ?>
    <main class="tin-tuc container">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb accent">
                <li class="breadcrumb-item"><a href="/nienluan.com/">Trang Chủ</a>
                <li class="breadcrumb-item active" breadcrumb-item active="page"><?php echo htmlspecialchars($pageTitle); ?></li>
            </ol>
        </nav>
        <div class="title-page mt-5 text-center">
            <h1><?php echo htmlspecialchars($pageTitle); ?></h1>
        </div>
        <!---------------------------------- BAI VIET --------------------------------------->
        <div class="post row mt-4">
            <?php
            if ($result) {
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="col-4 mb-4">';
                            echo '<div class="d-flex flex-column border-round shadow-sm">';
                                echo '<img class="post-img object-fit-cover" src="resources/uploads/' . htmlspecialchars($row["img-post"]) . '">'; 
                                echo '<div class="post-content flex flex-col row-gap-2 p-4 text-left">';
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

            echo '<div class="pagination d-flex flex-row mt-5 column-gap-4 justify-content-center align-items-center">';
                if ($page > 1) {
                    echo '<a href="?page=' . ($page - 1) . '">« Trang trước</a>';
                } else {
                    echo '<span class="disable">« Trang trước</span>';
                }
                for ($i = 1; $i <= $total_pages; $i++) {
                    if ($i == $page) {
                        echo '<span class="accent">' . $i . '</span>';
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
    </main>
    <?php include "footer.php" ?>
</body>

<?php
    // Đóng kết nối
    $conn->close();
?>

</html>