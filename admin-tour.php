<?php
include 'partical/db_connect.php';
include 'includes/functions.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Quản lý tour</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="description" content=""/>
    <meta http-equiv="refresh" content="900">
</head>

<body>
    <?php include 'header-blank.php'; ?>
    <main class="admin-tour">
        <h3>Tour</h3>
        <div class="row justify-content-between mt-4">
            <div class="col d-flex flex-row column-gap-2 align-items-center">
                <button class="button-light-background p-2 w-25" href="admin-new-tour.php">Thêm mới</button>
                <button class="button-light-background p-2 w-25" href="">Xóa</button>
            </div>
            <div class="col d-flex flex-row column-gap-2 align-items-center justify-content-end">
                <form>
                    <input class="p-2 border-round" type="text">
                    <button class="button-light-background p-2" type="submit">Tìm</button>
                </form>
            </div>
        </div>
        <div class="mt-4">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Tên tour</th>
                        <th scope="col">Mã tour</th>
                        <th scope="col">Giá</th>
                        <th scope="col">Ngày khởi hành</th>
                        <th scope="col">Thời gian</th>
                        <th scope="col">Mã tour</th>
                    </tr>
                </thead>
            </table>
        </div>
    </main>
</body>
</html>