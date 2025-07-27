<?php
include '../partical/db_connect.php';
include '../includes/functions.php';
include '../includes/delete.php';

        
    $results_per_page = 10;

    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    if ($page < 1) {
        $page = 1;
    }
    $start_form = ($page - 1) * $results_per_page;

    $sql_count = "SELECT COUNT(*) AS total FROM `contract`";
    $result_count = $conn->query($sql_count);
    $row_count = $result_count->fetch_assoc();
    $total_results = $row_count['total'];
    $total_pages = ceil($total_results/$results_per_page);

    $sql = "SELECT * FROM `contract` ORDER BY `id-contract` ASC LIMIT $start_form, $results_per_page";
    $result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Quản lý hợp đồng</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="description" content=""/>
    <meta http-equiv="refresh" content="900">
</head>

<body>
    <?php
        include '../header-blank.php';
    ?>

    <main class="admin-contract">
        <h3>Quản lý hợp đồng</h3>
        <div class="row justify-content-between mt-4">
            <div class="col d-flex flex-row column-gap-2 align-items-center">
                <button class="button-light-background p-2 w-25" onclick="window.open('admin-new-contract', '_blank')">Thêm mới</button>
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
                    echo '<a href="?page=admin-contract&page=' . ($page - 1) . '">«</a>';
                }
                for ($i = 1; $i <= $total_pages; $i++) {
                    if ($total_pages == 1) {
                                echo '';
                    }
                    else if ($i == $page) {
                        echo '<span class="accent">' . $i . '</span>';
                    } else {
                        echo '<a  class="number" href="?page=admin-contract&page=' . $i . '">' . $i . '</a>';
                    }
                }
                if ($page < $total_pages) {
                    echo '<a href="?page=admin-contract&page=' . ($page + 1) . '">»</a>';
                }
            echo '</div>';
        echo '</div>';
        ?>
        <hr>
        <form id="delete-form" method="POST">
        <input type="hidden" name="deleted-contract" value="1">
        <div class="mt-2">
            <table class="table table-striped">
                <thead>
                    <tr class="text-center text-uppercase">
                        <th scope="col" class="col"><input type="checkbox" id="select-all"></th>
                        <th scope="col" class="col">Mã hợp đồng</th>
                        <th scope="col" class="col-4">Tour</th>
                        <th scope="col" class="col">Nhiệm vụ</th>
                        <th scope="col" class="col">Tên</th>
                        <th scope="col" class="col">Giá trị</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if($result) {
                        if($result->num_rows > 0) {
                            while ($contract = $result->fetch_assoc()) {
                                echo '<tr class="text-center">';
                                    echo '<td><input type="checkbox" name="contracts[]" value="' . intval($contract['id-contract']) . '" class="contract-select"></td>';
                                    echo '<td><div>
                                        <a class="accent link" href="admin-edit-contract?id-contract=' . intval($contract['id-contract']) . '">'
                                        . htmlspecialchars($contract['id-contract']) . '</a>
                                    </div></td>';

                                    $sql_tour = "SELECT `title-tour`, `id-tour` FROM `tour` WHERE `index-tour` =" . htmlspecialchars($contract['index-tour']) .";";
                                    $result_tour = $conn->query($sql_tour);
                                    if($result_tour && $result_tour->num_rows > 0) {
                                        while($tour = $result_tour->fetch_assoc()) {
                                            $title =  htmlspecialchars($tour['title-tour']);
                                            $shortTitle = truncateTitle($title);
                                            echo '<td><a href="admin-edit-tour?id-tour=' . htmlspecialchars($tour['id-tour']) . '">' . $shortTitle . '  </a></td>';
                                            //echo '<td>' . $shortTitle . '</td>';
                                        }
                                    } else {
                                        echo '<td></td>';
                                    }

                                    $sql_contract_type = "SELECT `name-contract-type` FROM `contract-type` WHERE `id-contract-type` =" . intval($contract['id-contract-type']) .";";
                                    $result_contract_type = $conn->query($sql_contract_type);
                                    if($result_contract_type && $result_contract_type->num_rows > 0) {
                                        while($contract_type = $result_contract_type->fetch_assoc()) {
                                            echo '<td>' . htmlspecialchars($contract_type['name-contract-type']) . '</td>';
                                        }
                                    } else {
                                        echo '<td></td>';
                                    }

                                    $sql_collaborator = "SELECT `name-collaborator`, `id-collaborator` FROM `collaborator` WHERE `id-collaborator` =" . intval($contract['id-collaborator']) .";";
                                    $result_collaborator = $conn->query($sql_collaborator);
                                    if($result_collaborator && $result_collaborator->num_rows > 0) {
                                        while($collaborator = $result_collaborator->fetch_assoc()) {
                                            echo '<td><a href="admin-edit-collaborator?id-collaborator=' . intval($collaborator['id-collaborator']) . '">' 
                                            . htmlspecialchars($collaborator['name-collaborator']) . '  </a></td>';
                                            //echo '<td>' . htmlspecialchars($collaborator['name-collaborator']) . '</td>';
                                        }
                                    } else {
                                        echo '<td></td>';
                                    }

                                    echo '<td>' . htmlspecialchars($contract['total-contract']) . '</td>';
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
                    echo '<a href="?page=admin-contract&page=' . ($page - 1) . '">«</a>';
                }
                for ($i = 1; $i <= $total_pages; $i++) {
                    if ($total_pages == 1) {
                                echo '';
                    }
                    else if ($i == $page) {
                        echo '<span class="accent">' . $i . '</span>';
                    } else {
                        echo '<a  class="number" href="?page=admin-contract&page=' . $i . '">' . $i . '</a>';
                    }
                }
                if ($page < $total_pages) {
                    echo '<a href="?page=admin-contract&page=' . ($page + 1) . '">»</a>';
                }
            echo '</div>';
        echo '</div>';
        ?>
    <div>
    </main>
    <script>
        document.getElementById ('select-all').onclick = function() {
            var checkboxes = document.getElementsByClassName ('contract-select');
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