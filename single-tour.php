<?php
include 'partical/db_connect.php';
include 'includes/functions.php';

$slug = isset($_GET['id-tour']) ? $_GET['id-tour'] : '';

$sql = "SELECT * FROM tour WHERE `id-tour` = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $slug);
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
    <script src="https://kit.fontawesome.com/bbc8bd235c.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="resources/style.css">
    <meta http-equiv="refresh" content="900">
</head>

<body>
    <?php include "header-main.php" ?>
    <main class="container single-tour">
        <?php if ($tour) : ?>
            <div class="breadcrumb accent flex flex-row mt-4 column-gap-2">
                <li><a href="/nienluan.com/">Trang Chủ</a></li>
                <li><a href="/nienluan.com/tour">Tour du lịch</a></li>
                <li><a class="current"><?php echo htmlspecialchars($tour['title-tour']); ?></a></li>
            </div>
            <div class="title-page flex flex-col mt-6 text-center">
                <h3><?php echo htmlspecialchars($tour['title-tour']); ?></h3>
            </div>
            <div class="flex flex-row mt-6 column-gap-4">
                <div class="flex flex-col w-75 row-gap-2">
                    <div class="flex flex-row column-gap-4">
                        <div class="flex flex-col w-25 row-gap-2">
                            <img class="gallery-img-tour object-fit-cover" src="<?php echo htmlspecialchars($tour['img-tour']); ?>">
                        </div>
                        <div class="flex flex-col w-75 row-gap-2">
                            <img class="img-tour object-fit-cover" src="<?php echo htmlspecialchars($tour['img-tour']); ?>">
                        </div>
                    </div> 
                    <div class="mt-8">
                        <?php echo htmlspecialchars($tour['description-tour']); ?>
                    </div>
                </div>    
                <div class="info-tour relative w-25">
                    <div class="flex flex-col row-gap-2 px-4 py-4 sticky border-normal background-white shadow-md">
                        <div class="flex flex-col row-gap-4">
                            <div class="flex flex-col column-gap-2">
                                <h5>Giá:</h5>
                                <p class="text-light">(Người lớn - từ 12 tuổi trở lên)</p>
                                <div class="flex flex-wrap column-gap-2 align-items-center mt-2">
                                    <p class="price-tour"><?php echo number_format($tour['price-tour'], 0, ',', '.'); ?> đ </p>
                                    <p class="dvt">/ Khách</p>
                                </div>   
                            </div>
                        </div> 
                        <?php if (!is_null($tour['price-children-tour'])) : ?>
                            <div class="flex flex-row mt-2 justify-content-space-between align-items-center">
                                <div class="flex flex-col">
                                    <p class="accent">Trẻ em:</p>
                                    <p class="text-light">(Từ 2 - 11 tuổi)</p>
                                </div>
                                <p class="price-other-tour"><?php echo number_format($tour['price-children-tour'], 0, ',', '.'); ?> đ</p>
                            </div>
                        <?php endif; ?>
                        <?php if (!is_null($tour['price-baby-tour'])) : ?>
                            <div class="flex flex-row mt-2 justify-content-space-between align-items-center">
                                <div class="flex flex-col">
                                    <p class="accent">Em bé:</p>
                                    <p class="text-light">(Dưới 2 tuổi)</p>
                                </div>
                                <p class="price-other-tour"><?php echo number_format($tour['price-baby-tour'], 0, ',', '.'); ?> đ</p>
                            </div>
                        <?php endif; ?>
                        <hr class="my-4">
                        <div class="flex flex-row column-gap-2 align-items-center">
                            <i class="icon fa-classic fa-solid fa-circle-info fa-fw"></i>
                            <p>Mã chương trình: </p>
                            <p class="id-tour accent"><?php echo htmlspecialchars($tour["id-tour"]) ?></p>
                        </div>
                        <div class="flex flex-row column-gap-2 align-items-center">
                            <i class="icon fa-solid fa-location-dot"></i>
                            <p>Khởi hành:</p>
                            <p class="id-tour accent"><?php echo htmlspecialchars($tour["starting-gate"]) ?></p>
                        </div>
                        <div class="flex flex-row column-gap-2 align-items-center">
                            <i class="icon fa-solid fa-calendar-days"></i>
                            <p>Ngày khởi hành:</p>
                            <p class="id-tour accent"><?php echo formatDate($tour["date-tour"]) ?></p>
                        </div>
                        <div class="flex flex-row column-gap-2 align-items-center">
                            <i class="icon fa-regular fa-clock"></i>
                            <p>Thời gian:</p>
                            <p class="id-tour accent"><?php echo htmlspecialchars($tour["duration-tour"]) ?></p>
                        </div>
                        <hr class="my-4">
                        <button class="button-destructive px-4 py-4 w-full">Đặt tour</button>
                    </div>
                </div>    
            </div>
        <?php else : ?>
            <p>Bài viết không tồn tại.</p>
        <?php endif; ?>

    </main>
    <?php include "footer.php" ?>
</body>

<?php
    // Đóng kết nối
    $conn->close();
?>

</html>