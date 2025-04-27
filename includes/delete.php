<?php
//Xoa tour
if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['deleted-tour'])) {
    if (isset($_POST['tours'])) {
        $tours_to_delete = $_POST['tours'];
        $tour_ids = implode(',', array_map('intval', $tours_to_delete));
        $sql_delete = "DELETE FROM tour WHERE `id-tour` IN  ($tour_ids)";
        if ($conn->query($sql_delete) === TRUE) {
            echo '<script>
                alert("Xóa thành công");</script>';
        } else {
            echo 'Lỗi' . $conn->error;
        }
    }
}

//Xoa bai viet
if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['deleted-post'])) {
    if (isset($_POST['posts'])) {
        $posts_to_delete = $_POST['posts'];
        $post_ids = implode(',', array_map('intval', $posts_to_delete));
        $sql_delete = "DELETE FROM post WHERE `id-post` IN ($post_ids)";
        if ($conn->query($sql_delete) === TRUE) {
            echo '<script>
                alert("Xóa thành công");</script>';
        } else {
            echo 'Lỗi' . $conn->error;
        }
    }
}

//Xoa customer
if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['deleted-customer'])) {
    if (isset($_POST['customers'])) {
        $customers_to_delete = $_POST['customers'];
        $customer_ids = implode(',', array_map('intval', $customers_to_delete));
        $sql_delete = "DELETE FROM customer WHERE `id-customer` IN ($customer_ids)";
        if ($conn->query($sql_delete) === TRUE) {
            echo '<script>
                alert("Xóa thành công");</script>';
        } else {
            echo 'Lỗi' . $conn->error;
        }
    }
}

//Xoa category-post
if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['deleted-category-post'])) {
    if (isset($_POST['categories_post'])) {
        $customers_to_delete = $_POST['categories_post'];
        $customer_ids = implode(',', array_map('intval', $customers_to_delete));
        $sql_delete = "DELETE FROM `category-post` WHERE `id-category-post` IN ($customer_ids)";
        if ($conn->query($sql_delete) === TRUE) {
            echo '<script>
                alert("Xóa thành công");</script>';
        } else {
            echo 'Lỗi' . $conn->error;
        }
        header("Location: ../admin?page=admin-category-post");
    }
}
?>
