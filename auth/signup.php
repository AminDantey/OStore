<?php
require '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['usernameS'];
    $password = $_POST['passwordS'];

    
    $stmt=$pdo->prepare("SELECT * FROM users WHERE username=?");
    $stmt->execute([$username]);
    $user=$stmt->fetch(PDO::FETCH_ASSOC);
   

    if ($user) {
        die("نام کاربری قبلاً استفاده شده است. لطفاً نام کاربری دیگری انتخاب کنید.<br> <button style='font-size: 15px'><a href='login.php'>ورود به صفحه نخست</a></button>");
    } else {
        // افزودن کاربر جدید به دیتابیس
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $stmt->execute([$username, $hashed_password]);

        echo "ثبت نام با موفقیت انجام شد.";
        echo "<br> <button style='font-size: 15px'><a href='../function/showpage/dashboard.php'>ورود به داشبورد</a></button>";    
    }
}
?>