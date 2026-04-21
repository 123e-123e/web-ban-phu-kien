
<?php
session_start();
$pageTitle = "Trang chủ";
require "main/header.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Chủ</title>
    <link rel="stylesheet" src="style/main.css">
    <link rel="icon" type="image/svg+xml" sizes="48x48" href="../img/favicon/favicon.svg">
    <link rel="stylesheet" href="style/style.css">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

</head>
<body>
    <!-- Header và Nav -->
    <?php include "main/header.php"; ?>
    <?php include "main/nav.php"; ?>

    <!-- Quảng cáo/Slider -->
    <div class="slider-section">
        <?php include "main/slide-side.php"; ?>
    </div>

    <!-- Danh mục -->
    <div class="categories-section">
        <?php include "main/danhmuc.php"; ?>
    </div>

    <!-- Aside bar nếu cần -->
    <?php include "main/aside-bar.php"; ?>

    <?php include "main/sanpham.php"; ?>

    <!-- Footer -->
    <?php include "main/footer.php"; ?>

    <script src="script/script.js"></script>

</body>
</html>
