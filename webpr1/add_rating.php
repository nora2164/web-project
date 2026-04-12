<?php
include 'config.php';

// بيانات المدخلات
$book_id = isset($_POST['book_id']) ? intval($_POST['book_id']) : 0;
$rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;

// التحقق من البيانات
if ($book_id > 0 && $rating >= 1 && $rating <= 5) {
    $user_ip = $_SERVER['REMOTE_ADDR'];
    
    // استخدام prepared statements لمنع SQL injection
    $check_sql = "SELECT id FROM ratings WHERE book_id = ? AND user_ip = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("is", $book_id, $user_ip);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result && $check_result->num_rows > 0) {
        // تحديث التقييم
        $sql = "UPDATE ratings SET rating = ? WHERE book_id = ? AND user_ip = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $rating, $book_id, $user_ip);
    } else {
        // إدخال جديد
        $sql = "INSERT INTO ratings (book_id, rating, user_ip) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $book_id, $rating, $user_ip);
    }
    
    // تنفيذ الاستعلام
    if ($stmt->execute()) {
        // تحديث الإحصائيات
        $avg_sql = "SELECT AVG(rating) as avg_rating, COUNT(*) as rating_count FROM ratings WHERE book_id = ?";
        $avg_stmt = $conn->prepare($avg_sql);
        $avg_stmt->bind_param("i", $book_id);
        $avg_stmt->execute();
        $avg_result = $avg_stmt->get_result();
        
        if ($avg_result && $avg_data = $avg_result->fetch_assoc()) {
            $avg_rating = round($avg_data['avg_rating'], 1);
            $rating_count = $avg_data['rating_count'];
            
            $update_book = "UPDATE books SET average_rating = ?, rating_count = ? WHERE id = ?";
            $update_stmt = $conn->prepare($update_book);
            $update_stmt->bind_param("dii", $avg_rating, $rating_count, $book_id);
            $update_stmt->execute();
            $update_stmt->close();
        }
        $avg_stmt->close();
        
        header('Location: summary.php?id=' . $book_id . '&message=شكراً لتقييمك! تم حفظ التقييم بنجاح.');
    } else {
        header('Location: summary.php?id=' . $book_id . '&error=حدث خطأ في حفظ التقييم');
    }
    
    $stmt->close();
    $check_stmt->close();
} else {
    header('Location: summary.php?id=' . $book_id . '&error=تقييم غير صالح. الرجاء اختيار تقييم بين 1 و 5 نجوم');
}

$conn->close();
exit();
?>