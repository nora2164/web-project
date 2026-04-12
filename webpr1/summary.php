<?php
include 'config.php';

// Get book ID from URL
$book_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($book_id == 0) {
    header('Location: summaries.php');
    exit();
}

// Get book details
$sql = "SELECT * FROM books WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();
$book = $result->fetch_assoc();

if (!$book) {
    header('Location: summaries.php');
    exit();
}

// Check if book is in favorites
$favorite_sql = "SELECT * FROM favorites WHERE book_id = ?";
$favorite_stmt = $conn->prepare($favorite_sql);
$favorite_stmt->bind_param("i", $book_id);
$favorite_stmt->execute();
$is_favorite = $favorite_stmt->get_result()->num_rows > 0;
$favorite_stmt->close();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $book['title']; ?> - ملخصات</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <nav class="container">
            <div class="logo">📚 ملخصات</div>
            <ul class="nav-links">
                <li><a href="index.php">الرئيسية</a></li>
                <li><a href="summaries.php">جميع الملخصات</a></li>
                <li><a href="favorites.php">المفضلة</a></li>
            </ul>
        </nav>
    </header>

    <main class="container">
        <div style="background: white; border-radius: 15px; padding: 2rem; margin-top: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 2px solid #f0f0f0; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <h1 style="color: #333; margin: 0;"><?php echo $book['title']; ?></h1>
                    <div style="color: #666; margin-top: 0.5rem;">بواسطة: <?php echo $book['author']; ?></div>
                </div>
                <div style="display: flex; gap: 1rem; align-items: center;">
                    <div style="background: #e3f2fd; color: #1976d2; padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.9rem;">
                        ⏱ <?php echo $book['reading_time']; ?>
                    </div>
<!-- عرض التقييم -->
<div style="display: flex; align-items: center; gap: 0.5rem; margin-top: 0.5rem;">
    <div style="font-size: 0.9rem; color: #666;">التقييم:</div>
    <div style="display: flex; align-items: center; gap: 0.2rem;">
        <?php
        $avg_rating = $book['average_rating'] ?? 0;
        $rating_count = $book['rating_count'] ?? 0;
        
        // عرض النجوم
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= floor($avg_rating)) {
                echo '⭐'; // نجمة مملوءة
            } else if ($i == ceil($avg_rating) && fmod($avg_rating, 1) > 0) {
                echo '🌟'; // نجمة نصف مملوءة
            } else {
                echo '☆'; // نجمة فارغة
            }
        }
        
        echo " <span style='color: #666; font-size: 0.8rem; margin-right: 0.5rem;'>({$avg_rating} من {$rating_count} تقييم)</span>";
        ?>
    </div>
</div>

<!-- نموذج التقييم -->
<div style="margin-top: 1rem;">
    <form method="POST" action="add_rating.php" style="display: flex; align-items: center; gap: 0.5rem;">
        <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
        <div style="font-size: 0.9rem; color: #666;">قيم الكتاب:</div>
        <div style="display: flex; gap: 0.2rem;">
            <?php for ($i = 1; $i <= 5; $i++): ?>
                <button type="submit" name="rating" value="<?php echo $i; ?>" 
                        style="background: none; border: none; cursor: pointer; font-size: 1.2rem;"
                        onmouseover="highlightStars(this)" onmouseout="resetStars()">
                    ☆
                </button>
            <?php endfor; ?>
        </div>
    </form>
</div>
                    <?php if ($is_favorite): ?>
                        <a href="remove_favorite.php?book_id=<?php echo $book['id']; ?>" class="btn btn-secondary">إزالة من المفضلة</a>
                    <?php else: ?>
                        <a href="add_favorite.php?book_id=<?php echo $book['id']; ?>" class="btn">إضافة إلى المفضلة</a>
                    <?php endif; ?>
                </div>
            </div>

            <div style="background: #f8f9fa; padding: 2rem; border-radius: 10px; margin-bottom: 2rem;">
                <h2 style="color: #333; margin-bottom: 1rem;">📋 ملخص الكتاب</h2>
                <p style="line-height: 1.8; font-size: 1.1rem; color: #555; text-align: justify;">
                    <?php echo nl2br($book['summary']); ?>
                </p>
            </div>

            <?php if (!empty($book['key_points'])): ?>
            <div style="background: #fff3e0; padding: 2rem; border-radius: 10px;">
                <h2 style="color: #333; margin-bottom: 1rem;">🎯 النقاط الرئيسية</h2>
                <div style="line-height: 1.8; font-size: 1.1rem; color: #555;">
                    <?php echo nl2br($book['key_points']); ?>
                </div>
            </div>
            <?php endif; ?>

            <div style="margin-top: 2rem; padding-top: 1rem; border-top: 2px solid #f0f0f0;">
                <div style="display: flex; gap: 1rem; justify-content: center;">
                    <a href="summaries.php" class="btn btn-secondary">عودة للملخصات</a>
                    <?php if ($is_favorite): ?>
                        <a href="remove_favorite.php?book_id=<?php echo $book['id']; ?>" class="btn">إزالة من المفضلة</a>
                    <?php else: ?>
                        <a href="add_favorite.php?book_id=<?php echo $book['id']; ?>" class="btn btn-success">إضافة إلى المفضلة</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <footer style="background: rgba(0,0,0,0.8); color: white; text-align: center; padding: 2rem 0; margin-top: 3rem;">
        <div class="container">
            <p>© 2024 ملخصات - جميع الحقوق محفوظة</p>
        </div>
    </footer>
