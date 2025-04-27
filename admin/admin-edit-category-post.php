<?php
include '../includes/check-login.php';
include '../partical/db_connect.php';
include '../includes/functions.php';

if (isset($_GET['id-category-post'])) {
    $id_category_post = $_GET['id-category-post'];
    $sql = "SELECT * FROM `category-post` WHERE `id-category-post` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_category_post);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0 ) {
        $category_post = $result->fetch_assoc();
        }
    } else {
        echo 'Bài viết không tồn tại.';
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name_category_post = $_POST['name-category-post'];
        $slug_category_post = $_POST['slug-category-post'];
        $slug_post = createSlugCategory($slug_category_post);
        $description_category_post = $_POST['description-category-post'];

        $sql = "UPDATE `category-post` SET
            `name-category-post` = ?,
            `slug-category-post` = ?,
            `description-category-post` = ?
            WHERE `id-category-post` = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi",
            $name_category_post,
            $slug_post,
            $description_category_post,
            $id_category_post
            );

        if ($stmt->execute()) {
            header("Location: admin-edit-category-post?id-category-post=$id_category_post");
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
    <title>Sửa chuyên mục</title>
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
                    {window.location.href='admin?page=admin-category-post';}">
                    <i class="icon fa-solid fa-arrow-left"></i>&nbsp;&nbsp;Trở về
                </button>
            </div>
        </div>
    </div>
    <main class="container admin-edit-category-post" style="max-width: 700px; padding: 40px 0px">
    <form method="POST" enctype="multipart/form-data">
        <div class="row row-gap-2">
            <div>
                <label for="name-category-post">Tên chuyên mục</label>
                <input type="text" id="name-category-post" name="name-category-post"
                value="<?php echo htmlspecialchars($category_post['name-category-post']) ?>"> 
            </div>
            <div>
                <label for="slug-category-post">Đường dẫn</label>
                <input type="text" id="slug-category-post" name="slug-category-post"
                value="<?php echo htmlspecialchars($category_post['slug-category-post']) ?>"> 
            </div>
            <div>
                <label for="description-category-post">Mô tả</label>
                <textarea id="description-category-post" name="description-category-post" rows="5"><?php echo htmlspecialchars($category_post['description-category-post']); ?></textarea>
                </textarea>
            </div>  
            <div class="d-flex justify-content-end">
                <button class="button-primary px-3 py-2" type="submit" name="submit">
                    <i class="icon fa-solid fa-floppy-disk"></i>&nbsp;&nbsp;Lưu
                </button>
            </div>
        </div>
    </form>
    </main>
</body>
<?php
    // Đóng kết nối
    $conn->close();
?>

</html>

