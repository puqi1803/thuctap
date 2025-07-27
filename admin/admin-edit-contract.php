    <?php
    include '../includes/check-login.php';
    include '../partical/db_connect.php';
    include '../includes/functions.php';

    if (isset($_GET['id-contract'])) {
        $id_contract = $_GET['id-contract'];
        $sql = "SELECT * FROM `contract` WHERE `id-contract` = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_contract);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0 ) {
            $contract = $result->fetch_assoc();
            
            $sql_tour = "SELECT * FROM `tour`";
            $result_tour = $conn->query($sql_tour);

            $sql_collaborator = "SELECT * FROM `collaborator`";
            $result_collaborator = $conn->query($sql_collaborator);

            $sql_contract_type = "SELECT * FROM `contract-type`";
            $result_contract_type= $conn->query($sql_contract_type);

            $title_tour = getTourTitle($conn, $contract['index-tour']);
            $name_collaborator = getCollaboratorName($conn, $contract['id-collaborator']);
            $name_contract_type = getContractTypeName($conn, $contract['id-contract-type']);

            }
        } else {
            echo 'Lỗi, không tồn tại.';
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name_collaborator = $_POST['name-collaborator'];
            $name_contract_type = $_POST['name-contract-type'];
            $title_tour = $_POST['title-tour'];
            //$generated_file = $_POST['generated-file'];
            $created_at = $_POST['created-at'];
            $subtotal_contract = $_POST['subtotal-contract'];
            $tax_contract = $_POST['tax-contract'];
            $total_contract = $_POST['total-contract'];

            $sql = "UPDATE `contract` SET
                `id-collaborator` = ?,
                `id-contract-type` = ?,
                `index-tour` = ?,
                `generated-file` = ?,
                `created-at` = ?,
                `subtotal-contract` = ?,
                `tax-contract` = ?,
                `total-contract` = ?
                WHERE `id-contract` = ?";

                if ($title_tour) {
                    $sql_index_tour = "SELECT `index-tour` FROM `tour` WHERE `title-tour` = '" . $conn->real_escape_string($title_tour) . "'";
                    $result_index_tour = $conn->query($sql_index_tour);
                    if ($result_index_tour && $result_index_tour->num_rows > 0) {
                        $row = $result_index_tour->fetch_assoc();
                        $index_tour = $row['index-tour'];
                    }
                }
                if ($name_collaborator) {
                    $sql_id_collaborator = "SELECT `id-collaborator` FROM `collaborator` WHERE `name-collaborator` = '" . $conn->real_escape_string($name_collaborator) . "'";
                    $result_id_collaborator = $conn->query($sql_id_collaborator);
                    if ($result_id_collaborator && $result_id_collaborator->num_rows > 0) {
                        $row = $result_id_collaborator->fetch_assoc();
                        $id_collaborator = $row['id-collaborator'];
                    }
                }
                if ($name_contract_type) {
                    $sql_id_contract_type = "SELECT `id-contract-type` FROM `contract-type` WHERE `name-contract-type` = '" . $conn->real_escape_string($name_contract_type) . "'";
                    $result_id_contract_type = $conn->query($sql_id_contract_type);
                    if ($result_id_contract_type && $result_id_contract_type->num_rows > 0) {
                        $row = $result_id_contract_type->fetch_assoc();
                        $id_contract_type = $row['id-contract-type'];
                    }
                }

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iiissiiii",
                $id_collaborator,
                $id_contract_type,
                $index_tour,
                $generated_file,
                $created_at,
                $subtotal_contract,
                $tax_contract,
                $total_contract,
                $id_collaborator
                );

            if ($stmt->execute()) {
                header("Location: admin-edit-contract?id-contract=$id_contract");
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
        <title>Sửa hợp đồng</title>
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
                        {window.location.href='admin?page=admin-contract';}">
                        <i class="icon fa-solid fa-arrow-left"></i>&nbsp;&nbsp;Trở về
                    </button>
                </div>
                <div class="d-flex flex-row column-gap-4"> 
                <a href="/nienluan.com/"><i class="icon fa-solid fa-house"></i>  Trang chủ</a>
            </div>
            </div>
        </div>
        <main class="container admin-edit-contract" style="max-width: 700px; padding: 40px 0px">
        <form method="POST" enctype="multipart/form-data">
            <div class="d-flex flex-column row-gap-4 mt-5">
                <div class="d-flex flex-column row-gap-2"> 
                    <label for="title-tour">Tour</label>
                    <select id="title-tour" name="title-tour" class="border-round p-2">
                        <?php
                        if($result_tour && $result_tour->num_rows > 0) {
                            while($tour = $result_tour->fetch_assoc()) {
                                $selected_tour = ($tour['title-tour'] === $title_tour) ? 'selected' : '';
                                echo '<option value="' . htmlspecialchars($tour['title-tour']) . '" ' . $selected_tour . '>'
                                    . htmlspecialchars($tour['title-tour']) . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="d-flex flex-column row-gap-2"> 
                    <label for="name-contract-type">Loại hợp đồng</label>
                    <select id="name-contract-type" name="name-contract-type" class="border-round p-2">
                        <?php
                        if($result_contract_type && $result_contract_type->num_rows > 0) {
                            while($contract_type = $result_contract_type->fetch_assoc()) {
                                $selected_contract_type = ($contract_type['name-contract-type'] === $name_contract_type) ? 'selected' : '';
                                echo '<option value="' . htmlspecialchars($contract_type['name-contract-type']) . '" ' . $selected_contract_type . '>'
                                    . htmlspecialchars($contract_type['name-contract-type']) . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="d-flex flex-column row-gap-2"> 
                    <label for="name-collaborator">Họ tên</label>
                    <select id="name-collaborator" name="name-collaborator" class="border-round p-2">
                        <?php
                        if($result_collaborator && $result_collaborator->num_rows > 0) {
                            while($collaborator = $result_collaborator->fetch_assoc()) {
                                $selected_collaborator = ($collaborator['name-collaborator'] === $name_collaborator) ? 'selected' : '';
                                echo '<option value="' . htmlspecialchars($collaborator['name-collaborator']) . '" ' . $selected_collaborator . '>'
                                    . htmlspecialchars($collaborator['name-collaborator']) . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
                <div>
                    <label for="subtotal-contract">Thực nhận</label>
                    <input type="number" id="subtotal-contract" name="subtotal-contract"
                    value="<?php echo htmlspecialchars($contract['subtotal-contract']); ?>">
                </div>
                <div>
                    <label for="tax-contract">Thuế TNCN</label>
                    <input type="number" id="tax-contract" name="tax-contract"
                    value="<?php echo htmlspecialchars($contract['tax-contract']); ?>">
                </div>
                    <div>
                    <label for="total-contract">Giá trị hợp đồng</label>
                    <input type="number" id="total-contract" name="total-contract"
                    value="<?php echo htmlspecialchars($contract['total-contract']); ?>">
                </div>
                <div>
                    <label for="created-at">Ngày</label>
                    <input type="date" id="created-at" name="created-at" value="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="row justify-content-between my-4">
                    <div class="col">
                        <button class="button-primary px-3 py-2 w-100" name="submit">
                            <i class="icon fa-solid fa-floppy-disk"></i>&nbsp;&nbsp;Cập nhật
                        </button>
                    </div>
                    <div class="col">
                        <button class="button-primary px-3 py-2 w-100" type="submit" name="submit">
                            <i class="icon fa-solid fa-floppy-disk"></i>&nbsp;&nbsp;Lưu
                        </button>
                    </div>
                    </div>
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

