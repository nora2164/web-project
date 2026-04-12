<?php
include 'config.php';

echo "<h3>فحص جدول التقييمات</h3>";

// التحقق من وجود الجدول
$result = $conn->query("SHOW TABLES LIKE 'ratings'");
if ($result->num_rows > 0) {
    echo "✅ جدول ratings موجود<br>";
} else {
    echo "❌ جدول ratings غير موجود<br>";
    
    // إنشاء الجدول
    $sql = "CREATE TABLE ratings (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        book_id INT(6) UNSIGNED,
        rating TINYINT(1) NOT NULL,
        user_ip VARCHAR(45),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (book_id) REFERENCES books(id),
        UNIQUE KEY unique_rating (book_id, user_ip)
    )";
    
    if ($conn->query($sql) === TRUE) {
        echo "✅ تم إنشاء جدول ratings<br>";
    } else {
        echo "❌ فشل إنشاء الجدول: " . $conn->error . "<br>";
    }
}

// التحقق من الأعمدة في books
$columns = ['average_rating', 'rating_count'];
foreach ($columns as $column) {
    $result = $conn->query("SHOW COLUMNS FROM books LIKE '$column'");
    if ($result->num_rows > 0) {
        echo "✅ عمود $column موجود<br>";
    } else {
        echo "❌ عمود $column غير موجود<br>";
    }
}

$conn->close();
?>