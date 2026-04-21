<header>
    <img height="50px" width="20px" src="img/logo/logo.svg" alt="logo-laptopz1" id="logo">
    <form action="functions/search-view.php" method="post">
        <input type="text" placeholder="Nhập sản phẩm bạn muốn tìm kiếm...">
        <button type="submit" id="search"><ion-icon name="search"></ion-icon></button>

        <?php if (isset($_SESSION['user_id'])): ?>
            <span style="color: #333; font-weight: bold; font-size: 14px;">
                Chào, <?php echo htmlspecialchars($_SESSION['username']); ?>
            </span>
            
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <a href="/index1.php" style="color: #a020f0;">Quản trị</a>
            <?php endif; ?>

            <a href="/account/logout.php" style="color: #ff3333;">Đăng Xuất</a>
        <?php else: ?>
            <a href="/account/login.php">Đăng Nhập</a>
            <a href="/account/register.php">Đăng Ký</a>
        <?php endif; ?>

        <a href="elements/favorites.php"><ion-icon name="heart"></ion-icon></a>
        <a href="elements/view-cart.php"><ion-icon name="cart"></ion-icon></a>
    </form>
</header>
