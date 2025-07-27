<?php
include '../partical/db_connect.php';
include '../includes/functions.php';
include '../includes/delete.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Quản lý nhiệm vụ</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="description" content=""/>
    <meta http-equiv="refresh" content="900">
</head>

<body>
    <?php
        include '../header-blank.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name_role_post = $_POST['name-role-collaborator'];
            $description_role_post = $_POST['description-role-collaborator'];
    
        $sql = "INSERT INTO `role-collaborator` (
            `name-role-collaborator`,
            `description-role-collaborator`
            ) VALUES (?, ?)";
    
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss",
            $name_role_post,
            $description_role_post
            );
    
        if ($stmt->execute()) {
            $id_role_collaborator = $conn->insert_id;
            header("Location: ?page=admin-role-collaborator");
            exit();
        } else {
            echo 'Lỗi: ' . $stmt->error;
        }
        $stmt->close();
        }

        //Phan trang
        $results_per_page = 5;

        $page = isset($_GET['role-collaborator-page']) ? (int)($_GET['role-collaborator-page']) : 1;
        if ($page < 1) {
            $page = 1;
        }
        $start_form = ($page-1) * $results_per_page;

        $sql_count = "SELECT COUNT(*) AS total FROM `role-collaborator` WHERE 1=1";
        $result_count = $conn->query($sql_count);
        $row_count = $result_count->fetch_assoc();
        $total_results =$row_count['total'];
        $total_pages = ceil($total_results/$results_per_page);

        $sql = "SELECT * FROM `role-collaborator` ORDER BY `name-role-collaborator` ASC LIMIT $start_form, $results_per_page";
        $result = $conn->query($sql);
    ?>
    <main class="role-collaborator">
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
                        <label for="name-role-collaborator">Tên nhiệm vụ</label>
                        <input type="text" id="name-role-collaborator" name="name-role-collaborator">
                    </div>
                    <div>
                        <label for="description-role-collaborator">Mô tả</label>
                        <textarea type="text" rows="5" id="description-role-collaborator" name="description-role-collaborator"></textarea>
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
                            <select class="w-25" id="action-role-collaborator" name="action-role-collaborator">
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
                            echo '<a href="?page=admin-role-collaborator&role-collaborator-page=' . ($page - 1) . '">«</a>';
                        }
                        for ($i = 1; $i <= $total_pages; $i++) {
                            if ($total_pages == 1) {
                                echo '';
                            }
                            else if ($i == $page) {
                                    echo '<span class="accent">' . $i .'</span>';
                            } else {
                                echo '<a class="number" href="?page=admin-role-collaborator&role-collaborator-page=' . $i . '">' . $i . '</a>';
                            }
                        }
                        if ($page < $total_pages) {
                            echo '<a href="?page=admin-role-collaborator&role-collaborator-page=' . ($page + 1) . '">»</a>';
                        }
                        echo '</div>';  
                        ?>
                    </div>
                </div>

                <!--- Show Category --->    
                <div>
                    <form id="delete-form" method="POST">
                        <input type="hidden" name="deleted-role-collaborator" value="1">
                        <div class="mt-2">
                            <table class="table table-striped">
                                <thead>
                                    <tr class="text-center text-uppercase">
                                        <th scope="col" class="col-1"><input type="checkbox" id="select-all"></th>
                                        <th scope="col" class="col-3">Tên chuyên mục</th>
                                        <th scope="col" class="col">Mô tả</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                if ($result) {
                                    if ($result->num_rows > 0) {
                                        while ($role_collaborator = $result->fetch_assoc()) {
                                            echo '<tr class="text-center">';
                                                echo '<td><input type="checkbox" name="categories_post[]" value="'
                                                . htmlspecialchars($role_collaborator['id-role-collaborator']) . '" class="role-collaborator-select"></td>';
                                                if (!empty($role_collaborator['name-role-collaborator'])) {
                                                    echo '<td><a class="accent link" href="admin-edit-role-collaborator?id-role-collaborator=' . htmlspecialchars($role_collaborator['id-role-collaborator']) . '">'
                                                        . htmlspecialchars($role_collaborator['name-role-collaborator']) . '  </a></td>';
                                                } else {
                                                    echo '<td></td>';
                                                }
                                                $description = htmlspecialchars($role_collaborator['description-role-collaborator']);
                                                $short_description = truncateExpertShort($description);
                                                echo '<td>' . (!empty($role_collaborator['description-role-collaborator']) ? htmlspecialchars($short_description) : '') . '</td>';
                                            echo '</tr>';
                                            }
                                        } else {
                                            echo '<tr><td colspan="3">Không tìm thấy kết quả phù hợp</td></tr>';
                                        }
                                    } else {
                                        echo '<tr><td colspan="3">Lỗi: ' . $conn->error . '</td></tr>'; 
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
                            echo '<a href="?page=admin-role-collaborator&role-collaborator-page=' . ($page - 1) . '">«</a>';
                        }
                        for ($i = 1; $i <= $total_pages; $i++) {
                            if ($total_pages == 1) {
                                echo '';
                            }
                            else if ($i == $page) {
                                echo '<span class="accent">' . $i .'</span>';
                            } else {
                                echo '<a class="number" href="?page=admin-role-collaborator&role-collaborator-page=' . $i . '">' . $i . '</a>';
                            }
                        }
                        if ($page < $total_pages) {
                            echo '<a href="?page=admin-role-collaborator&role-collaborator-page=' . ($page + 1) . '">»</a>';
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
            var checkboxes = document.getElementsByClassName('role-collaborator-select');
            for (var checkbox of checkboxes) {
                checkbox.checked = this.checked;
            }
        };
        function confirmAction() {
            const action = document.getElementById("action-role-collaborator").value;
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