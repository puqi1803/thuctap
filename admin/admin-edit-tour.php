<?php
include '../includes/check-login.php';
include '../partical/db_connect.php';
include '../includes/functions.php';

if (isset($_GET['id-tour'])) {
    $id_tour = $_GET['id-tour'];
    $sql = "SELECT * FROM tour WHERE `id-tour` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id_tour);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0 ) {
        $tour = $result->fetch_assoc();
        $id_status_tour = $tour['id-status-tour'];
    } else {
        echo 'Tour không tồn tại.';
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
    $img_tour = $tour['img-tour'];
    $id_status_tour = $_POST['id-status-tour'];

    if (isset($_FILES['img-tour']) && $_FILES['img-tour']['error'] == 0) {
        include '../includes/check-image.php';
    }
    //$status_tour = (isset($_POST['Draft']) && $_POST['Draft'] === 'true') ? 'Draft' : 'Published';

    $sql = "UPDATE tour SET
    `title-tour` = ?, 
    `date-tour` = ?, 
    `img-tour` = ?, 
    `price-tour` = ?,
    `price-children-tour` = ?, 
    `price-baby-tour` = ?, 
    `starting-gate` = ?, 
    `participators` = ?, 
    `sale-price-tour` = ?, 
    `information-tour` = ?, 
    `duration-tour` = ?, 
    `timeline-tour` = ?, 
    `description-tour` = ?,
    `id-status-tour`= ?,
    `location-tour`= ?
    WHERE `id-tour` = ?"; 

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssiiisiissssiss",
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
        $id_status_tour,
        $location_tour,
        $id_tour
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
    <title>Sửa tour</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="description" content=""/>
    <meta http-equiv="refresh" content="900">
</head>

<body>
    <?php  
        include '../header-blank.php';

        $sql_starting_gate = "SELECT `name-starting-gate` FROM `starting-gate`  ORDER BY `name-starting-gate` ASC ";
        $result_starting_gate = $conn->query($sql_starting_gate);

        $sql_location = "SELECT `name-location` FROM `location`  ORDER BY `name-location` ASC ";
        $result_location = $conn->query($sql_location);
    ?>
    
    <div class="container-fluid taskbar">
        <div class="container d-flex flex-row py-2 px-4 justify-content-between">
            <div class="d-flex flex-row column-gap-4">
                <button class="button-none"
                    onclick= "if(confirm('Bạn sẽ rời khỏi trang này khi bài viết chưa được lưu. Bạn chắc chắn chứ?'))
                    {window.location.href='admin?page=admin-tour';}">
                    <i class="icon fa-solid fa-arrow-left"></i>&nbsp;&nbsp;Trở về
                </button>
            </div>
            <div class="d-flex flex-row column-gap-4"> 
                <a href="../single-tour?id-tour=<?php echo htmlspecialchars($tour['id-tour']);?>" target="_blank"><i class="icon fa-solid fa-eye"></i>  Xem</a>
            </div>
        </div>
    </div>           
    <main class="container admin-edit-tour">
        <form method="POST" enctype="multipart/form-data">
            <div class="row mt-5 column-gap-4">
                <div>
                    <h5>Tiêu đề</h5>
                    <h4><textarea class="title-tour p-4" rows="2" type="text" id="title-tour" name="title-tour"><?php echo htmlspecialchars($tour['title-tour']);?></textarea></h4>
                </div>
                <div class="col-8 d-flex flex-column mt-4 row-gap-4">
                    <div class="d-flex flex-column row-gap-2">
                        <h5>Nội dung</h5>
                        <div>
                            <label for="description-tour">Mô tả</label>
                            <textarea rows="10" id="description-tour" name="description-tour"><?php echo htmlspecialchars($tour['description-tour']);?></textarea>
                        </div>
                        <div>
                            <label for="timeline-tour">Lịch trình</label>
                            <textarea rows="10" id="timeline-tour" name="timeline-tour"><?php echo htmlspecialchars($tour['timeline-tour']);?></textarea> 
                        </div>
                        <div>
                            <label for="information-tour">Thông tin khác</label>
                            <textarea rows="10" id="information-tour" name="information-tour"><?php echo htmlspecialchars($tour['information-tour']);?></textarea>
                        </div>
                    </div>
                </div>
                <div class="col d-flex flex-column mt-4 row-gap-4">
                    <div class="d-flex flex-column row-gap-2">
                        <label for="img-tour">Ảnh</label>
                        <?php if (!empty($tour['img-tour'])) : ?>
                            <img id="old-image" src="../resources/uploads/<?php echo htmlspecialchars($tour['img-tour']); ?>" alt="<?php echo htmlspecialchars($tour['img-tour']); ?>">
                        <?php endif; ?>
                        <div id="image-preview"></div>
                        <input type="file" id="img-tour" name="img-tour" accept="image/*" onchange="previewImage(event)">
                    </div>
                    <div class="d-flex flex-column row-gap-2">
                        <h5>Thông tin chung</h5>
                        <div class="row justify-content-between">
                            <div class="col-6">
                                <label for="id-tour">Mã tour</label>
                                <input type="text" id="id-tour" name="id-tour" value="<?php echo htmlspecialchars($tour['id-tour']);?>" disabled>
                            </div>
                            <div class="col-6">
                                <label for="date-tour">Ngày khởi hành</label>
                                <input  type="date" id="date-tour" name="date-tour" value="<?php echo date($tour['date-tour']);?>">
                            </div>
                        </div>
                        <div class="row justify-content-between">
                            <div class="col-6">
                                <label for="starting-gate">Khởi hành</label>
                                <select id="starting-gate" name="starting-gate">
                                    <?php
                                    if ($result_starting_gate) {
                                        if ($result_starting_gate->num_rows > 0) {
                                            while ($starting_gate = $result_starting_gate->fetch_assoc()) {
                                                echo '<option value="' . htmlspecialchars($starting_gate['name-starting-gate']) . '"';
                                                if ($starting_gate['name-starting-gate'] == $tour['starting-gate']) {
                                                    echo ' selected';
                                                }
                                                echo '>' . htmlspecialchars($starting_gate['name-starting-gate']) . '</option>';
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-6">
                                <label for="duration-tour">Thời gian</label>
                                <input type="text" id="duration-tour" name="duration-tour" value="<?php echo htmlspecialchars($tour['duration-tour']);?>">
                            </div>
                        </div>
                        <div class="row justify-content-between">
                            <div class="col-6">
                                <label for="participators">Số lượng</label>
                                <input type="number" id="participators" name="participators" value="<?php echo number_format($tour['participators']);?>">     
                            </div>
                            <div class="col-6">
                                <label for="location-tour">Địa điểm</label>
                                <select id="location-tour" name="location-tour">
                                    <?php
                                    if ($result_location) {
                                        if($result_location->num_rows > 0) {
                                            while($location_tour = $result_location->fetch_assoc()) {
                                                echo '<option value="' . htmlspecialchars($location_tour['name-location']) . '"';
                                                if ($location_tour['name-location'] == $tour['location-tour']) {
                                                    echo ' selected';
                                                }    
                                                echo '>' . htmlspecialchars($location_tour['name-location']) . '</option>';
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>             
                    </div>
                    <div class="d-flex flex-column row-gap-2">
                        <h5>Giá</h5>
                        <div class="row justify-content-between">
                            <div class="col-6">
                                <label for="price-tour">Giá bán</label>
                                <?php $price_tour = isset($tour['price-tour']) ? str_replace(',', '', $tour['price-tour']) : 0; ?>
                                <input type="number" id="price-tour" name="price-tour" value="<?php echo $price_tour ?>" step="0.01">
                            </div>
                            <div class="col-6">
                                <?php $sale_price_tour = isset($tour['sale-price-tour']) ? str_replace(',', '', $tour['sale-price-tour']) : 0; ?>
                                <label for="sale-price-tour">Giá khuyến mãi</label>
                                <input type="number" id="sale-price-tour" name="sale-price-tour" value="<?php echo $sale_price_tour; ?>" step="0.01">
                            </div>
                        </div>
                        <div class="row justify-content-between">
                            <div class="col-6">
                                <?php $price_childern_tour = isset($tour['price-children-tour']) ? str_replace(',', '', $tour['price-children-tour']) : 0; ?>
                                <label for="price-children-tour">Giá cho trẻ em<br>(Từ 2 - 11 tuổi)</label>
                                <input type="number" id="price-children-tour" name="price-children-tour" value="<?php echo $price_childern_tour ?>" step="0.01">
                            </div>
                            <div class="col-6">
                                <?php $price_baby_tour = isset($tour['price-baby-tour']) ? str_replace(',', '', $tour['price-baby-tour']) : 0; ?>
                                <label for="price-baby-tour">Giá cho trẻ sơ sinh<br>(Dưới 2 tuổi)</label>
                                <input type="number" id="price-baby-tour" name="price-baby-tour" value="<?php echo $price_baby_tour?>" step="0.01">
                            </div>
                        </div>   
                    </div>
                    <!--- <div class="d-flex flex-row column-gap-4 justify-content-end"> 
                        <button class="button-light-background px-3 py-2" type="submit" name="Draft" value="true">
                            <i class="icon fa-solid fa-file-alt"></i>&nbsp;&nbsp;Lưu nháp
                        </button>
                        <button class="button-primary px-3 py-2" type="submit" name="submit">
                            <i class="icon fa-solid fa-floppy-disk"></i>&nbsp;&nbsp;Phát hành
                        </button>
                    </div> -->
                    <div class="row justify-content-between mt-2">
                        <div class="col-8">
                        <select id="id-status-tour" name="id-status-tour">
                            <option value=3 <?php echo (intval($id_status_tour) === 3) ? 'selected' : '' ?>>Phát hành</option>
                            <option value=2 <?php echo (intval($id_status_tour) === 2) ? 'selected' : '' ?>>Chờ duyệt</option>
                            <option value=1 <?php echo (intval($id_status_tour) === 1) ? 'selected' : '' ?>>Nháp</option>
                        </select>
                        </div>
                        <div class="col-4">
                            <button class="button-primary px-3 py-2 w-100" type="submit" name="submit">
                                <i class="icon fa-solid fa-floppy-disk"></i>&nbsp;&nbsp;Lưu
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </main>
    <script>
        function previewImage(event) {
            const imagePreview = document.getElementById('image-preview');
            const oldImage = document.getElementById('old-image');
            if (oldImage) {
                oldImage.style.display = 'none';
            }
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
</body>
<?php
    // Đóng kết nối
    $conn->close();
?>

</html>