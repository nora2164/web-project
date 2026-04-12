<?php
include 'config.php';

if (isset($_GET['book_id'])) {
    $book_id = intval($_GET['book_id']);
    
    $sql = "DELETE FROM favorites WHERE book_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $book_id);
    
    if ($stmt->execute()) {
        header('Location: ' . $_SERVER['HTTP_REFERER'] . '?message=تمت إزالة الملخص من المفضلة');
    } else {
        header('Location: ' . $_SERVER['HTTP_REFERER'] . '?error=حدث خطأ في الإزالة');
    }
    
    $stmt->close();
} else {
    header('Location: favorites.php');
}

$conn->close();
?>