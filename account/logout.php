<?php
// 1. Khởi động lại session để hệ thống nhận diện được người dùng hiện tại
session_start();

// 2. Xóa sạch toàn bộ các biến trong Session (như user_id, username, role)
$_SESSION = array();

// 3. Phá hủy hoàn toàn Session này trên máy chủ
session_destroy();

// 4. Điều hướng người dùng về lại trang Đăng nhập
header("Location: /account/login.php");
exit();
?>