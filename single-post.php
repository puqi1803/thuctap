<?php
include 'partical/db_connect.php';
include 'includes/functions.php';

$slug = isset($_GET['slug-post']) ? $_GET['slug-post'] : '';

$sql = "SELECT * FROM post WHERE `slug-post` = ?"; 
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $slug);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();
$stmt->close();

?>  
<!DOCTYPE html>
<html lang="vi">
<head>
    <title>
        <?php
            echo isset($post['title-post']) ? htmlspecialchars($post['title-post']) : 'Bài viết không tồn tại';
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
    <main class="container single-post">
        <?php if ($post) : ?>
            <div class="breadcrumb accent flex flex-row mt-4 column-gap-2">
                <li><a href="/nienluan.com/">Trang Chủ</a></li>
                <li><a href="/nienluan.com/tin-tuc">Tin tức</a></li>
                <li><a class="current"><?php echo htmlspecialchars($post['title-post']); ?></a></li>
            </div>
            <div class="title-page flex flex-col mt-6">
                <h3><?php echo htmlspecialchars($post['title-post']); ?></h3>
            </div>
            <p class="expert-post mt-4"> <?php echo htmlspecialchars($post['expert-post']); ?></p>
            <div class="flex flex-row mt-4 column-gap-2 align-items-center">
                <i class="icon fa-regular fa-clock"></i>
                <p class="date-post"><?php echo formatDate($post["date-post"]); ?></p>
            </div>
            <img class="img-post mt-4 w-full object-fit-cover" src="<?php echo htmlspecialchars($post['img-post']); ?>">
            <p class="mt-8"><?php echo nl2br(htmlspecialchars($post['content-post'])); ?></p>
        <?php else : ?>
            <p>Bài viết không tồn tại.</p>
        <?php endif; ?>

        <div class="other-posts flex flex-col mt-8">
            <h3 class="title-page">CÁC TIN KHÁC</h3> 
            <div class="flex flex-wrap mt-4 row-gap-2 justify-content-space-between">
            <?php
            $sql = "SELECT * FROM post ORDER BY `id-post` ASC LIMIT 4";
                $result = $conn->query($sql);
                if ($result) {
                    if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                            echo '<div class="flex flex-col calc-width-4-col border-normal shadow-md">';
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
            ?>
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