<?php
include 'config.php';

if (isset($_GET['book_id']) && isset($_GET['rating'])) {
    $book_id = intval($_GET['book_id']);
    $rating = intval($_GET['rating']);
    $user_ip = $_SERVER['REMOTE_ADDR'];
    
    if ($book_id > 0 && $rating >= 1 && $rating <= 5) {
        // إضافة أو تحديث التقييم
        $sql = "INSERT INTO ratings (book_id, rating, user_ip) VALUES (?, ?, ?) 
                ON DUPLICATE KEY UPDATE rating = VALUES(rating)";
        
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("iis", $book_id, $rating, $user_ip);
            $stmt->execute();
            $stmt->close();
            
            // تحديث متوسط التقييم
            $update_sql = "UPDATE books 
                          SET average_rating = (SELECT AVG(rating) FROM ratings WHERE book_id = ?),
                              rating_count = (SELECT COUNT(*) FROM ratings WHERE book_id = ?)
                          WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            if ($update_stmt) {
                $update_stmt->bind_param("iii", $book_id, $book_id, $book_id);
                $update_stmt->execute();
                $update_stmt->close();
            }
        }
    }
}

// العودة لصفحة الملخص
header("Location: summary.php?id=$book_id");
exit();
?>