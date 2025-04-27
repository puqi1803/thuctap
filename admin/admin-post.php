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

        //Truy van loc
        $category_post = $_GET['category-post'] ?? '';
        $rq_date = $_GET['date-post'] ?? '';
        $order_by = 'ORDER BY `created-at` DESC';
        $status_post = $_GET['status-post'] ?? '';

        //Xay dung truy van
        $sql = "SELECT * FROM post WHERE 1=1"
        ;
        /*if ($rq_date) {
            $sql .= " AND `date-post` = '" . $conn->real_escape_string($rq_date) . '";
        }*/
        if ($category_post) {
            $sql_id_category_post = "SELECT `id-category-post` FROM `category-post` WHERE `name-category-post` = '" . $conn->real_escape_string($category_post) . "'";
            $result_id_category_post = $conn->query($sql_id_category_post);
            if ($result_id_category_post && $result_id_category_post->num_rows > 0) {
                $row = $result_id_category_post->fetch_assoc();
                $id_category_post = $row['id-category-post'];
            }
            $sql .= " AND `id-category-post` = $id_category_post";
        }
        if ($status_post) {
            $sql_id_status_post = "SELECT `id-status` FROM `status` WHERE `name-status` = '" . $conn->real_escape_string($status_post) . "'";
            $result_id_status_post = $conn->query($sql_id_status_post);
            if ($result_id_status_post && $result_id_status_post->num_rows > 0) {
                $row = $result_id_status_post->fetch_assoc();
                $id_status_post = $row['id-status'];
            }
            $sql .= " AND `id-status-post` = $id_status_post";
        }

        //Phan trang
        $results_per_page = 10;

        $page = isset($_GET['post-page']) ? (int)($_GET['post-page']) : 1;
        if ($page < 1) {
            $page = 1;
        }
        $start_form = ($page-1) * $results_per_page;

        $sql_count = "SELECT COUNT(*) AS total FROM post WHERE 1=1";
        if ($category_post) {
            $sql_count .= " AND `id-category-post` = $id_category_post";
        }
        if ($status_post) {
            $sql_count .= " AND `id-status-post` = $id_status_post";
        }

        $result_count = $conn->query($sql_count);
        $row_count = $result_count->fetch_assoc();
        $total_results =$row_count['total'];
        $total_pages = ceil($total_results/$results_per_page);

        $sql .= " ORDER BY `id-post` DESC LIMIT $start_form, $results_per_page";
        $result = $conn->query($sql);
    ?>
    <main class="admin-post">
        <h3 class="title-page">Bài viết</h3>
        <!--- Function delete, add new --->
        <div class="row justify-content-between mt-4">
            <div class="col-5 d-flex flex-row column-gap-2 align-items-center">
                <button class="button-light-background p-2 px-4" onclick="window.open('admin-new-post', '_blank')">Thêm mới</button>
                <button class="button-light-background p-2 px-4" type="submit" form="delete-form" onclick="return confirm('Bạn có chắc chắn muốn xóa không?');">Xóa</button>
            </div>
            <!---
            <div class="col-3">
                <form class="d-flex flex-row column-gap-2 align-items-center justify-content-end">
                    <input class="col p-2 border-round" type="text">
                    <button class="col-2 button-light-background p-2" type="submit">Tìm</button>
                </form>
            </div>--->
        </div>
        <!--- Filter --->
        <div class="row">
            <form method="GET" action="admin.php" class="d-flex flex-row column-gap-2 mt-4 align-items-center">
                <input type="hidden" name="page" value="admin-post">
                <input type="hidden" name="category-post" value="<?php echo htmlspecialchars($rq_category_post); ?>">
                <input type="hidden" name="date-post" value="<?php echo htmlspecialchars($rq_date); ?>">
                <?php 
                echo '<div class="col-2">';
                    echo '<select id="category-post" name="category-post" class="px-2 py-2 border-accent">';
                    echo '<option value="">Chuyên mục</option>';
                        $sql_category= "SELECT * FROM `category-post`;";
                        $result_name_category = $conn->query($sql_category);
                        if ($result_name_category) {
                            if ($result_name_category->num_rows > 0) {
                                while ($name_category_post = $result_name_category->fetch_assoc()) {
                                    $selected_category_post = ($name_category_post['name-category-post']===$category_post) ? 'selected' : '';                                        echo '<option value="' . htmlspecialchars($name_category_post['name-category-post']) . '"' . $selected_category_post . '>'
                                        . htmlspecialchars($name_category_post['name-category-post'])
                                        . '</option>';
                                    }
                                }
                            }
                        echo '</select>';
                    echo '</div>';
                    ?>
                     <!--- 
                    <div>
                        <input class="col p-2 border-round" type="date" value="" id="date-post" name="date-post"></input>
                    </div>--->
                    <?php
                    echo '<div class="col-2">';
                        echo '<select id="status-post" name="status-post" class="px-2 py-2 border-accent">';
                        echo '<option value="">Trạng thái</option>';
                        $sql_status = "SELECT * FROM `status`;";
                        $result_name_status = $conn->query($sql_status);
                        if ($result_name_status) {
                            if ($result_name_status->num_rows > 0) {
                                while ($name_status = $result_name_status->fetch_assoc()) {
                                    $selected_status = ($name_status['name-status']===$status_post) ? 'selected' : '';
                                    echo '<option value="' . htmlspecialchars($name_status['name-status']) . '"' . $selected_status . '>'
                                    . htmlspecialchars($name_status['name-status'])
                                    . '</option>';
                                }
                            }
                        }
                        echo '</select>';
                    echo '</div>';
                    ?>
                <button class="px-4 button-light-background p-2" type="submit">Lọc</button>
            </form>
        </div>
        <?php 
        //Phan trang
        echo '<div class="d-flex flex-row mt-5 column-gap-5 justify-content-between">';
            echo '<p class="accent">Tổng: ' . $total_results . ' mục</p>';
            echo '<div class="pagination-admin d-flex flex-row column-gap-4">';
                if ($page > 1) {
                    echo '<a href="?page=admin-post&post-page=' . ($page - 1) . '">«</a>';
                }
                for ($i = 1; $i <= $total_pages; $i++) {
                    if ($total_pages == 1) {
                        echo '<p>' . $total_pages . '</p>';
                    } else if ($i == $page) {
                        echo '<span class="accent">' . $i .'</span>';
                    } else {
                        echo '<a class="number" href="?page=admin-post&post-page=' . $i . '">' . $i . '</a>';
                    }
                }
                if ($page < $total_pages) {
                    echo '<a href="?page=admin-post&post-page=' . ($page + 1) . '">»</a>';
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
                        <th scope="col" class="col">Chuyên mục</th>
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
                                if (!empty($post['title-post'])) {
                                    echo '<td><div class="text-start">';
                                        $title = htmlspecialchars($post['title-post']);
                                        $shortTitle = truncateTitle($title);
                                        echo '<a class="accent link" href="admin-edit-post?id-post=' . htmlspecialchars($post['id-post']) . '">' . $shortTitle . '  </a>';
                                        echo '<a href="../single-post?slug-post=' . htmlspecialchars($post['slug-post']) . '" target="_blank"><i class="icon fa-solid fa-eye"></i></a>';
                                    echo '</div>';
                                    echo '</td>';
                                } else {
                                    echo '<td></td>';
                                }
                                $sql_name_category = "SELECT * FROM `category-post` WHERE `id-category-post` = " . intval($post['id-category-post']) . ";";
                                $result_category_post = $conn->query($sql_name_category);
                                if($result_category_post && $result_category_post->num_rows > 0) {
                                    $name_category = $result_category_post->fetch_assoc();
                                    echo '<td>' . htmlspecialchars($name_category['name-category-post']) . '</td>';
                                }
                                else {
                                    echo '<td></td>';
                                }
                                echo '<td>' . (!empty($post['date-post']) ? formatDate($post['date-post']) : '') . '</td>'; 
                                $expert = htmlspecialchars($post['expert-post']);
                                $shortExpert = truncateExpertShort($expert);
                                echo '<td>' . (!empty($post['expert-post']) ? htmlspecialchars($shortExpert) : '') . '</td>';
                                $sql_name_status = "SELECT * FROM `status` WHERE `id-status` = " . intval($post['id-status-post']) . ";";
                                    $result_status = $conn->query($sql_name_status);
                                    if($result_status && $result_status->num_rows > 0) {
                                        $name_status_for_post = $result_status->fetch_assoc();
                                        echo '<td>' . htmlspecialchars($name_status_for_post['name-status']) . '</td>';
                                    } else {
                                        echo '<td></td>';
                                    }
                                //echo '<td>' . htmlspecialchars($post['id-status-post']) . '</td>';
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
        <hr>
        <?php 
        //Phan trang
        echo '<div class="d-flex flex-row mt-5 column-gap-5 justify-content-between">';
            echo '<p class="accent">Tổng: ' . $total_results . ' mục</p>';
            echo '<div class="pagination-admin d-flex flex-row column-gap-4">';
                if ($page > 1) {
                    echo '<a href="?page=admin-post&post-page=' . ($page - 1) . '">«</a>';
                }
                for ($i = 1; $i <= $total_pages; $i++) {
                    if ($total_pages == 1) {
                        echo '<p>' . $total_pages . '</p>';
                    } else if ($i == $page) {
                        echo '<span class="accent">' . $i .'</span>';
                    } else {
                        echo '<a class="number" href="?page=admin-post&post-page=' . $i . '">' . $i . '</a>';
                    }
                }
                if ($page < $total_pages) {
                    echo '<a href="?page=admin-post&post-page=' . ($page + 1) . '">»</a>';
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