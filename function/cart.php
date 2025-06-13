<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

// افزودن محصول به سبد خرید
if (isset($_GET['action']) && $_GET['action'] == 'add' && isset($_GET['id']) && isset($_GET['quantity'])) {
    $product_id = $_GET['id'];
    $quantity = $_GET['quantity'];

    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = $quantity;
    } else {
        $_SESSION['cart'][$product_id] += $quantity;
    }

    header('Location: cart.php');
    exit;
}

// حذف یک واحد از محصول
if (isset($_GET['action']) && $_GET['action'] == "remove" && isset($_GET['id'])) {
    $product_id = $_GET['id'];

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]--;

        if ($_SESSION['cart'][$product_id] <= 0) {
            unset($_SESSION['cart'][$product_id]);
        }
    }
    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>سبد خرید</title>
    <link rel="stylesheet" href="../CSSs/cart.css">
</head>
<body>
    <div class="container">
        <h2>سبد خرید شما</h2>

        <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
            <?php
            $totalPrice = 0;
            foreach ($_SESSION['cart'] as $product_id => $quantity):
                $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
                $stmt->execute([$product_id]);
                $product = $stmt->fetch(PDO::FETCH_ASSOC);
                if (!$product) continue;

                $subtotal = $product['price'] * $quantity;
                $totalPrice += $subtotal;
            ?>
                <div class="cart-card">
                    <div class="name"><?= htmlspecialchars($product['name']) ?></div>
                    <div class="quantity">تعداد: <?= $quantity ?></div>
                    <div class="price"><?= number_format($subtotal) ?> تومان</div>
                </div>
            <?php endforeach; ?>

            <div class="total">مجموع کل: <?= number_format($totalPrice) ?> تومان</div>

            <form action="checkout.php" method="POST">
                <button type="submit" class="checkout-btn">تایید و پرداخت</button>
            </form>

            <a href="showpage/products.php">
                <button class="secondary-btn">انتخاب محصولات بیشتر</button>
            </a>
        <?php else: ?>
            <div class="empty-message">سبد خرید شما خالی است.</div>
            <a href="showpage/dashboard.php">
                <button class="secondary-btn">بازگشت به داشبورد</button>
            </a>
        <?php endif; ?>
    </div>
</body>
</html>
