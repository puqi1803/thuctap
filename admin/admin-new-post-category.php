<?php
    include '../includes/check-login.php';
    include '../partical/db_connect.php';
    include '../includes/functions.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title_post = $_POST['title-post'];
        $date_post = $_POST['date-post'];
        $slug_post = createSlug($title_post);
        $expert_post = $_POST['expert-post'];
        $content_post = $_POST['content-post'];
        $img_post = null;
        $id_categories = $_POST['id-category-post'];
        $id_status_post = $_POST['id-status-post'];

    include '../includes/check-image.php';

    //$status_post = (isset($_POST['Draft']) && $_POST['Draft'] === 'true') ? 'Draft' : 'Published'; 

    $sql = "INSERT INTO post (
        `title-post`,
        `img-post`,
        `date-post`,
        `slug-post`,
        `author-post`,
        `expert-post`,
        `content-post`,
        `id-status-post`
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi",
        $title_post,
        $img_post,
        $date_post,
        $slug_post,
        $expert_post,
        $content_post,
        $id_status_post
        );

        
        if ($stmt->execute()) {
            $id_post = $conn->insert_id;

            $sql_category = "INSERT INTO `detail-category-post` (`id-post`, `id-category-post`) VALUES (?, ?)";
            $stmt_category = $conn->prepare($sql_category);

            foreach ($id_categories as $id_category_post) {
                $stmt_category->bind_param("ii",
                    $id_post,
                    $id_category_post
                );
                $stmt_category->execute();
            }
            $stmt_category->close();

            header("Location: admin-edit-post?id-post= $id_post");
            exit();
        } else {
            echo 'Lỗi: ' . $stmt->error;
        }
        $stmt->close();
    }
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Thêm bài viết</title>
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
            <a href="/nienluan.com/"><i class="icon fa-solid fa-house"></i>  Trang chủ</a>
        </div>
    </div>
    </div>
    <main class="container admin-new-post">
        <form method="POST" enctype="multipart/form-data">
            <div class="row mt-5 column-gap-4">
                <div>
                    <h5>Tiêu đề</h5>
                    <h4><textarea class="title-post p-4" rows="2" type="text" name="title-post"></textarea></h4>
                </div>
                <div class="col-8 d-flex flex-column mt-4 row-gap-4">
                    <div class="d-flex flex-column row-gap-2">
                        <div>
                            <label for="expert-post">Mô tả</label>
                            <textarea type="text" rows="10" id="expert-post" name="expert-post"></textarea>
                        </div>
                        <div>
                            <label for="content-post">Nội dung</label>
                            <textarea type="text" rows="10" id="content-post" name="content-post"></textarea>
                        </div>
                    </div>
                </div>
                <div class="col d-flex flex-column mt-4 row-gap-4">
                    <div class="d-flex flex-column row-gap-2">
                        <label for="img-post">Ảnh</label>
                        <div id="image-preview"></div>
                        <input type="file" id="img-post" name="img-post" accept="image/*" onchange="previewImage(event)">
                    </div>
                    <div class="d-flex flex-column row-gap-2">
                        <div>
                            <label for="date-post">Ngày xuất bản</label>
                            <input  type="date" id="date-post" name="date-post" value="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div>
                            <label for="slug-post">Liên kết</label>
                            <input type="text" id="slug-post" name="slug-post">
                        </div>
                        <div class="d-flex flex-column row-gap-2"> 
                            <label for="img-post">Chuyên mục</label>
                            <div style="overflow-x: auto; max-height: 200px;" class="border-round p-2">
                            <?php
                            $sql_name_category = "SELECT * FROM `category-post`";
                            $result_category_post = $conn->query($sql_name_category);
                            if($result_category_post && $result_category_post->num_rows > 0) {
                                while($name_category = $result_category_post->fetch_assoc()) {
                                    echo '<div class="d-flex flex-row mt-2 column-gap-2">';
                                    echo '<input style="width: 10%" type="checkbox" id="id-category-post-' . htmlspecialchars($name_category['id-category-post'])
                                        . '" name="id-category-post[]" value="' . htmlspecialchars($name_category['id-category-post']) . '">';
                                    echo '<label for="id-category-post-' . htmlspecialchars($name_category['id-category-post']) . '">'
                                        . htmlspecialchars($name_category['name-category-post']) . '<label>';
                                    echo '</div>';
                                }
                            }
                            ?>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-between mt-4">
                        <div class="col-8">
                            <select id="id-status-post" name="id-status-post">
                                <option value="3">Phát hành</option>
                                <option value="2">Chờ duyệt</option>
                                <option value="1">Nháp</option>
                            </select>
                        </div>
                        <div class="col-4">
                            <button class="button-primary px-3 py-2 w-100" type="submit" name="submit">
                                <i class="icon fa-solid fa-floppy-disk"></i>&nbsp;&nbsp;Lưu
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </main>
    <script>
        function previewImage(event) {
            const imagePreview = document.getElementById('image-preview');
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
            }
        }
    </script>
</body>
<?php
    // Đóng kết nối
    $conn->close();
?>

</html>