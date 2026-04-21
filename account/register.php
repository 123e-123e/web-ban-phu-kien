<?php
session_start();
require_once __DIR__ . '/../database/connect-sql.php';

$msg = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $msg = "<p style='color: #ff3333; margin-bottom: 15px;'>Mật khẩu xác nhận không khớp!</p>";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        try {
            $stmt = $conn->prepare("INSERT INTO users_account (username, email, password, role) VALUES (?, ?, ?, 'user')");
            $stmt->bind_param("sss", $username, $email, $hashed_password);
            $stmt->execute();
            $msg = "<p style='color: #ff9999; margin-bottom: 15px;'>Khởi tạo thành công! <a href='login.php' style='color:#ff3333'>Đăng nhập ngay</a></p>";
            $stmt->close();
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) {
                $msg = "<p style='color: #ff3333; margin-bottom: 15px;'>Tên tài khoản hoặc Email đã tồn tại!</p>";
            } else {
                $msg = "<p style='color: #ff3333; margin-bottom: 15px;'>Lỗi hệ thống: " . $e->getMessage() . "</p>";
            }
        }
    }
}
require_once __DIR__ . '/../database/disconnect-sql.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<link rel="icon" href="../img/favicon/favicon.svg" type="image/png">
<title>Đăng Ký - LaptopZZ</title>

<style>
* { box-sizing: border-box; margin: 0; padding: 0; }

body {
    font-family: 'Segoe UI', sans-serif;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: url("../img/favicon/background.svg") center/cover fixed;
    color: #fff;
        
    background-size: 100% auto; 
    background-position: top center; 
    background-color: #ffffff; 
    
    background-repeat: no-repeat;
}

/* CARD */
.auth-card {
    background: rgba(10, 10, 25, 0.7);
    backdrop-filter: blur(15px);
    border: 1px solid rgba(255, 51, 51, 0.4);
    border-radius: 15px;
    padding: 40px;
    width: 100%;
    max-width: 450px;
    position: relative;
    text-align: center;
    box-shadow: 0 0 25px rgba(255, 51, 51, 0.2);
}

/* BACK */
.back-home {
    position: absolute;
    top: 15px;
    left: 20px;
    color: #ff9999;
    text-decoration: none;
    font-size: 0.85em;
}

.back-home:hover {
    color: #ff3333;
}

/* TITLE */
h2 {
    color: #ff9999;
    text-shadow: 0 0 10px #ff3333;
    margin-bottom: 25px;
    letter-spacing: 2px;
}

/* INPUT */
.input-group {
    margin-bottom: 15px;
    text-align: left;
}

.input-group label {
    display: block;
    margin-bottom: 5px;
    color: #ccc;
    font-size: 0.9em;
}

.input-group input {
    width: 100%;
    padding: 12px;
    background: rgba(255, 255, 255, 0.05);
    border: 2px solid rgba(255, 51, 51, 0.2);
    color: #fff;
    border-radius: 8px;
    outline: none;
}

.input-group input:focus {
    border-color: #ff3333;
    box-shadow: 0 0 10px #ff3333;
}

/* BUTTON */
.btn-neon {
    width: 100%;
    padding: 12px;
    background: transparent;
    color: #fff;
    border: 2px solid #ff3333;
    border-radius: 8px;
    font-weight: bold;
    cursor: pointer;
    transition: 0.4s;
    text-transform: uppercase;
}

.btn-neon:hover {
    background: rgba(255, 51, 51, 0.2);
    box-shadow: 0 0 15px #ff3333;
}

/* LINK */
.switch-link {
    margin-top: 20px;
    font-size: 0.9em;
}

.switch-link a {
    color: #ff9999;
    text-decoration: none;
    font-weight: bold;
}

.switch-link a:hover {
    color: #ff3333;
}
</style>

</head>

<body>
<div class="auth-card">

    <a href="/index.php" class="back-home">← Quay lại</a>

    <h2>ĐĂNG KÝ</h2>

    <?php echo $msg; ?>

    <form action="" method="POST">
        <div class="input-group">
            <label>Tên tài khoản</label>
            <input type="text" name="username" required>
        </div>

        <div class="input-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>

        <div class="input-group">
            <label>Mật khẩu</label>
            <input type="password" name="password" required>
        </div>

        <div class="input-group">
            <label>Xác thực mật khẩu</label>
            <input type="password" name="confirm_password" required>
        </div>

        <button type="submit" name="register" class="btn-neon">Khởi tạo</button>
    </form>

    <div class="switch-link">
        Đã có tài khoản? <a href="login.php">Đăng nhập</a>
    </div>

</div>
</body>
</html>
