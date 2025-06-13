<?php
session_start();
require '../../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit;
}


$order_id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    echo "سفارشی با این شناسه پیدا نشد.";
    exit;
}

// daryafte mahsoolate sefaresh
$stmt = $pdo->prepare("
    SELECT oi.quantity, p.name, p.price 
    FROM order_items oi 
    JOIN products p ON oi.product_id = p.id 
    WHERE oi.order_id = ?
");
$stmt->execute([$order_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fa">

<head>
    <meta charset="UTF-8">
    <title>جزئیات سفارش</title>
    <link rel="stylesheet" href="../../CSSs/order_details.css">
</head>

<body>
    <h2>جزئیات سفارش شماره <?= $order['id'] ?></h2>
    <p>مبلغ کل: <?= number_format($order['total_price']) ?> تومان</p>

    <h3>محصولات سفارش</h3>
    <table border="1">
        <tr>
            <th>نام محصول</th>
            <th>تعداد</th>
            <th>قیمت واحد</th>
            <th>قیمت کل</th>
        </tr>
        <?php foreach ($items as $item): ?>
            <tr>
                <td><?= $item['name'] ?></td>
                <td><?= $item['quantity'] ?></td>
                <td><?= number_format($item['price']) ?> تومان</td>
                <td><?= number_format($item['price'] * $item['quantity']) ?> تومان</td>
            </tr>
        <?php endforeach; ?>
    </table>
    <a href="orders_list.php">بازگشت به لیست سفارشات</a>
</body>

</html>