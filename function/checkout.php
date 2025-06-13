<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
    echo "سبد خرید شما خالی است!";
    exit;
}

// متغیرهای مورد نیاز برای سفارش
$user_id = $_SESSION['user_id'];
$total_price = 0;

// ایجاد سفارش در دیتابیس
$stmt = $pdo->prepare("INSERT INTO orders (user_id, total_price) VALUES (?, ?)");
$stmt->execute([$user_id, $total_price]);
$order_id = $pdo->lastInsertId();

// افزودن محصولات به جدول order_items
foreach ($_SESSION['cart'] as $product_id => $quantity) {
    $stmt = $pdo->prepare("SELECT price FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        $price = $product['price'];
        $item_total = $price * $quantity;
        $total_price += $item_total;

        // اضافه کردن آیتم‌ها به جدول order_items
        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt->execute([$order_id, $product_id, $quantity, $price]);
    }
}

// آپدیت مبلغ کل سفارش
$stmt = $pdo->prepare("UPDATE orders SET total_price = ? WHERE id = ?");
$stmt->execute([$total_price, $order_id]);

// پاک کردن سبد خرید
unset($_SESSION['cart']);

echo "سفارش شما با موفقیت ثبت شد!";
echo '<br><a href="showpage/dashboard.php"><button style="padding: 10px 20px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; font-size: 16px; cursor: pointer;">بازگشت به داشبورد</button></a>';
?>
