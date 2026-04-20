<?php
session_start();

// KIỂM TRA BẢO MẬT ADMIN
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: /account/login.php");
    exit();
}
// ... (code kết nối database bên dưới giữ nguyên)
// 2. KẾT NỐI DATABASE
require_once __DIR__ . '/database/connect-sql.php';

$successMsg = "";
$errorMsg = "";

// 3. XỬ LÝ XÓA TÀI KHOẢN
if (isset($_GET['delete_id'])) {
    $del_id = $_GET['delete_id'];
    if ($del_id != $_SESSION['user_id']) {
        $stmt = $conn->prepare("DELETE FROM users_account WHERE id = ?");
        $stmt->bind_param("i", $del_id);
        if ($stmt->execute()) {
            $successMsg = "Đã xóa tài khoản thành công!";
        } else {
            $errorMsg = "Lỗi khi xóa: " . $conn->error;
        }
        $stmt->close();
    } else {
        $errorMsg = "Bạn không thể tự xóa tài khoản của chính mình!";
    }
}

// 4. XỬ LÝ TẠO TÀI KHOẢN MỚI
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $conn->prepare("INSERT INTO users_account (username, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $hashed_password, $role);
        if ($stmt->execute()) {
            $successMsg = "Đã tạo tài khoản '$username' thành công!";
        }
        $stmt->close();
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) {
            $errorMsg = "Lỗi: Tên tài khoản hoặc Email đã tồn tại!";
        } else {
            $errorMsg = "Lỗi hệ thống: " . $e->getMessage();
        }
    }
}

// 5. LẤY DANH SÁCH TÀI KHOẢN
$users = [];
$result = $conn->query("SELECT id, username, email, role, created_at FROM users_account ORDER BY id DESC");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Trị LaptopZZ</title>
    <link rel="stylesheet" href="/style/style_index.css?v=<?php echo time(); ?>">
</head>
<body>

    <header>
        <div class="container">
            <h1>Hệ Thống Quản Trị LaptopZZ</h1>
            <nav>
                <ul class="menu">
                    <li><a href="/index1.php" class="active">Quản lý Tài khoản</a></li>
                    <li><a href="/index.php" target="_blank">Xem Cửa Hàng</a></li>
                    <li><a href="/account/logout.php" style="color: #ffcccc;">Đăng xuất (<?php echo $_SESSION['username']; ?>)</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container">
        
                <h2><span style="font-size:25px;">Tạo tài khoản</span></h2>

        <p><span style="font-size: 12px; color: red;">* Thông tin được yêu cầu</span></p>

        <form method="post" action="index1.php">
            
            <div style="margin-bottom: 12px;">
                <label style="display: inline-block; width: 120px;">Tên tài khoản: <span style="color: red;">*</span></label>
                <input type="text" name="username" required>
            </div>

            <div style="margin-bottom: 12px;">
                <label style="display: inline-block; width: 120px;">E-mail: <span style="color: red;">*</span></label>
                <input type="email" name="email" required>
            </div>

            <div style="margin-bottom: 12px;">
                <label style="display: inline-block; width: 120px;">Mật khẩu: <span style="color: red;">*</span></label>
                <input type="password" name="password" required>
            </div>

            <div style="margin-bottom: 12px;">
                <label style="display: inline-block; width: 120px;">Quyền hạn:</label>
                <select name="role" style="width: 100%; max-width: 500px; padding: 0.5em; margin-top: 0.3em; font-size: 1em;">
                    <option value="user">Khách hàng (User)</option>
                    <option value="employee">Nhân viên (Employee)</option>
                    <option value="admin">Quản trị viên (Admin)</option>
                </select>
            </div>

            <div style="text-align: center; margin-top: 15px;">
                <button type="submit" name="submit">Lưu (Tạo mới)</button>
            </div>
        </form>

        <hr style="margin: 30px 0; border: 0; border-top: 1px solid #ddd;">

        <h3>Danh sách Tài khoản</h3>
        <?php if (count($users) > 0): ?>
            <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; border-collapse:collapse; background: #fff; margin-top: 10px;">
                <tr style="background:#003366; color:white;">
                    <th>ID</th>
                    <th>Tên tài khoản</th>
                    <th>Email</th>
                    <th>Quyền hạn</th>
                    <th>Ngày tạo</th>
                    <th>Hành động</th>
                </tr>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td style="text-align: center;"><?php echo $u['id']; ?></td>
                        <td><?php echo htmlspecialchars($u['username']); ?></td>
                        <td><?php echo htmlspecialchars($u['email']); ?></td>
                        <td style="text-align: center; font-weight: bold; color: <?php echo ($u['role'] == 'admin') ? 'red' : 'green'; ?>">
                            <?php echo strtoupper($u['role']); ?>
                        </td>
                        <td style="text-align: center;"><?php echo $u['created_at']; ?></td>
                        <td style="text-align: center;">
                            <a href="edit_user.php?id=<?php echo $u['id']; ?>" class="btn">Sửa</a>
                            
                            <?php if ($u['id'] != $_SESSION['user_id']): ?>
                                <a href="index1.php?delete_id=<?php echo $u['id']; ?>" class="btn btn-delete" onclick="return confirm('Bạn có chắc chắn muốn xóa tài khoản này không?');">Xóa</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>Chưa có tài khoản nào trong hệ thống.</p>
        <?php endif; ?>

    </main>

<footer class="site-footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section about">
                <h3 class="footer-heading">Thông Tin Về Chúng Tôi</h3>
                <p>Chuyên cung cấp các dòng LapTop, phụ kiện PC, phụ kiện Thông Minh chất lượng cao.</p>
                <div class="contact-info">
                    <p><i class="fas fa-map-marker-alt"></i> 1 Đường Võ Thị Sáu, Quận 1, TP. HCM</p>
                    <p><i class="fas fa-phone"></i> Hotline: 0902 030 456</p>
                    <p><i class="fas fa-envelope"></i> Email: Kn4267909@gmail.com</p>
                </div>
            </div>

            <div class="footer-section links">
                <h3 class="footer-heading">Chính Sách</h3>
                <ul>
                    <li><a href="#">Chính sách bảo hành</a></li>
                    <li><a href="#">Chính sách đổi trả</a></li>
                    <li><a href="#">Hình thức thanh toán</a></li>
                    <li><a href="#">Vận chuyển & Giao hàng</a></li>
                </ul>
            </div>

            <div class="footer-section newsletter">
                <h3 class="footer-heading">Đăng Ký Nhận Tin</h3>
                <p>Nhận thông tin khuyến mãi mới nhất từ chúng tôi.</p>
                <form action="" class="subscribe-form">
                    <input type="email" placeholder="Email của bạn..." required>
                    <button type="submit">Gửi</button>
                </form>
            </div>
        </div>

</body>
</html>
<?php require_once __DIR__ . '/database/disconnect-sql.php'; ?>