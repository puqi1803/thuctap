<?php
include '../partical/check-login.php';
include '../partical/db_connect.php';
include '../includes/functions.php';

if (isset($_GET['id-post'])) {
    $id_post = $_GET['id-post'];

    $sql = "SELECT * FROM post WHERE `id-post` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_post);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0 ) {
        $post = $result->fetch_assoc();
    } else {
        echo 'Bài viết không tồn tại.';
        exit;
    }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title_post = $_POST['title-post'];
    $date_post = $_POST['date-post'];
    $slug_post = $_POST['slug-post'];
    $expert_post = $_POST['expert-post'];
    $content_post = $_POST['content-post'];
    $img_post = $post['img-post'];

    if (isset($_FILES['img-post']) && $_FILES['img-post']['error'] == 0) {
        include '../includes/check-image.php';
    }

    $status_post = (isset($_POST['Draft']) && $_POST['Draft'] === 'true') ? 'Draft' : 'Published';

    $sql = "UPDATE post SET
        `title-post` = ?,
        `img-post` = ?,
        `date-post` = ?,
        `slug-post` = ?,
        `expert-post` = ?,
        `content-post` = ?,
        `status-post` = ?
    WHERE `id-post` = ?";
    }

    $stmt = $conn->prepare($sql);
    if (isset($title_post, $date_post, $slug_post, $expert_post, $content_post, $id_post)) {
        $stmt->bind_param("sssssssi",
            $title_post,
            $img_post,
            $date_post,
            $slug_post,
            $expert_post,
            $content_post,
            $status_post,
            $id_post
        );

        if ($stmt->execute()) {
            header("Location: admin-edit-post?id-post=$id_post");
            exit();
        } else {
            echo 'Lỗi: ' . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Sửa bài viết</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="description" content=""/>
    <meta http-equiv="refresh" content="900">
</head>
<body>
    <?php include '../header-blank.php'; ?> 
    <div class="container-fluid taskbar">
        <div class="container d-flex flex-row py-2 px-4 justify-content-between">
            <div class="d-flex flex-row column-gap-4">
                <button class="button-none"
                    onclick= "if(confirm('Bạn sẽ rời khỏi trang này khi bài viết chưa được lưu. Bạn chắc chắn chứ?'))
                    {window.location.href='admin?page=admin-post';}">
                    <i class="icon fa-solid fa-arrow-left"></i>&nbsp;&nbsp;Trở về
                </button>
            </div>
            <div class="d-flex flex-row column-gap-4"> 
                <a href="../single-post?slug-post=<?php echo htmlspecialchars($post['slug-post']);?>" target="_blank"><i class="icon fa-solid fa-eye"></i>  Xem</a>
            </div>
        </div>
    </div>
    <main class="container admin-edit-post">
        <form method="POST" enctype="multipart/form-data">
            <div class="row mt-5 column-gap-4">
                <div>
                    <h5>Tiêu đề</h5>
                    <h4><textarea class="title-post p-4" rows="2" type="text" name="title-post"><?php echo htmlspecialchars($post['title-post']); ?></textarea></h4>
                </div>
                <div class="col-8 d-flex flex-column mt-4 row-gap-4">
                    <div class="d-flex flex-column row-gap-2">
                        <div>
                            <label for="expert-post">Mô tả</label>
                            <textarea type="text" rows="10" id="expert-post" name="expert-post"><?php echo htmlspecialchars($post['expert-post']); ?></textarea>
                        </div>
                        <div>
                            <label for="content-post">Nội dung</label>
                            <textarea type="text" rows="10" id="content-post" name="content-post"><?php echo htmlspecialchars($post['content-post']); ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="col d-flex flex-column mt-4 row-gap-4">
                    <div class="d-flex flex-column row-gap-2">
                        <label for="img-post">Ảnh</label>
                        <?php if (!empty($post['img-post'])) : ?>
                            <img id="old-image" src="../resources/uploads/<?php echo htmlspecialchars($post['img-post']);?>" alt="<?php echo htmlspecialchars($post['img-post']); ?>">
                        <?php endif; ?>
                        <div id="image-preview"></div>
                        <input type="file" id="img-post" name="img-post" accept="image/*" onchange="previewImage(event)">
                    </div>
                    <div class="d-flex flex-column row-gap-2">
                        <div>
                            <label for="date-post">Ngày xuất bản</label>
                            <input type="date" id="date-post" name="date-post" value="<?php echo date($post['date-post']); ?>">
                        </div>
                        <div>
                            <label for="slug-post">Liên kết</label>
                            <input type="text" id="slug-post" name="slug-post" value="<?php echo htmlspecialchars($post['slug-post']); ?>" required>
                        </div>
                        <div>
                            <a class="note link" href="../single-post?slug-post=<?php echo htmlspecialchars($post['slug-post']);?>">nienluan.com/single-post?slug-post=<?php echo htmlspecialchars($post['slug-post']); ?></a>
                        </div>
                    </div>
                    <div class="d-flex flex-row column-gap-4 justify-content-end"> 
                        <button class="button-light-background px-3 py-2" type="submit" name="Draft" value="true">
                            <i class="icon fa-solid fa-file-alt"></i>&nbsp;&nbsp;Lưu nháp
                        </button>
                        <button class="button-primary px-3 py-2" type="submit" name="submit">
                            <i class="icon fa-solid fa-floppy-disk"></i>&nbsp;&nbsp;Phát hành
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </main>
    <script>
        function previewImage(event) {
            const imagePreview = document.getElementById('image-preview');
            const oldImage = document.getElementById('old-image');
            if (oldImage) {
                oldImage.style.display = 'none';
            }
            imagePreview.innerHTML = '';
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = 'Thumbnail';
                    img.style.maxWidth = '200px';
                    img.style.maxHeight = '150px';
                    img.style.objectFit = 'cover';
                    imagePreview.appendChild(img);
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>
<?php
    // Đóng kết nối
    $conn->close();
?>

</html>

