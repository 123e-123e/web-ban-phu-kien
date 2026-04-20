<?php
$servername = "localhost";
$username = "root";
$password = "P@ssw0rd";
$dbname = "nguyentheanh_db";

// Bật chế độ báo lỗi exception cho MySQLi
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    $conn->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    die("Dừng hệ thống - Lỗi kết nối Database: " . $e->getMessage());
}
?>