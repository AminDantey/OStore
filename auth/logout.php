<?php
session_start();
session_unset();  // حذف تمام مقادیر سشن
session_destroy(); // تخریب سشن

header("Location: login.php"); // هدایت به صفحه لاگین
exit;
?>
