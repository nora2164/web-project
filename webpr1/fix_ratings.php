<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "booksummaries";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("❌ فشل الاتصال: " . $conn->connect_error);
}

// Create ratings table
$sql = "CREATE TABLE IF NOT EXISTS ratings (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    book_id INT(6) UNSIGNED,
    rating TINYINT(1) NOT NULL,
    user_ip VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (book_id) REFERENCES books(id),
    UNIQUE KEY unique_rating (book_id, user_ip)
)";

if ($conn->query($sql) === TRUE) {
    echo "✅ تم إنشاء جدول التقييمات<br>";
} else {
    echo "❌ خطأ في إنشاء جدول التقييمات: " . $conn->error . "<br>";
}

// Add rating columns to books table if they don't exist
$sql = "SHOW COLUMNS FROM books LIKE 'average_rating'";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
    $sql = "ALTER TABLE books ADD COLUMN average_rating DECIMAL(3,2) DEFAULT 0.00";
    if ($conn->query($sql) === TRUE) {
        echo "✅ تم إضافة عمود average_rating<br>";
    }
}

$sql = "SHOW COLUMNS FROM books LIKE 'rating_count'";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
    $sql = "ALTER TABLE books ADD COLUMN rating_count INT(6) DEFAULT 0";
    if ($conn->query($sql) === TRUE) {
        echo "✅ تم إضافة عمود rating_count<br>";
    }
}

echo "🎉 اكتمل إصلاح الجداول!";
$conn->close();
?>