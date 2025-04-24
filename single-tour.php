<?php
include 'partical/db_connect.php';
include 'includes/functions.php';

$slug = isset($_GET['id-tour']) ? $_GET['id-tour'] : '';

$sql_id_status = "SELECT `id-status` FROM `status` WHERE `name-status` = 'Phát hành'";
$result_id_status = $conn->query($sql_id_status);
if ($result_id_status && $result_id_status->num_rows > 0) {
    $name_status = $result_id_status->fetch_assoc();
    $id_status = $name_status['id-status'];
}

$sql = "SELECT * FROM tour WHERE `id-tour` = ? AND `id-status-tour`=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $slug, $id_status);
$stmt->execute();
$result = $stmt->get_result();
$tour = $result->fetch_assoc();
$stmt->close();

?>  
<!DOCTYPE html>
<html lang="vi">
<head>
    <title>
        <?php
            echo isset($tour['title-tour']) ? htmlspecialchars($tour['title-tour']) : 'Bài viết không tồn tại';
        ?>
    </title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="description" content=""/>
    <link rel="stylesheet" href="resources/style.css">
    <meta http-equiv="refresh" content="900">
</head>

<body>
    <?php include "header-main.php" ?>
    <main class="single-tour container">
        <?php if ($tour) : ?>
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb accent">
                    <li class="breadcrumb-item"><a href="/nienluan.com/">Trang Chủ</a>
                    <li class="breadcrumb-item"><a href="/nienluan.com/tour">Tour Du Lịch</a>
                    <li class="breadcrumb-item active" breadcrumb-item active="page"><?php echo htmlspecialchars($tour['title-tour']); ?></li>
                </ol>
            </nav>
                <div class="title-page mt-5 text-center">
                    <h3><?php echo htmlspecialchars($tour['title-tour']); ?></h3>
                </div>
                <div class="row mt-5 mb-3">
                    <div class="col-8 d-flex flex-column row-gap-5">
                        <div class="d-flex flex-row column-gap-4">
                            <div class="d-flex flex-column w-25 row-gap-2">
                                <img class="gallery-img-tour object-fit-cover" src="resources/uploads/<?php echo htmlspecialchars($tour['img-tour']); ?>">
                            </div>
                            <div class="d-flex flex-column w-75 row-gap-2">
                                <img class="img-tour object-fit-cover" src="resources/uploads/<?php echo htmlspecialchars($tour['img-tour']); ?>">
                            </div>
                        </div>
                        <?php if(!empty($tour['description-tour'])) : ?> 
                        <div>
                            <?php echo nl2br(html_entity_decode($tour['description-tour'])); ?>
                        </div>
                        <?php endif ?>
                        <?php if(!empty($tour['timeline-tour'])) : ?> 
                        <div>
                            <h4 class="title-page text-center mb-4">Lịch trình</h4>
                            <?php echo nl2br(html_entity_decode($tour['timeline-tour'])); ?>
                        </div>
                        <?php endif ?>
                        <?php if(!empty($tour['information-tour'])) : ?> 
                        <div>
                            <h4 class="title-page text-cente mb-4">Thông tin khác</h4>
                            <?php echo nl2br(html_entity_decode($tour['information-tour'])); ?>
                        </div>
                        <?php endif ?>
                    </div>    
                    <div class="col info-tour relative">
                        <div class="d-flex flex-column row-gap-2 px-4 py-4 sticky border-round background-white shadow-md">
                            <div class="d-flex flex-column row-gap-4">
                                <div class="d-flex flex-column column-gap-2">
                                    <h5>Giá:</h5>
                                    <p class="note-ligh">(Người lớn - từ 12 tuổi trở lên)</p>
                                    <div class="d-flex flex-wrap column-gap-2 align-items-center mt-2">
                                        <p class="price-tour"><?php echo number_format($tour['price-tour'], 0, ',', '.'); ?> đ </p>
                                        <p class="dvt">/ Khách</p>
                                    </div>   
                                </div>
                            </div> 
                            <?php if (!is_null($tour['price-children-tour'])): ?>
                                <div class="d-flex flex-row mt-2 justify-content-between align-items-center">
                                    <div class="d-flex flex-column">
                                        <p class="accent">Trẻ em:</p>
                                        <p class="note-light">(Từ 2 - 11 tuổi)</p>
                                    </div>
                                    <p class="price-other-tour"><?php echo number_format($tour['price-children-tour'], 0, ',', '.'); ?> đ</p>
                                </div>
                            <?php endif; ?>
                            <?php if (!is_null($tour['price-baby-tour'])) : ?>
                                <div class="d-flex flex-row mt-2 justify-content-between align-items-center">
                                    <div class="d-flex flex-column">
                                        <p class="accent">Em bé:</p>
                                        <p class="note-light">(Dưới 2 tuổi)</p>
                                    </div>
                                    <p class="price-other-tour"><?php echo number_format($tour['price-baby-tour'], 0, ',', '.'); ?> đ</p>
                                </div>
                            <?php endif; ?>
                            <hr class="my-4">
                            <div class="d-flex flex-row column-gap-2 align-items-center">
                                <i class="icon fa-classic fa-solid fa-circle-info fa-fw"></i>
                                <p>Mã chương trình: </p>
                                <p class="id-tour accent"><?php echo htmlspecialchars($tour["id-tour"]) ?></p>
                            </div>
                            <div class="d-flex flex-row column-gap-2 align-items-center">
                                <i class="icon fa-solid fa-plane"></i>
                                <p>Khởi hành:</p>
                                <p class="id-tour accent"><?php echo htmlspecialchars($tour["starting-gate"]) ?></p>
                            </div>
                            <div class="d-flex flex-row column-gap-2 align-items-center">
                                <i class="icon fa-solid fa-location-dot"></i>
                                <p>Địa điểm:</p>
                                <p class="id-tour accent"><?php echo htmlspecialchars($tour["location-tour"]) ?></p>
                            </div>
                            <div class="d-flex flex-row column-gap-2 align-items-center">
                                <i class="icon fa-solid fa-calendar-days"></i>
                                <p>Ngày khởi hành:</p>
                                <p class="id-tour accent"><?php echo formatDate($tour["date-tour"]) ?></p>
                            </div>
                            <div class="d-flex flex-row column-gap-2 align-items-center">
                                <i class="icon fa-regular fa-clock"></i>
                                <p>Thời gian:</p>
                                <p class="id-tour accent"><?php echo htmlspecialchars($tour["duration-tour"]) ?></p>
                            </div>
                            <hr class="my-4">
                            <a href="lien-he" target="_blank">
                                <button class="button-destructive p-2 w-100">Đặt tour</button>
                            </a>
                        </div>
                    </div>    
                </div>
        <?php else : ?>
            <p>Bài viết không tồn tại.</p>
        <?php endif; ?>
                <div class="other-tours mt-5">
                    <h3 class="title-page">TOUR TƯƠNG TỰ</h3> 
                    <?php
                    //$sql .= "SELECT * FROM tour WHERE `id-tour` = ? AND `id-status-tour` = ? ORDER BY `created-at` DESC LIMIT 4";
                    $result = $conn->query($sql);
                    if ($result) {
                        if ($result->num_rows > 0) {
                            echo '<div class="tour-container row mt-4 justify-content-between">';
                                //Tour item
                                while ($row = $result->fetch_assoc()) {
                                    echo '<div class="col-3">';
                                        echo '<div class="tour-item d-flex flex-column pb-2 text-left border-round shadow-sm">';
                                            echo '<img class="tour-img w-100 object-fit-cover" src="resources/uploads/' . htmlspecialchars($row["img-tour"]) . '">';
                                            echo '<div class="tour-content d-flex flex-column p-3 row-gap-3 text-left">';
                                                echo '<a href="single-tour.php?id-tour=' . htmlspecialchars($row["id-tour"]) . '">
                                                <h6>' . htmlspecialchars($row["title-tour"]) . '</h6></a>';
                                                echo '<div class="d-flex flex-row column-gap-2 justify-space-center">
                                                    <i class="icon fa-solid fa-location-dot"></i>
                                                    <p> Khởi hành: '. htmlspecialchars($row["starting-gate"]) . '</p>
                                                    </div>';
                                                echo '<div class="d-flex flex-row column-gap-2 justify-space-center">
                                                    <i class="icon fa-solid fa-calendar-days"></i>
                                                    <p> Ngày khởi hành: '. formatDate($row["date-tour"]) . '</p>
                                                    </div>';
                                                echo '<div class="highlight tour-price">' . number_format($row["price-tour"], 0, ',', '.') . ' đ</div>';
                                                echo '<button class="button-primary w-full py-2 px-2">Đặt tour</button>';
                                            echo '</div>';
                                        echo '</div>';
                                    echo '</div>';
                                }
                            echo '</div>';
                            } else {
                                echo 'Không tìm thấy tour phù hợp';
                            }
                            echo '</div>';
                        } else {
                            echo 'Lỗi truy vấn: ' . $conn->error;
                        }
                    ?>
                    </div>
                </div>
    </main>
    <?php include "footer.php" ?>
</body>

<?php
    // Đóng kết nối
    $conn->close();
?>

</html>