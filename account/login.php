<?php
session_start();
// Gọi module kết nối từ thư mục database
require_once __DIR__ . '/../database/connect-sql.php';

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $login_id = trim($_POST['username']); 
    $password = $_POST['password'];

    try {
        // Tìm thực thể trong Database
        $stmt = $conn->prepare("SELECT id, username, password, role FROM users_account WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $login_id, $login_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            // Xác thực mật khẩu đã mã hóa
            if (password_verify($password, $row['password'])) {
                
                // Cấp thẻ Session để định danh quyền hạn
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['role'] = $row['role'];

                // === THUẬT TOÁN ĐIỀU PHỐI (ROUTING LOGIC) ===
                if ($row['role'] === 'admin') {
                    // Nếu là Admin: Đẩy về Trạm Kiểm Soát Hệ Thống (file index.php ở thư mục gốc)
                    header("Location: /index1.php");
                } else {
                    // Nếu là User: Đẩy về Trang chủ Cửa hàng phụ kiện
                    header("Location: /index.php");
                }
                exit();

            } else {
                $msg = "<p style='color: #ff4d4d; margin-bottom: 15px;'>Mật khẩu không chính xác!</p>";
            }
        } else {
            $msg = "<p style='color: #ff4d4d; margin-bottom: 15px;'>Tài khoản không tồn tại!</p>";
        }
        $stmt->close();
    } catch (mysqli_sql_exception $e) {
        $msg = "<p style='color: #ff4d4d; margin-bottom: 15px;'>Lỗi hệ thống: " . $e->getMessage() . "</p>";
    }
}
require_once __DIR__ . '/../database/disconnect-sql.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">

    <title>Đăng Nhập - LaptopZZ</title>
    <style>
        /* CSS tương tự register.php để đồng bộ giao diện Nhân Hoàng */
        * { box-sizing: border-box; margin: 0; padding: 0; }
               body { font-family: 'Segoe UI', sans-serif; min-height: 100vh; display: flex; align-items: center; justify-content: center; background: url("../img/img_index1/footer-bg.jpg") center/cover fixed; color: #fff; }
        .auth-card { background: rgba(10, 10, 25, 0.7); backdrop-filter: blur(15px); border: 1px solid rgba(160, 32, 240, 0.4); border-radius: 15px; padding: 40px; width: 100%; max-width: 420px; position: relative; text-align: center; }
        .back-home { position: absolute; top: 15px; left: 20px; color: #dfa8ff; text-decoration: none; font-size: 0.85em; }
        h2 { color: #dfa8ff; text-shadow: 0 0 10px #a020f0; margin-bottom: 30px; letter-spacing: 2px; }
        .input-group { margin-bottom: 20px; text-align: left; }
        .input-group label { display: block; margin-bottom: 8px; color: #ccc; font-size: 0.9em; }
        .input-group input { width: 100%; padding: 12px; background: rgba(255, 255, 255, 0.05); border: 2px solid rgba(160, 32, 240, 0.2); color: #fff; border-radius: 8px; outline: none; }
        .input-group input:focus { border-color: #a020f0; box-shadow: 0 0 10px #a020f0; }
        .btn-neon { width: 100%; padding: 12px; background: transparent; color: #fff; border: 2px solid #a020f0; border-radius: 8px; font-weight: bold; cursor: pointer; transition: 0.4s; text-transform: uppercase; }
        .btn-neon:hover { background: rgba(160, 32, 240, 0.2); box-shadow: 0 0 15px #a020f0; }
        .switch-link { margin-top: 20px; font-size: 0.9em; }
        .switch-link a { color: #dfa8ff; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
    <div class="auth-card">
        <a href="\..\index.php" class="back-home">← Trang chủ</a>
        <h2>ĐĂNG NHẬP</h2>
        <?php echo $msg; ?>
        <form action="" method="POST">
            <div class="input-group"><label>Tên tài khoản / Email</label><input type="text" name="username" required></div>
            <div class="input-group"><label>Mật khẩu</label><input type="password" name="password" required></div>
            <button type="submit" name="login" class="btn-neon">Xác nhận</button>
        </form>
        <div class="switch-link">Chưa có tài khoản? <a href="register.php">Đăng ký</a></div>
    </div>
</body>
</html>