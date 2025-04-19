<?php
include 'partical/db_connect.php';
include 'includes/functions.php';

$slug = isset($_GET['slug-post']) ? $_GET['slug-post'] : '';
$status_post = 'Published';

$sql = "SELECT * FROM post WHERE `slug-post` = ? AND `status-post` = ?"; 
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $slug, $status_post);
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
    <link rel="stylesheet" href="resources/style.css">
    <meta http-equiv="refresh" content="900">
</head>

<body>
    <?php include "header-main.php" ?>
    <main class="single-post container">
        <?php if ($post) : ?>
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb accent">
                    <li class="breadcrumb-item"><a href="/nienluan.com/">Trang Chủ</a>
                    <li class="breadcrumb-item"><a href="/nienluan.com/tin-tuc">Tin Tức</a>
                    <li class="breadcrumb-item active" breadcrumb-item active="page"><?php echo htmlspecialchars($post['title-post']); ?></li>
                </ol>
            </nav>
            <div class="single-post-content my-5">
                <div class="title-page">
                    <h3><?php echo htmlspecialchars($post['title-post']); ?></h3>
                </div>
                <p class="expert-post mt-4"> <?php echo htmlspecialchars($post['expert-post']); ?></p>
                <div class="d-flex flex-row mt-4 column-gap-2 align-items-center">
                    <i class="icon fa-regular fa-clock"></i>
                    <p class="date-post accent"><?php echo formatDate($post["date-post"]); ?></p>
                </div>
                <img class="post-img mt-4 w-100 object-fit-cover" src="resources/uploads/<?php echo htmlspecialchars($post['img-post']); ?>">
                <p class="mt-5"><?php echo nl2br(html_entity_decode($post['content-post'])); ?></p>
            <?php else : ?>
                <p>Bài viết không tồn tại.</p>
            <?php endif; ?>
            </div>

        <div class="other-posts my-5">
            <h3 class="title-page">CÁC TIN KHÁC</h3> 
            <div class="row mt-4">
            <?php
            $sql_posts = "SELECT * FROM post WHERE `status-post`=? AND `slug-post`!=? ORDER BY `id-post` ASC LIMIT 4";
                $stmt_post = $conn->prepare($sql_posts);

                if($stmt_post) {
                    $stmt_post->bind_param("ss", $status_post, $slug);
                    $stmt_post->execute();
                }

                $result_posts = $stmt_post->get_result();

                if ($result_posts) {
                    if ($result_posts->num_rows > 0) {
                    while ($row = $result_posts->fetch_assoc()) {
                        echo '<div class="col-3 ">';
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