<!-- قسم التقييم المحسّن -->
<div style="background: #f8f9fa; padding: 1.5rem; border-radius: 10px; margin: 1.5rem 0; border: 2px solid #e3f2fd;">
    <h3 style="color: #333; margin-bottom: 1rem; text-align: center;">⭐ تقييم الكتاب</h3>
    
    <?php
    // جلب البيانات المحدثة من قاعدة البيانات
    $rating_sql = "SELECT average_rating, rating_count FROM books WHERE id = ?";
    $rating_stmt = $conn->prepare($rating_sql);
    $rating_stmt->bind_param("i", $book_id);
    $rating_stmt->execute();
    $rating_result = $rating_stmt->get_result();
    $rating_data = $rating_result->fetch_assoc();
    $rating_stmt->close();
    
    $avg_rating = $rating_data['average_rating'] ?? 0;
    $rating_count = $rating_data['rating_count'] ?? 0;
    
    // التحقق إذا كان المستخدم قد قام بالتقييم مسبقاً
    $user_ip = $_SERVER['REMOTE_ADDR'];
    $user_rating_sql = "SELECT rating FROM ratings WHERE book_id = ? AND user_ip = ?";
    $user_rating_stmt = $conn->prepare($user_rating_sql);
    $user_rating_stmt->bind_param("is", $book_id, $user_ip);
    $user_rating_stmt->execute();
    $user_rating_result = $user_rating_stmt->get_result();
    $user_has_rated = $user_rating_result->num_rows > 0;
    $user_rating = $user_has_rated ? $user_rating_result->fetch_assoc()['rating'] : 0;
    $user_rating_stmt->close();
    ?>
    
    <!-- عرض التقييم الحالي -->
    <div style="text-align: center; margin-bottom: 1.5rem;">
        <div style="display: inline-flex; align-items: center; background: white; padding: 0.8rem 1.5rem; border-radius: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <div style="font-size: 1.1rem; color: #333; font-weight: bold; margin-left: 1rem;">التقييم العام:</div>
            <div style="display: flex; align-items: center; gap: 0.3rem;">
                <?php
                // عرض النجوم للتقييم العام
                for ($i = 1; $i <= 5; $i++) {
                    if ($i <= $avg_rating) {
                        echo '<span style="font-size: 1.5rem; color: #ffc107;">⭐</span>';
                    } else if ($i == ceil($avg_rating) && fmod($avg_rating, 1) > 0) {
                        echo '<span style="font-size: 1.5rem; color: #ffc107;">🌟</span>';
                    } else {
                        echo '<span style="font-size: 1.5rem; color: #ddd;">☆</span>';
                    }
                }
                ?>
                <span style="color: #666; font-size: 1rem; background: #e3f2fd; padding: 0.3rem 0.8rem; border-radius: 15px; font-weight: bold;">
                    <?php echo round($avg_rating, 1); ?> / 5 (<?php echo $rating_count; ?> تقييم)
                </span>
            </div>
        </div>
    </div>

    <!-- نموذج التقييم -->
    <div style="text-align: center;">
        <?php if ($user_has_rated): ?>
            <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 10px; margin-bottom: 1rem; display: inline-block;">
                <span style="font-weight: bold;">شكراً لك!</span> لقد قمت بتقييم هذا الكتاب بـ 
                <span style="font-weight: bold; color: #e65100;"><?php echo $user_rating; ?> نجوم</span>
            </div>
            <br>
            <div style="color: #666; font-size: 0.9rem;">
                يمكنك تعديل تقييمك في أي وقت
            </div>
        <?php else: ?>
            <div style="color: #666; margin-bottom: 1rem; font-size: 1rem;">
                قيم هذا الكتاب من 1 إلى 5 نجوم:
            </div>
        <?php endif; ?>
        
        <form method="POST" action="add_rating.php" style="display: inline-block;">
            <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
            <div style="display: flex; gap: 0.3rem; justify-content: center; margin-bottom: 1rem;">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <button type="submit" name="rating" value="<?php echo $i; ?>" 
                            class="star-rating-btn <?php echo ($user_has_rated && $i <= $user_rating) ? 'rated' : ''; ?>"
                            data-rating="<?php echo $i; ?>"
                            onmouseover="highlightStars(<?php echo $i; ?>)" 
                            onmouseout="resetStars()">
                        <?php echo ($user_has_rated && $i <= $user_rating) ? '⭐' : '☆'; ?>
                    </button>
                <?php endfor; ?>
            </div>
            <div style="color: #666; font-size: 0.8rem; margin-top: 0.5rem;">
                انقر على النجمات للتقييم
            </div>
        </form>
    </div>
</div>

<script>
// دالة لتظليل النجوم عند التمرير
function highlightStars(rating) {
    const stars = document.querySelectorAll('.star-rating-btn');
    stars.forEach((star, index) => {
        if (index < rating) {
            star.innerHTML = '⭐';
            star.style.transform = 'scale(1.2)';
        }
    });
}

// دالة لإعادة النجوم لحالتها الأصلية
function resetStars() {
    const stars = document.querySelectorAll('.star-rating-btn');
    const currentRating = <?php echo $user_has_rated ? $user_rating : 0; ?>;
    
    stars.forEach((star, index) => {
        if (currentRating === 0 || index >= currentRating) {
            star.innerHTML = '☆';
        }
        star.style.transform = 'scale(1)';
    });
}

// إظهار تأكيد عند النقر على النجوم
document.querySelectorAll('.star-rating-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
        const rating = this.getAttribute('data-rating');
        if (!confirm(`هل تريد تقييم هذا الكتاب بـ ${rating} نجوم؟`)) {
            e.preventDefault();
        }
    });
});
</script>

<style>
.star-rating-btn {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 2rem;
    transition: all 0.3s ease;
    padding: 0.2rem;
}

.star-rating-btn:hover {
    transform: scale(1.3);
}

.star-rating-btn.rated {
    color: #ffc107;
}
</style></div>
</body>
</html>
<?php $conn->close(); ?>