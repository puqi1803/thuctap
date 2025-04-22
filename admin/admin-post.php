<?php
include '../partical/db_connect.php';
include '../includes/functions.php';
include '../includes/delete.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Quản lý bài viết</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="description" content=""/>
    <meta http-equiv="refresh" content="900">
</head>

<body>
    <?php
        include '../header-blank.php';

        $results_per_page = 10;

        $page = isset($_GET['post-page']) ? (int)($_GET['post-page']) : 1;
        if ($page < 1) {
            $page = 1;
        }
        $start_form = ($page-1) * $results_per_page;

        $sql_count = "SELECT COUNT(*) AS total FROM post";
        $result_count = $conn->query($sql_count);
        $row_count = $result_count->fetch_assoc();
        $total_results =$row_count['total'];
        $total_pages = ceil($total_results/$results_per_page);

        $sql = "SELECT * FROM post ORDER BY `id-post` DESC LIMIT $start_form, $results_per_page";
        $result = $conn->query($sql);
    ?>
    <main class="admin-post">
        <h3 class="title-page">Bài viết</h3>
        <!--- Function delete, add new --->
        <div class="row justify-content-between mt-4">
            <div class="col d-flex flex-row column-gap-2 align-items-center">
                <button class="button-light-background p-2 px-4" onclick="window.open('admin-new-post', '_blank')">Thêm mới</button>
                <button class="button-light-background p-2 px-4" type="submit" form="delete-form" onclick="return confirm('Bạn có chắc chắn muốn xóa không?');">Xóa</button>
            </div>
            <div class="col d-flex flex-row column-gap-2 align-items-center justify-content-end">
                <form>
                    <input class="p-2 border-round" type="text">
                    <button class="button-light-background p-2" type="submit">Tìm</button>
                </form>
            </div>
        </div>
        <!--- Filter --->
        <div class="row justify-content-between mt-4">
            <div class="col-9 d-flex flex-row column-gap-2 align-items-center">
                <form method="GET" action="admin.php">
                    <input type="hidden" name="page" value="admin-post">
                    <input type="hidden" name="category-post" value="<?php echo htmlspecialchars($rq_category_post); ?>">
                    <input type="hidden" name="date-post" value="<?php echo htmlspecialchars($rq_date); ?>">
                    <input class="col p-2 border-round" type="date" value="" id="date-tour" name="date-tour"></input>
                    <?php 
                    echo '<select id="category-post" name="category-post" class="px-2 py-2 border-accent">';
                    echo '<option value="">Chuyên mục</option>';
                        $sql_category= "SELECT * FROM `category-post`;";
                        $result_name_category = $conn->query($sql_category);
                        if ($result_name_category) {
                            if ($result_name_category->num_rows > 0) {
                                while ($category_post = $result_name_category->fetch_assoc()) {
                                    echo '<option value="' . htmlspecialchars($category_post['name-category']) . '">'
                                        . htmlspecialchars($category_post['name-category'])
                                        . '</option>';
                                }
                            }
                        }
                    echo '</select>';
                    ?>
                    <select id="sort" name="sort" class="px-2 py-2 border-accent">
                        <option value="">Sắp xếp</option>
                        <option value="gia-thap-den-cao"
                            <?php echo (isset($_GET['sort']) && $_GET['sort']==='gia-thap-den-cao') ? 'selected' : ''; ?>>Giá từ thấp đến cao
                        </option>
                        <option value="gia-cao-den-thap"
                            <?php echo (isset($_GET['sort']) && $_GET['sort']==='gia-cao-den-thap') ? 'selected' : ''; ?>>Giá từ cao đến thấp
                        </option>    
                    </select>
                    <button class="px-4 button-light-background p-2" type="submit">Lọc</button>
                </form>
            </div>
        </div>
        <?php 
        //Phan trang
        echo '<div class="d-flex flex-row mt-5 column-gap-5 justify-content-between">';
            echo '<p class="accent">Tổng: ' . $total_results . ' mục</p>';
            echo '<div class="pagination-admin d-flex flex-row column-gap-4">';
                if ($page > 1) {
                    echo '<a href="?page=admin-post&post-page=' . ($page - 1) . '">«</a>';
                } else {
                    echo '<span class="disable">«</span>';
                }
                for ($i = 1; $i <= $total_pages; $i++) {
                    if ($i == $page) {
                        echo '<span class="accent">' . $i .'</span>';
                    } else {
                        echo '<a class="number" href="?page=admin-post&post-page=' . $i . '">' . $i . '</a>';
                    }
                }
                if ($page < $total_pages) {
                    echo '<a href="?page=admin-post&post-page=' . ($page + 1) . '">»</a>';
                } else {
                    echo '<span class="disable">»</span>';
                }
            echo '</div>';
        echo '</div>';  
        ?>
        <hr>
        <form id="delete-form" method="POST">
        <input type="hidden" name="deleted-post" value="1">
        <div class="mt-2">
            <table class="table table-striped">
                <thead>
                    <tr class="text-center text-uppercase">
                        <th scope="col" class="col"><input type="checkbox" id="select-all"></th>
                        <th scope="col" class="col-3">Tên bài viết</th>
                        <th scope="col" class="col">Ngày phát hành</th>
                        <th scope="col" class="col-3">Mô tả</th>
                        <th scope="col" class="col">Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                if ($result) {
                    if ($result->num_rows > 0) {
                        while ($post = $result->fetch_assoc()) {
                            echo '<tr class="text-center">';
                                echo '<td><input type="checkbox" name="posts[]" value="' . htmlspecialchars($post['id-post']) . '" class="post-select"></td>';
                                echo '<td><div class="text-start">';
                                    $title = htmlspecialchars($post['title-post']);
                                    $shortTitle = truncateTitle($title);
                                    echo '<a class="accent link" href="admin-edit-post?id-post=' . htmlspecialchars($post['id-post']) . '">' . $shortTitle . '  </a>';
                                    echo '<a href="../single-post?slug-post=' . htmlspecialchars($post['slug-post']) . '" target="_blank"><i class="icon fa-solid fa-eye"></i></a>';
                                echo '</td></div>'; 
                                echo '<td>' . formatDate($post['date-post']) . '</td>';
                                $expert = htmlspecialchars($post['expert-post']);
                                $shortExpert = truncateExpertShort($expert);
                                echo '<td>' . htmlspecialchars($shortExpert) . '</td>';
                                echo '<td>' . htmlspecialchars($post['status-post']) . '</td>';
                            echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="5">Không tìm thấy kết quả phù hợp</td></tr>';
                        }
                    } else {
                        echo '<tr><td colspan="5">Lỗi: ' . $conn->error . '</td></tr>'; 
                    }
                    ?>
                </tbody>
            </table>
        </div>
        </form>
        <hr>
        <?php 
        //Phan trang
        echo '<div class="d-flex flex-row mt-5 column-gap-5 justify-content-between">';
            echo '<p class="accent">Tổng: ' . $total_results . ' mục</p>';
            echo '<div class="pagination-admin d-flex flex-row column-gap-4">';
                if ($page > 1) {
                    echo '<a href="?page=admin-post&post-page=' . ($page - 1) . '">«</a>';
                } else {
                    echo '<span class="disable">«</span>';
                }
                for ($i = 1; $i <= $total_pages; $i++) {
                    if ($i == $page) {
                        echo '<span class="accent">' . $i .'</span>';
                    } else {
                        echo '<a class="number" href="?page=admin-post&post-page=' . $i . '">' . $i . '</a>';
                    }
                }
                if ($page < $total_pages) {
                    echo '<a href="?page=admin-post&post-page=' . ($page + 1) . '">»</a>';
                } else {
                    echo '<span class="disable">»</span>';
                }
            echo '</div>';
        echo '</div>';  
        ?>
    </main>
    <script>
        document.getElementById('select-all').onclick = function() {
            var checkboxes = document.getElementsByClassName('post-select');
            for (var checkbox of checkboxes) {
                checkbox.checked = this.checked;
            }
        };
    </script>  
</body>
<?php
    // Đóng kết nối
    $conn->close();
?>

</html>