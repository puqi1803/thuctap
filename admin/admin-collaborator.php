<?php
include '../partical/db_connect.php';
include '../includes/functions.php';
include '../includes/delete.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Quản lý nhân sự</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="description" content=""/>
    <meta http-equiv="refresh" content="900">
</head>

<body>
    <?php
        include '../header-blank.php';
        
        $results_per_page = 10;

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) {
            $page = 1;
        }
        $start_form = ($page - 1) * $results_per_page;

        $sql_count = "SELECT COUNT(*) AS total FROM collaborator";
        $result_count = $conn->query($sql_count);
        $row_count = $result_count->fetch_assoc();
        $total_results = $row_count['total'];
        $total_pages = ceil($total_results/$results_per_page);

        $sql = "SELECT * FROM collaborator ORDER BY `name-collaborator` ASC LIMIT $start_form, $results_per_page";
        $result = $conn->query($sql);
    ?>

    <main class="admin-collaborator">
        <h3>Khách hàng</h3>
        <div class="row justify-content-between mt-4">
            <div class="col d-flex flex-row column-gap-2 align-items-center">
                <button class="button-light-background p-2 w-25" onclick="window.open('admin-new-collaborator', '_blank')">Thêm mới</button>
                <button class="button-light-background p-2 w-25" type="submit" form="delete-form" onclick="return confirm('Bạn có chắc chắn muốn xóa không?');">Xóa</button>
            </div>
            <div class="col-3">
                <form class="d-flex flex-row column-gap-2 align-items-center justify-content-end">
                    <input class="col p-2 border-round" type="text">
                    <button class="col-2 button-light-background p-2" type="submit">Tìm</button>
                </form>
            </div>
        </div>
        <?php
        //Phan trang
        echo '<div class="d-flex flex-row mt-5 column-gap-5 justify-content-between">';
            echo '<p class="accent">Tổng: ' . $total_results . ' mục</p>';
            echo '<div class="pagination-admin d-flex flex-row column-gap-4">';
                if ($page > 1) {
                    echo '<a href="?page=admin-collaborator&page=' . ($page - 1) . '">«</a>';
                }
                for ($i = 1; $i <= $total_pages; $i++) {
                    if ($total_pages == 1) {
                                echo '';
                    }
                    else if ($i == $page) {
                        echo '<span class="accent">' . $i . '</span>';
                    } else {
                        echo '<a  class="number" href="?page=admin-collaborator&page=' . $i . '">' . $i . '</a>';
                    }
                }
                if ($page < $total_pages) {
                    echo '<a href="?page=admin-collaborator&page=' . ($page + 1) . '">»</a>';
                }
            echo '</div>';
        echo '</div>';
        ?>
        <hr>
        <form id="delete-form" method="POST">
        <input type="hidden" name="deleted-collaborator" value="1">
        <div class="mt-2">
            <table class="table table-striped">
                <thead>
                    <tr class="text-center text-uppercase">
                        <th scope="col" class="col"><input type="checkbox" id="select-all"></th>
                        <th scope="col" class="col">Tên</th>
                        <th scope="col" class="col">Số điện thoại</th>
                        <th scope="col" class="col">Phân loại</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if($result) {
                        if($result->num_rows > 0) {
                            while ($collaborator = $result->fetch_assoc()) {
                                echo '<tr class="text-center">';
                                    echo '<td><input type="checkbox" name="collaborators[]" value="' . htmlspecialchars($collaborator['id-collaborator']) . '" class="collaborator-select"></td>';
                                    $title =  htmlspecialchars($collaborator['name-collaborator']);
                                    $shortTitle = truncateTitle($title);
                                    echo '<td><div>
                                        <a class="accent link" href="admin-edit-collaborator?id-collaborator=' . htmlspecialchars($collaborator['id-collaborator']) . '">'
                                        . $shortTitle . '</a>
                                    </div></td>';
                                    echo '<td>' . htmlspecialchars($collaborator['phone-collaborator']) . '</td>';
                                    echo '<td>' . htmlspecialchars($collaborator['role-collaborator']) . '</td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="4">Không tìm thấy kết quả phù hợp</td></tr>';
                        }
                    } else {
                        echo '<tr><td colspan="4">Lỗi: ' . $conn->error . '</td></tr>'; 
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
                    echo '<a href="?page=admin-collaborator&page=' . ($page - 1) . '">«</a>';
                }
                for ($i = 1; $i <= $total_pages; $i++) {
                    if ($total_pages == 1) {
                                echo '';
                    }
                    else if ($i == $page) {
                        echo '<span class="accent">' . $i . '</span>';
                    } else {
                        echo '<a  class="number" href="?page=admin-collaborator&page=' . $i . '">' . $i . '</a>';
                    }
                }
                if ($page < $total_pages) {
                    echo '<a href="?page=admin-collaborator&page=' . ($page + 1) . '">»</a>';
                }
            echo '</div>';
        echo '</div>';
        ?>
    <div>
    </main>
    <script>
        document.getElementById ('select-all').onclick = function() {
            var checkboxes = document.getElementsByClassName ('collaborator-select');
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