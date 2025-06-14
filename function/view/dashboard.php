<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fa">
<link rel="stylesheet" href="../../CSSs/modern.css">

<head>
    <meta charset="UTF-8">
    <title>داشبورد</title>

</head>

<body>
    <h2 class="h2">! <?= htmlspecialchars($_SESSION['username']); ?> خوش اومدی</h2>
    <p class="p">شما با موفقیت وارد شدید</p>

    <div class="dashboard-cards">
        <a href="products.php" class="card">
            <div class="icon">📦</div>
            <div class="title">محصولات</div>
        </a>
        <a href="../cart.php" class="card">
            <div class="icon">🛒</div>
            <div class="title">سبد خرید</div>
        </a>
        <a href="orders_list.php" class="card">
            <div class="icon">📃</div>
            <div class="title">لیست سفارشات</div>
        </a>
        <a href="../../auth/logout.php" class="card logout-card">
            <div class="icon">🚪</div>
            <div class="title">خروج</div>
        </a>
    </div>
</body>


</html>