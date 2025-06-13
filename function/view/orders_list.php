<?php
session_start();
require '../../config/db.php';

$stmt = $pdo->query("SELECT * FROM orders ORDER BY id DESC");
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>لیست سفارشات</title>
    <link rel="stylesheet" href="../../CSSs/orders_list.css">

</head>
<body>
    <h2> >لیست سفارشات ثبت شده< </h2>
    <table style="border: 3px solid black; border-collapse: collapse; width: 50%;">
        <tr>
            <th style="border: 1px solid black; width: 40%;">شناسه سفارش</th>
            <th style="border: 1px solid black; width: 50%;">مبلغ کل</th>
            <th style="border: 1px solid black; width: 50%;">عملیات</th>
        </tr>
        <?php foreach ($orders as $order): ?>
            <tr>
                <td style="border: 1px solid black; width: 40%;"><?= $order['id'] ?></td>
                <td style="border: 1px solid black; width: 40%;"><?= number_format($order['total_price']) ?> تومان</td>
                <td style="border: 1px solid black; width: 40%;"><a href="order_details.php?id=<?= $order['id'] ?>">مشاهده جزئیات</a></td>
            </tr>
            
        <?php endforeach; ?>
    </table>
    <button type="button" onclick="window.location.href='dashboard.php'">Go To Dashboard</button></button>
</body>
</html>
