<?php
session_start();
if (!isset($_SESSION['username-user'])) {
    header('Location: ../login.php');
    exit;
}

include '../partical/db_connect.php';
include '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_tour = $_POST['id-tour'];
    $title_tour = $_POST['title-tour'];
    $date_tour = $_POST['date-tour'];
    $price_tour = $_POST['price-tour'];
    $price_children_tour = $_POST['price-children-tour'];
    $price_baby_tour = $_POST['price-baby-tour'];
    $starting_gate = $_POST['starting-gate'];
    $participators = $_POST['participators'];
    $sale_price_tour = $_POST['sale-price-tour'];
    $information_tour = $_POST['information-tour'];
    $duration_tour = $_POST['duration-tour'];
    $timeline_tour = $_POST['timeline-tour'];
    $description_tour = $_POST['description-tour'];
    $location_tour = $_POST['location-tour'];
    $img_tour = null;

    include '../includes/check-image.php';

    $status_tour = (isset($_POST['Draft']) && $_POST['Draft'] === 'true') ? 'Draft' : 'Published';

    $sql = "INSERT INTO tour (
        `id-tour`,
        `title-tour`, 
        `date-tour`, 
        `img-tour`, 
        `price-tour`,
        `price-children-tour`, 
        `price-baby-tour`, 
        `starting-gate`, 
        `participators`, 
        `sale-price-tour`, 
        `information-tour`, 
        `duration-tour`, 
        `timeline-tour`, 
        `description-tour`,
        `status-tour`,
        `location-tour`
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssiiisiissssss",
        $id_tour,
        $title_tour, 
        $date_tour, 
        $img_tour,
        $price_tour, 
        $price_children_tour, 
        $price_baby_tour, 
        $starting_gate, 
        $participators, 
        $sale_price_tour, 
        $information_tour, 
        $duration_tour, 
        $timeline_tour, 
        $description_tour,
        $status_tour,
        $location_tour
    );

    if ($stmt->execute()) {
        header("Location: admin-edit-tour?id-tour=$id_tour");
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
    <title>Thêm tour</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="description" content=""/>
    <meta http-equiv="refresh" content="900">
</head>

<body>
    <?php 
        include '../header-blank.php';
        include '../taskbar.php';

        $sql_location = "SELECT `name-location` FROM `location`  ORDER BY `name-location` ASC ";
        $result_location = $conn->query($sql_location);
    ?> 
    <main class="container admin-new-tour">
        <form method="POST" enctype="multipart/form-data">
            <div class="row mt-5 column-gap-4">
                <div>
                    <h5>Tiêu đề</h5>
                    <h4><textarea class="title-tour p-4" rows="2" type="text" id="title-tour" name="title-tour"></textarea></h4>
                </div>
                <div class="col-8 d-flex flex-column mt-4 row-gap-4">
                    <div class="d-flex flex-column row-gap-2">
                        <h5>Nội dung</h5>
                        <div>
                            <label for="description-tour">Mô tả</label>
                            <textarea rows="10" id="description-tour" name="description-tour"></textarea>
                        </div>
                        <div>
                            <label for="timeline-tour">Lịch trình</label>
                            <textarea rows="10" id="timeline-tour" name="timeline-tour"></textarea> 
                        </div>
                        <div>
                            <label for="information-tour">Thông tin khác</label>
                            <textarea rows="10" id="information-tour" name="information-tour"></textarea>
                        </div>
                    </div>
                </div>
                <div class="col d-flex flex-column mt-4 row-gap-4">
                    <div class="d-flex flex-column row-gap-2">
                        <label for="img-tour">Ảnh</label>
                        <div id="image-preview"></div>
                        <input type="file" id="img-tour" name="img-tour" accept="image/*" onchange="previewImage(event)">
                    </div>
                    <div class="d-flex flex-column row-gap-2">
                        <h5>Thông tin chung</h5>
                        <div class="row justify-content-between">
                            <div class="col-6">
                                <label for="id-tour">Mã tour</label>
                                <input type="text" id="id-tour" name="id-tour" required>
                            </div>
                            <div class="col-6">
                                <label for="date-tour">Ngày khởi hành</label>
                                <input  type="date" id="date-tour" name="date-tour" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                        </div>
                        <div class="row justify-content-between">
                            <div class="col-6">
                                <label for="starting-gate">Khởi hành</label>
                                <input type="text" id="starting-gate" name="starting-gate">
                            </div>
                            <div class="col-6">
                                <label for="duration-tour">Thời gian</label>
                                <input type="text" id="duration-tour" name="duration-tour">
                            </div>
                        </div>
                        <div class="row justify-content-between">
                            <div class="col-6">
                                <label for="participators">Số lượng</label>
                                <input type="number" id="participators" name="participators">     
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-column row-gap-2">
                        <h5>Giá</h5>
                        <div class="row justify-content-between">
                            <div class="col-6">
                                <label for="price-tour">Giá bán</label>
                                <input type="number" id="price-tour" name="price-tour">
                            </div>
                            <div class="col-6">
                                <label for="sale-price-tour">Giá khuyến mãi</label>
                                <input type="number" id="sale-price-tour" name="sale-price-tour">
                            </div>
                        </div>
                        <div class="row justify-content-between">
                            <div class="col-6">
                                <label for="price-children-tour">Giá cho trẻ em</label>
                                <input type="number" id="price-children-tour" name="price-children-tour">
                            </div>
                            <div class="col-6">
                                <label for="price-baby-tour">Giá cho trẻ sơ sinh</label>
                                <input type="number" id="price-baby-tour" name="price-baby-tour">
                            </div>
                        </div>   
                    </div>
                    <div class="d-flex flex-row column-gap-4 justify-content-end"> 
                        <button class="button-light-background px-3 py-2" type="submit" name="Draft" value="true">
                            <i class="icon fa-solid fa-file-alt"></i>&nbsp;&nbsp;Lưu nháp
                        </button>
                        <button class="button-primary px-3 py-2" type="submit" name="submit">
                            <i class="icon fa-solid fa-floppy-disk"></i>&nbsp;&nbsp;Phát hành
                        </button>
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
                reader.readAsDataURL(file);
            }
        }
        </script>
    </script> 
</body>
<?php
    // Đóng kết nối
    $conn->close();
?>

</html>