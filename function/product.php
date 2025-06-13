<?php
session_start();

require '../config/db.php';

header("Content-Type: application/json; charset=UTF-8");

//check if product id is provided
if (!isset($_GET['id'])) {
    echo json_encode(["error" => "Product ID is required"]);
    exit;
}

$id = $_GET['id'];


$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]); 
$product = $stmt->fetch(PDO::FETCH_ASSOC);


if (!$product) {
    echo json_encode(["error" => "Product not found"]);
    exit;
}

echo json_encode($product, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT); 


//-----------------------  -----------------------//


if (isset($_GET['id'])) {
    $product_id = $_GET['id']; 
    $quantity = 1;  

    // agar session cart vojod nadasht, eejadesh mikone
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = []; 
    }

    // agar product tekrari bod +1 kon va agar nabod ejadesh kon
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity; 
        } else {
        $_SESSION['cart'][$product_id] = $quantity;  
    }

    echo "محصول با موفقیت به سبد خرید اضافه شد.";
}

?>
