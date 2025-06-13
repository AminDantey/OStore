<?php
session_start();
require '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // بررسی reCAPTCHA
    $recaptcha_secret = "6Ld_0usqAAAAAFWk1W0jzcRsyQwqDgHBKT7Ws1tC";
    $recaptcha_response = $_POST['g-recaptcha-response'];

    if (!isset($recaptcha_response) || empty($recaptcha_response)) {
        die("خطا: لطفاً reCAPTCHA را تأیید کنید.");
    }

    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$recaptcha_secret&response=$recaptcha_response");
    $response_keys = json_decode($response, true);

    if (intval($response_keys["success"]) !== 1) {
        die("اعتبارسنجی reCAPTCHA شکست خورد. لطفاً دوباره تلاش کنید.");
    }

    // بررسی کاربر در دیتابیس
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: ../function/showpage/dashboard.php");
        exit;
    } else {
        $error = "نام کاربری یا رمز عبور اشتباه است!";
    }
}
?>

<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>ورود به حساب کاربری</title>
    <link rel="stylesheet" href="../CSSs/login.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <div class="login-container">
        <h2>ورود به حساب کاربری</h2>

        <?php if (isset($error)): ?>
            <div class="error-message"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" class="login-form">
            <label>نام کاربری:</label>
            <input type="text" name="username" required placeholder="نام کاربری خود را وارد کنید">

            <label>رمز عبور:</label>
            <input type="password" name="password" required placeholder="رمز عبور را وارد کنید">

            <div class="g-recaptcha" data-sitekey="6Ld_0usqAAAAADnsVYzqNgMCH0NZ31lF6GTD-OL8"></div>

            <button type="submit">ورود</button>
        </form>

        <hr>

        <form action="signup.php" method="POST" class="signup-form">
            <p>ثبت‌نام نکرده‌اید؟</p>
            <label>نام کاربری:</label>
            <input type="text" name="usernameS" required placeholder="نام کاربری دلخواه">

            <label>رمز عبور:</label>
            <input type="password" name="passwordS" required placeholder="رمز عبور قوی وارد کنید">

            <button type="submit">ثبت‌نام در سایت پرتو</button>
        </form>
    </div>
</body>
</html>
