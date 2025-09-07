<?php   
include '../includes/check-login.php';
include '../partical/db_connect.php';
include '../includes/functions.php';

// require_once '../vendor/autoload.php';
// require_once '../partical/google-client.php';

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
    $generated_file = $_POST['generated-file'];
    $created_at = $_POST['created-at'];
    $subtotal_contract = $_POST['subtotal-contract'];
    $tax_contract = $_POST['tax-contract'];
    $total_contract = $_POST['total-contract'];
    $aiw_contract = $_POST['aiw-contract'];
    $timeline_tour= $_POST['timeline-tour'];
    $title_contract = $_POST['title-contract'];

    $sql = "UPDATE `contract` SET
        `id-collaborator` = ?,
        `id-contract-type` = ?,
        `index-tour` = ?,
        `subtotal-contract` = ?,
        `tax-contract` = ?,
        `total-contract` = ?,
        `aiw-contract` = ?,
        `timeline-tour` = ?,
        `title-contract` = ?
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
        $sql_id_contract_type = "SELECT `id-contract-type`, `templateID-contract-type` FROM `contract-type` WHERE `name-contract-type` = '" . $conn->real_escape_string($name_contract_type) . "'";
        $result_id_contract_type = $conn->query($sql_id_contract_type);
        if ($result_id_contract_type && $result_id_contract_type->num_rows > 0) {
            $row = $result_id_contract_type->fetch_assoc();
            $id_contract_type = $row['id-contract-type'];
            $templateId = $row['templateID-contract-type'];
        }
    }

    $sql_collaborator = "SELECT * FROM `collaborator` WHERE `name-collaborator` = '" . $conn->real_escape_string($name_collaborator) . "'";
    $result_collaborator = $conn->query($sql_collaborator);
    if ($result_collaborator && $result_collaborator->num_rows > 0) {
        $row_collaborator = $result_collaborator->fetch_assoc();
        $sex = $row_collaborator['sex-collaborator'];
        $dob = $row_collaborator['dob-collaborator'];
        $identify = $row_collaborator['identify-collaborator'];
        $doi = $row_collaborator['doi-collaborator'];
        $poi = $row_collaborator['poi-collaborator'];
        $tax = $row_collaborator['tax-collaborator'];
        $number_bank = $row_collaborator['number-bank-collaborator'];
        $bank_collaborator = $row_collaborator['bank-collaborator'];
    }

    $sql_tour = "SELECT `starting-gate`, `id-location-tour` FROM `tour` WHERE `title-tour` = '" . $conn->real_escape_string($title_tour) . "'";
    $result_tour = $conn->query($sql_tour);
    if ($result_tour && $result_tour->num_rows > 0) {
        $row_tour = $result_tour->fetch_assoc();
        $starting_gate = $row_tour['starting-gate'];
        $id_location_tour = $row_tour['id-location-tour'];
    }
    $sql_location = "SELECT `name-location` FROM `location` WHERE `id-location` = '" . $conn->real_escape_string($id_location_tour) . "'";
    $result_location = $conn->query($sql_location);
    if ($result_location && $result_location->num_rows > 0) {
        $row_location = $result_location->fetch_assoc();
        $location_tour = $row_location['name-location'];
    }
    
    $replacements = [
        'name-collaborator' => $name_collaborator,
        'sex-collaborator' => $sex,
        'dob-collaborator' => $dob,
        'identify-collaborato' => $identify,
        'doi-collaborator' => $doi,
        'poi-collaborator' => $poi,
        'tax-collaborator' => $tax,
        'number-bank-collaborator' => $number_bank,
        'bank-collaborator' => $bank_collaborator,
        'starting-gate' => $starting_gate,
        'location-tour' => $location_tour,
        'timeline-tour' => $timeline_tour,
        'total-contract' => $total_contract,
        'subtotal-contract' => $subtotal_contract,
        'tax-contract' => $tax_contract,
        'aiw-contract' => $aiw_contract
    ];

    //$generated_file = createContractFromTemplate($templateId, $replacements, $title_contract);

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiidddsssi",
            $id_collaborator,
            $id_contract_type,
            $index_tour,
            $subtotal_contract,
            $tax_contract,
            $total_contract,
            $aiw_contract,
            $timeline_tour,
            $title_contract,
            $id_contract
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
            <div>
                <label for="title-contract">Tên hợp đồng</label>
                <input type="text" id="title-contract" name="title-contract"
                value="<?php echo htmlspecialchars($contract['title-contract']); ?>">
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
                <label for="name-contract-type">Nhiệm vụ</label>
                <input type="text" id="name-contract-type" name="name-contract-type"
                value="<?php echo htmlspecialchars($name_contract_type); ?>" readonly>
            </div>

            <div>
                <label for="subtotal-contract">Thực nhận</label>
                <input type="number" id="subtotal-contract" name="subtotal-contract"
                value="<?php echo htmlspecialchars($contract['subtotal-contract']); ?>" readonly>
            </div>
            <div>
                <label for="tax-contract">Thuế TNCN</label>
                <input type="number" id="tax-contract" name="tax-contract"
                value="<?php echo htmlspecialchars($contract['tax-contract']); ?>" readonly>
            </div>
            <div>
                <label for="total-contract">Giá trị hợp đồng</label>
                <input type="number" id="total-contract" name="total-contract"
                value="<?php echo htmlspecialchars($contract['total-contract']); ?>" readonly>
            </div>
            <div>
                <label for="aiw-contract">Số tiền viết bằng chữ</label>
                <input type="text" id="aiw-contract" name="aiw-contract"
                value="<?php echo htmlspecialchars($contract['aiw-contract']); ?>" readonly>
            </div>
            <div>
                <label for="timeline-tour">Thời gian</label>
                <input type="text" id="timeline-tour" name="timeline-tour"
                value="<?php echo htmlspecialchars($contract['timeline-tour']); ?>" readonly>
            </div>
            <div>
                <label for="text">Liên kết</label>
                <input type="text" id="generated-file" name="generated-file" value="<?php echo htmlspecialchars($contract['generated-file']); ?>" readonly>
            </div>
            <!-- <div class="row justify-content-between my-4">
                <div class="col">
                    <button class="button-primary px-3 py-2 w-100" type="submit" name="submit">
                        <i class="icon fa-solid fa-floppy-disk"></i>&nbsp;&nbsp;Lưu thông tin
                    </button>
                </div>
                <div class="col">
                    <button class="button-secondary px-3 py-2 w-100" type="submit" name="generate_google_doc">
                        <i class="icon fa-brands fa-google-drive"></i>&nbsp;&nbsp;Tạo/Cập nhật Google Docs
                    </button>
                </div>
                </div>
            </div> -->
        </div>
    </form>
    </main>
</body>
<?php
    // Đóng kế tết nối
    $conn->close();
?>

</html>
