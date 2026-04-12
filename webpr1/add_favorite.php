<?php
include 'config.php';

if (isset($_GET['book_id'])) {
    $book_id = intval($_GET['book_id']);
    
    // Check if book already exists in favorites
    $check_sql = "SELECT * FROM favorites WHERE book_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $book_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows == 0) {
        // Get current max display_order
        $order_sql = "SELECT MAX(display_order) as max_order FROM favorites";
        $order_result = $conn->query($order_sql);
        $max_order = $order_result->fetch_assoc()['max_order'] ?? 0;
        $new_order = $max_order + 1;
        
        // Add to favorites
        $sql = "INSERT INTO favorites (book_id, display_order) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $book_id, $new_order);
        
        if ($stmt->execute()) {
            header('Location: ' . $_SERVER['HTTP_REFERER'] . '?message=تمت إضافة الملخص إلى المفضلة بنجاح');
        } else {
            header('Location: ' . $_SERVER['HTTP_REFERER'] . '?error=حدث خطأ في الإضافة');
        }
        $stmt->close();
    } else {
        header('Location: ' . $_SERVER['HTTP_REFERER'] . '?error=الملخص موجود بالفعل في المفضلة');
    }
    $check_stmt->close();
} else {
    header('Location: summaries.php');
}

$conn->close();
?>