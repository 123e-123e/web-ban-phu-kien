<?php
session_start();

// 1. KIỂM TRA BẢO MẬT: Phải là admin mới được vào trang này
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: /account/login.php");
    exit();
}

require_once __DIR__ . '/database/connect-sql.php';

$successMsg = "";
$errorMsg = "";
$user = null;

// 2. LẤY THÔNG TIN NGƯỜI DÙNG CẦN SỬA
if (isset($_GET['id'])) {
    $edit_id = $_GET['id'];
    $stmt = $conn->prepare("SELECT id, username, email, role FROM users_account WHERE id = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        die("<h2 style='text-align:center; margin-top:50px;'>Không tìm thấy tài khoản! <a href='index1.php'>Quay lại</a></h2>");
    }
    $stmt->close();
} else {
    header("Location: index1.php");
    exit();
}

// 3. XỬ LÝ KHI BẤM NÚT "CẬP NHẬT"
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $new_username = trim($_POST['username']);
    $new_email = trim($_POST['email']);
    $new_role = $_POST['role'];

    // Cập nhật Database
    try {
        $stmt_update = $conn->prepare("UPDATE users_account SET username = ?, email = ?, role = ? WHERE id = ?");
        $stmt_update->bind_param("sssi", $new_username, $new_email, $new_role, $edit_id);
        
        if ($stmt_update->execute()) {
            $successMsg = "Đã cập nhật thông tin và quyền hạn thành công!";
            // Cập nhật lại biến $user để form hiển thị thông tin mới nhất
            $user['username'] = $new_username;
            $user['email'] = $new_email;
            $user['role'] = $new_role;
        }
        $stmt_update->close();
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) {
            $errorMsg = "Lỗi: Tên tài khoản hoặc Email này đã bị trùng với người khác!";
        } else {
            $errorMsg = "Lỗi hệ thống: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Tài Khoản - LaptopZZ</title>
    <link rel="stylesheet" href="/style/style_index.css?v=<?php echo time(); ?>"> 
</head>
<body>

    <header>
        <div class="container">
            <h1>Chỉnh Sửa Tài Khoản</h1>
            <nav>
                <ul class="menu">
                    <li><a href="/index1.php">← Quay lại danh sách</a></li>
                    <li><a href="/account/logout.php" style="color: #ffcccc;">Đăng xuất (<?php echo $_SESSION['username']; ?>)</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container">
        
        <h3 style="text-align: center; border-bottom: 2px solid #a020f0; padding-bottom: 10px; display: inline-block;">
            Đang sửa tài khoản: <span style="color: red;">ID <?php echo $user['id']; ?></span>
        </h3>

        <?php if ($successMsg): ?>
            <p style="color: green; font-weight: bold; text-align: center; background: #e6ffe6; padding: 10px; border-radius: 5px;"><?php echo $successMsg; ?></p>
        <?php endif; ?>
        <?php if ($errorMsg): ?>
            <p style="color: red; font-weight: bold; text-align: center; background: #ffe6e6; padding: 10px; border-radius: 5px;"><?php echo $errorMsg; ?></p>
        <?php endif; ?>

        <form method="post" action="edit_user.php?id=<?php echo $edit_id; ?>">
            
            <div style="margin-bottom: 15px;">
                <label style="display: inline-block; width: 120px;">Tên tài khoản:</label>
                <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: inline-block; width: 120px;">E-mail:</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: inline-block; width: 120px;">Quyền hạn:</label>
                <select name="role" style="width: 100%; max-width: 500px; padding: 0.5em; font-size: 1em;">
                    <option value="user" <?php if($user['role'] == 'user') echo 'selected'; ?>>Khách hàng (User)</option>
                    <option value="employee" <?php if($user['role'] == 'employee') echo 'selected'; ?>>Nhân viên (Employee)</option>
                    <option value="admin" <?php if($user['role'] == 'admin') echo 'selected'; ?>>Quản trị viên (Admin)</option>
                </select>
            </div>

            <div style="text-align: center; margin-top: 25px;">
                <button type="submit" name="update" style="padding: 10px 30px; font-size: 16px;">CẬP NHẬT THAY ĐỔI</button>
            </div>
        </form>

    </main>

    <footer>
        <div class="container">
            <p>&copy; 2026 Nguyễn Thế Anh. Quản trị viên.</p>
        </div>
    </footer>

</body>
</html>
<?php require_once __DIR__ . '/database/disconnect-sql.php'; ?>