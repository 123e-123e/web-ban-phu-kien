<?php
session_start();
require_once __DIR__ . '/../database/connect-sql.php';

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $login_id = trim($_POST['username']); 
    $password = $_POST['password'];

    try {
        $stmt = $conn->prepare("SELECT id, username, password, role FROM users_account WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $login_id, $login_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row['password'])) {
                
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['role'] = $row['role'];

                if ($row['role'] === 'admin') {
                    header("Location: /index1.php");
                } else {
                    header("Location: /index.php");
                }
                exit();

            } else {
                $msg = "<p style='color: #ff3333; margin-bottom: 15px;'>Mật khẩu không chính xác!</p>";
            }
        } else {
            $msg = "<p style='color: #ff3333; margin-bottom: 15px;'>Tài khoản không tồn tại!</p>";
        }
        $stmt->close();
    } catch (mysqli_sql_exception $e) {
        $msg = "<p style='color: #ff3333; margin-bottom: 15px;'>Lỗi hệ thống: " . $e->getMessage() . "</p>";
    }
}
require_once __DIR__ . '/../database/disconnect-sql.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
    <link rel="icon" type="image/svg+xml" sizes="48x48" href="img/favicon/favicon.svg">
<title>Đăng Nhập - LaptopZZ</title>

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
    max-width: 420px;
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
    margin-bottom: 30px;
    letter-spacing: 2px;
}

/* INPUT */
.input-group {
    margin-bottom: 20px;
    text-align: left;
}

.input-group label {
    display: block;
    margin-bottom: 8px;
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

    <a href="/index.php" class="back-home">← Trang chủ</a>

    <h2>ĐĂNG NHẬP</h2>

    <?php echo $msg; ?>

    <form action="" method="POST">
        <div class="input-group">
            <label>Tên tài khoản / Email</label>
            <input type="text" name="username" required>
        </div>

        <div class="input-group">
            <label>Mật khẩu</label>
            <input type="password" name="password" required>
        </div>

        <button type="submit" name="login" class="btn-neon">Xác nhận</button>
    </form>

    <div class="switch-link">
        Chưa có tài khoản? <a href="register.php">Đăng ký</a>
    </div>

</div>
</body>
</html>
