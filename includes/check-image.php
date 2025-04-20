<?php

//Image tour
$target_dir = "../resources/uploads/";

if (isset($_FILES["img-tour"]) && $_FILES["img-tour"]["error"] == 0) {
    $original_file_name = basename($_FILES["img-tour"]["name"]);
    $imageFileType = strtolower(pathinfo($original_file_name, PATHINFO_EXTENSION));
    $target_file = $target_dir . $original_file_name;
    $counter = 1;

    while (file_exists($target_file)) {
        $target_file = $target_dir . pathinfo($original_file_name, PATHINFO_FILENAME) . '-' . $counter . '.' . $imageFileType;
        $counter++;
    }

    $check = getimagesize($_FILES["img-tour"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;

        if ($_FILES["img-tour"]["size"] > 500000000) {
            echo 'Dung lượng vượt quá mức cho phép.';
            $uploadOk = 0;
        }

        if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'svg', 'gif', 'webp'])) {
            echo 'File không nằm trong danh sách định dạng cho phép.';
            $uploadOk = 0;
        }

        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["img-tour"]["tmp_name"], $target_file)) {
                $img_tour = basename($target_file);
            } else {
                echo 'Có lỗi trong khi tải lên';
            }
        } else {
            echo 'File không được upload. Vui lòng thử lại.';
        }
    } else {
        echo 'File không phải là hình ảnh.';
    }
}

//Image post
if (isset($_FILES["img-post"]) && $_FILES["img-post"]["error"] == 0) {
    $original_file_name = basename($_FILES["img-post"]["name"]);
    $imageFileType = strtolower(pathinfo($original_file_name, PATHINFO_EXTENSION));
    $target_file = $target_dir . $original_file_name;
    $counter = 1;

    while (file_exists($target_file)) {
        $target_file = $target_dir . pathinfo($original_file_name, PATHINFO_FILENAME) . '-' . $counter . '.' . $imageFileType;
        $counter++;
    }

    $check = getimagesize($_FILES["img-post"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;

        if ($_FILES["img-post"]["size"] > 500000000) {
            echo 'Dung lượng vượt quá mức cho phép.';
            $uploadOk = 0;
        }

        if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'svg', 'gif', 'webp'])) {
            echo 'File không nằm trong danh sách định dạng cho phép.';
            $uploadOk = 0;
        }

        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["img-post"]["tmp_name"], $target_file)) {
                $img_post = basename($target_file);
            } else {
                echo 'Có lỗi trong khi tải lên';
            }
        } else {
            echo 'File không được upload. Vui lòng thử lại.';
        }
    } else {
        echo 'File không phải là hình ảnh.';
    }
}

//Avatar customer
if (isset($_FILES["avatar-customer"]) && $_FILES["avatar-customer"]["error"] == 0) {
    $original_file_name = basename($_FILES["avatar-customer"]["name"]);
    $imageFileType = strtolower(pathinfo($original_file_name, PATHINFO_EXTENSION));
    $target_file = $target_dir . $original_file_name;
    $counter = 1;

    while (file_exists($target_file)) {
        $target_file = $target_dir . pathinfo($original_file_name, PATHINFO_FILENAME) . '-' . $counter . '.' . $imageFileType;
        $counter++;
    }

    $check = getimagesize($_FILES["avatar-customer"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;

        if ($_FILES["avatar-customer"]["size"] > 500000000) {
            $error_message = 'Dung lượng vượt quá mức cho phép.';
            $uploadOk = 0;
        }

        if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'svg', 'gif', 'webp'])) {
            $error_message = 'File không nằm trong danh sách định dạng cho phép.';
            $uploadOk = 0;
        }

        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["avatar-customer"]["tmp_name"], $target_file)) {
                $avatar_customer = basename($target_file);
                $error_message = "Done";
            } else {
                $error_message = 'Có lỗi trong khi tải lên';
            }
        } else {
            $error_message = 'File không được upload. Vui lòng thử lại.';
        }
    } else {
        $error_message = 'File không phải là hình ảnh.';
    }
}
?>