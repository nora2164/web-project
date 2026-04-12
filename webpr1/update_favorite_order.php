<?php
include 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (isset($input['order']) && is_array($input['order'])) {
        $success = true;
        
        foreach ($input['order'] as $item) {
            $favorite_id = intval($item['favorite_id']);
            $display_order = intval($item['display_order']);
            
            $sql = "UPDATE favorites SET display_order = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $display_order, $favorite_id);
            
            if (!$stmt->execute()) {
                $success = false;
            }
            
            $stmt->close();
        }
        
        if ($success) {
            echo json_encode(['success' => true, 'message' => 'تم تحديث الترتيب بنجاح']);
        } else {
            echo json_encode(['success' => false, 'message' => 'حدث خطأ في تحديث الترتيب']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'بيانات غير صالحة']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'طريقة غير مسموحة']);
}

$conn->close();
?>