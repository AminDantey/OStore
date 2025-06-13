<?php
session_start();
require '../../config/db.php';
//header("Content-Type: application/json; charset=UTF-8"); برای وقتی که بخوایم به صورت جیسون اطلاعات رو برگردونیم


// مقدار اولیه برای متغیرهای جستجو و فیلتر
$query = "SELECT * FROM products WHERE 1=1";
$params = []; //یک آرایه خالی برای ذخیره پارامترهای فیلتر شده به‌طور داینامیک و استفاده از آنها در query.


if (!empty($_GET['search'])) { // چک کردن مقدار فیلد نام
    $query .= " AND name LIKE ?";
    $params[] = "%" . trim($_GET['search']) . "%";
}


if (!empty($_GET['min_price']) && !empty($_GET['max_price'])) { // چک کردن مقدار قیمت‌ها
    $query .= " AND price BETWEEN ? AND ?";
    $params[] = $_GET['min_price'];
    $params[] = $_GET['max_price'];
}



// اجرای query و گرفتن نتیجه
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);


//echo json_encode($products, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT); برای وقتی که بخوایم به صورت جیسون اطلاعات رو برگردونیم،در نتیجه نیازی به کد زیر (اچ تی ام ال) نیست!
?>

<!DOCTYPE html>
<html lang="fa">

<head>
    <meta charset="UTF-8">
    <title>محصولات</title>
    <link rel="stylesheet" href="../../CSSs/modern.css">
</head>

<body>
    <h1>لیست محصولات</h1>
    <div class="container">
        <div class="form-container">
            <form method="GET" action="products.php">
                <label for="search">:جستجو</label>
                <input type="text" name="search" id="search" placeholder="نام محصول را وارد کنید">

                <br>
                :یا بر اساس قیمت (هردو را پر کنید)
                <br>
                <label for="min_price">:حداقل قیمت</label>
                <input type="number" name="min_price" id="min_price" placeholder="حداقل قیمت">

                <br>

                <label for="max_price">:حداکثر قیمت</label>
                <input type="number" name="max_price" id="max_price" placeholder="حداکثر قیمت">

                <br>
                <button> <a class="link" href="dashboard.php">Go to Dashboard</a></button>
                <button type="submit">جستجو</button>

                <form action=""> <button type="submit">refresh</button> </form>


            </form>
        </div>

        <div class="list-container">
            <ul>
                <?php foreach ($products as $product): ?>

                    <ol class="ol">
                        <h3><?php echo $product['name']; ?></h3>
                        <p>قیمت: <?php echo number_format($product['price']); ?> تومان</p>
                        <div class="product-actions">
                            <a href="../product.php?id=<?php echo $product['id']; ?>">جزئیات محصول</a>
                            <a href="../cart.php?action=add&id=<?php echo $product['id']; ?>&quantity=1">افزودن به سبد</a>
                        </div>
                    </ol>

                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</body>

</html>