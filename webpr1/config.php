<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "booksummaries";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("❌ فشل الاتصال بقاعدة البيانات: " . $conn->connect_error . 
        "<br><br>➡ <a href='database_setup.php' style='color: #667eea; font-weight: bold;'>اضغط هنا لإنشاء قاعدة البيانات</a>");
}

// Set charset to UTF-8 for Arabic support
$conn->set_charset("utf8");
?>