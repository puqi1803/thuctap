<?php
include '../partical/db_connect.php';
include '../includes/functions.php';
include '../includes/delete.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Quản lý chuyên mục</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="description" content=""/>
    <meta http-equiv="refresh" content="900">
</head>

<body>
    <?php
        include '../header-blank.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name_category_post = $_POST['name-category-post'];
            $slug_category_post = $_POST['slug-category-post'];
            $slug_post = createSlugCategory($name_category_post);
            $description_category_post = $_POST['description-category-post'];
    
        $sql = "INSERT INTO `category-post` (
            `name-category-post`,
            `slug-category-post`,
            `description-category-post`
            ) VALUES (?, ?, ?)";
    
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss",
            $name_category_post,
            $slug_post,
            $description_category_post
            );
    
        if ($stmt->execute()) {
            $id_category_post = $conn->insert_id;
            header("Location: ?page=admin-category-post");
            exit();
        } else {
            echo 'Lỗi: ' . $stmt->error;
        }
        $stmt->close();
        }

        //Phan trang
        $results_per_page = 5;

        $page = isset($_GET['category-post-page']) ? (int)($_GET['category-post-page']) : 1;
        if ($page < 1) {
            $page = 1;
        }
        $start_form = ($page-1) * $results_per_page;

        $sql_count = "SELECT COUNT(*) AS total FROM `category-post` WHERE 1=1";
        $result_count = $conn->query($sql_count);
        $row_count = $result_count->fetch_assoc();
        $total_results =$row_count['total'];
        $total_pages = ceil($total_results/$results_per_page);

        $sql = "SELECT * FROM `category-post` ORDER BY `name-category-post` ASC LIMIT $start_form, $results_per_page";
        $result = $conn->query($sql);
    ?>
    <main class="category-post">
        <h3 class="title-page">Chuyên mục</h3>
        <!---
        <div class="col justify-content-end">
            <form class="d-flex flex-row column-gap-2 align-items-center justify-content-end">
                <input class="p-2 border-round w-25" type="text">
                <button class="button-light-background p-2" type="submit">Tìm</button>
            </form>
        </div> --->
        <hr>
        <div class="d-flex flex-row column-gap-6">
            <div class="col-3">
            <form method="POST" enctype="multipart/form-data">
                <div class="row row-gap-2">
                    <div>
                        <label for="name-category-post">Tên chuyên mục</label>
                        <input type="text" id="name-category-post" name="name-category-post">
                    </div>
                    <div>
                        <label for="slug-category-post">Đường dẫn</label>
                        <input type="text" id="slug-category-post" name="slug-category-post">
                    </div>
                    <div>
                        <label for="description-category-post">Mô tả</label>
                        <textarea type="text" rows="5" id="description-category-post" name="description-category-post"></textarea>
                    </div>  
                    <div class="d-flex justify-content-end">
                        <button class="button-primary px-3 py-2" type="submit" name="submit">
                            <i class="icon fa-solid fa-floppy-disk"></i>&nbsp;&nbsp;Lưu
                        </button>
                    </div>
                </div>
            </form>
            </div>

            <!--- Category --->
            <div class="col d-flex flex-column row-gap-2">
                <!--- Filter --->
                <div class="d-flex flex-row column-gap-8 align-items-center">
                    <!---Action--->
                    <div class="col-8">
                        <form class="d-flex flex-row column-gap-2">
                            <select class="w-25" id="action-category-post" name="action-category-post">
                                <option value="" disable selected>Thao tác</option>
                                <option value="delete">Xóa</option>
                            </select>
                            <button class="button-light-background p-2" type="submit" onclick="return confirmAction();">Thực hiện</button>
                        </form>
                    </div>
                    <!---Phan trang --->
                    <div class="col">
                        <?php
                        echo '<div class="pagination-admin d-flex flex-row column-gap-4 justify-content-end">';
                        if ($page > 1) {
                            echo '<a href="?page=admin-category-post&category-post-page=' . ($page - 1) . '">«</a>';
                        }
                        for ($i = 1; $i <= $total_pages; $i++) {
                            if ($total_pages == 1) {
                                echo '';
                            }
                            else if ($i == $page) {
                                    echo '<span class="accent">' . $i .'</span>';
                            } else {
                                echo '<a class="number" href="?page=admin-category-post&category-post-page=' . $i . '">' . $i . '</a>';
                            }
                        }
                        if ($page < $total_pages) {
                            echo '<a href="?page=admin-category-post&category-post-page=' . ($page + 1) . '">»</a>';
                        }
                        echo '</div>';  
                        ?>
                    </div>
                </div>

                <!--- Show Category --->    
                <div>
                    <form id="delete-form" method="POST">
                        <input type="hidden" name="deleted-category-post" value="1">
                        <div class="mt-2">
                            <table class="table table-striped">
                                <thead>
                                    <tr class="text-center text-uppercase">
                                        <th scope="col" class="col"><input type="checkbox" id="select-all"></th>
                                        <th scope="col" class="col-3">Tên chuyên mục</th>
                                        <th scope="col" class="col">Đường dẫn</th>
                                        <th scope="col" class="col-3">Mô tả</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                if ($result) {
                                    if ($result->num_rows > 0) {
                                        while ($category_post = $result->fetch_assoc()) {
                                            echo '<tr class="text-center">';
                                                echo '<td><input type="checkbox" name="categories_post[]" value="'
                                                . htmlspecialchars($category_post['id-category-post']) . '" class="category-post-select"></td>';
                                                if (!empty($category_post['name-category-post'])) {
                                                    echo '<td><a class="accent link" href="admin-edit-category-post?id-category-post=' . htmlspecialchars($category_post['id-category-post']) . '">'
                                                        . htmlspecialchars($category_post['name-category-post']) . '  </a></td>';
                                                } else {
                                                    echo '<td></td>';
                                                }
                                                echo '<td>' . (!empty($category_post['slug-category-post']) ? htmlspecialchars($category_post['slug-category-post']) : '') . '</td>'; 
                                                $description = htmlspecialchars($category_post['description-category-post']);
                                                $short_description = truncateExpertShort($description);
                                                echo '<td>' . (!empty($category_post['description-category-post']) ? htmlspecialchars($short_description) : '') . '</td>';
                                            echo '</tr>';
                                            }
                                        } else {
                                            echo '<tr><td colspan="6">Không tìm thấy kết quả phù hợp</td></tr>';
                                        }
                                    } else {
                                        echo '<tr><td colspan="6">Lỗi: ' . $conn->error . '</td></tr>'; 
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>

                <!--- Phan trang --->
                <div>
                <?php 
                echo '<div class="d-flex flex-row column-gap-5 justify-content-between">';
                    echo '<p class="accent">Tổng: ' . $total_results . ' mục</p>';
                    echo '<div class="pagination-admin d-flex flex-row column-gap-4">';
                        if ($page > 1) {
                            echo '<a href="?page=admin-category-post&category-post-page=' . ($page - 1) . '">«</a>';
                        }
                        for ($i = 1; $i <= $total_pages; $i++) {
                            if ($total_pages == 1) {
                                echo '';
                            }
                            else if ($i == $page) {
                                echo '<span class="accent">' . $i .'</span>';
                            } else {
                                echo '<a class="number" href="?page=admin-category-post&category-post-page=' . $i . '">' . $i . '</a>';
                            }
                        }
                        if ($page < $total_pages) {
                            echo '<a href="?page=admin-category-post&category-post-page=' . ($page + 1) . '">»</a>';
                        }
                    echo '</div>';
                echo '</div>';  
                ?>
                </div>
            </div>
        </div>
    </main>
    <script>
        document.getElementById('select-all').onclick = function() {
            var checkboxes = document.getElementsByClassName('category-post-select');
            for (var checkbox of checkboxes) {
                checkbox.checked = this.checked;
            }
        };
        function confirmAction() {
            const action = document.getElementById("action-category-post").value;
            if (action === "delete") {
                return confirm("Bạn có chắc chắn muốn xóa không?");
            }
            return false;
        }
    </script>  
</body>
<?php
    // Đóng kết nối
    $conn->close();
?>

</html>