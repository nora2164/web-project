<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "booksummaries";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("فشل الاتصال: " . $conn->connect_error);
}

// Add display_order column to favorites table
$sql = "ALTER TABLE favorites ADD COLUMN display_order INT(6) DEFAULT 0";

if ($conn->query($sql) === TRUE) {
    echo "✅ تم إضافة العمود display_order بنجاح<br>";
} else {
    echo "❌ خطأ في إضافة العمود: " . $conn->error . "<br>";
}

// Update existing records with order
$update_sql = "UPDATE favorites SET display_order = id";
if ($conn->query($update_sql) === TRUE) {
    echo "✅ تم تحديث البيانات الحالية<br>";
} else {
    echo "❌ خطأ في تحديث البيانات: " . $conn->error . "<br>";
}

echo "<h3 style='color: green;'>🎉 اكتمل تحديث قاعدة البيانات!</h3>";
echo "<a href='index.php' style='display: inline-block; padding: 10px 20px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; margin-top: 20px;'>اذهب إلى الصفحة الرئيسية</a>";

$conn->close();
?>