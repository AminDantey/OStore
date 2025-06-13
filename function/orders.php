<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

error_reporting(E_ALL);
ini_set('display_errors', 1);



print_r($_SESSION['cart']);
// اینجا چک می‌کنیم که دکمه ثبت سفارش کلیک شده باشه
//if (isset($_POST['submit_order'])) {
    $cart = $_SESSION['cart'];

    // calculate the total price:
    $total_price = 0;
    foreach ($cart as $product_id => $quantity) {
        echo"product_id:".$product_id ;
        $stmt = $pdo->prepare("SELECT price FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($product) {
            $total_price += $product['price'] * $quantity;
        }
     }

    // ذخیره سفارش در دیتابیس
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_price) VALUES (?, ?)");
    $stmt->execute([1, $total_price]);  // فرض کنید که user_id = 1 است

    // گرفتن شناسه سفارش جدید
    $order_id = $pdo->lastInsertId();

    // ذخیره محصولات سفارش در order_items
    foreach ($cart as $product_id => $quantity) {
        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->execute([$order_id, $product_id, $quantity]);
    }

    // پاک کردن سبد خرید بعد از ثبت سفارش
    unset($_SESSION['cart']);

    // نمایش پیام موفقیت با شناسه سفارش
    echo "سفارش شما با موفقیت ثبت شد! شناسه سفارش شما: " . $order_id;
//}
?>
