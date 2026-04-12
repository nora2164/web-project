<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>جميع الملخصات - ملخصات</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <nav class="container">
            <div class="logo">📚 ملخصات</div>
            <ul class="nav-links">
                <li><a href="index.php">الرئيسية</a></li>
                <li><a href="summaries.php" class="active">جميع الملخصات</a></li>
                <li><a href="categories.php">التصنيفات</a></li>
                <li><a href="favorites.php">المفضلة</a></li>
                <li><a href="about.php">من نحن</a></li>
                <li><a href="contact.php">اتصل بنا</a></li>
            </ul>
        </nav>
    </header>

    <main class="container">
        <section style="background: white; border-radius: 15px; padding: 2rem; margin-bottom: 2rem;">
            <?php
            // Get category from URL
            $category = isset($_GET['category']) ? $_GET['category'] : '';
            
            if ($category) {
                echo "<h1 style='color: #333; text-align: center; margin-bottom: 2rem;'>📖 ملخصات: {$category}</h1>";
                echo "<div style='text-align: center; margin-bottom: 1rem;'>";
                echo "<a href='summaries.php' class='btn btn-secondary'>عرض جميع الملخصات</a>";
                echo "</div>";
            } else {
                echo "<h1 style='color: #333; text-align: center; margin-bottom: 2rem;'>📖 جميع ملخصات الكتب</h1>";
            }
            ?>

            <div style="margin-bottom: 2rem;">
                <input type="text" id="search" placeholder="ابحث عن كتاب أو مؤلف..." 
                       onkeyup="searchSummaries()" style="width: 100%; padding: 1rem; border: 2px solid #e0e0e0; border-radius: 25px; font-size: 1rem;">
            </div>

            <div class="books-grid">
                <?php
                // Build SQL query based on category
                if ($category) {
                    $sql = "SELECT * FROM books WHERE category = ? ORDER BY title";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $category);
                    $stmt->execute();
                    $result = $stmt->get_result();
                } else {
                    $sql = "SELECT * FROM books ORDER BY title";
                    $result = $conn->query($sql);
                }
                
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        // Check if book is in favorites
                        $favorite_sql = "SELECT * FROM favorites WHERE book_id = ?";
                        $favorite_stmt = $conn->prepare($favorite_sql);
                        $favorite_stmt->bind_param("i", $row['id']);
                        $favorite_stmt->execute();
                        $is_favorite = $favorite_stmt->get_result()->num_rows > 0;
                        $favorite_stmt->close();
                        
                        $favorite_btn = $is_favorite 
                            ? "<button class='btn btn-secondary' style='padding: 0.5rem 1rem; font-size: 0.9rem;' disabled>في المفضلة</button>"
                            : "<a href='add_favorite.php?book_id={$row['id']}' class='btn' style='padding: 0.5rem 1rem; font-size: 0.9rem;'>إضافة للمفضلة</a>";
                        
                                            // حساب التقييم قبل الـ echo
                        $avg_rating = $row['average_rating'] ?? 0;
                        $rating_count = $row['rating_count'] ?? 0;
                        $stars = '';
                        
                        // إنشاء النجوم
                        for ($i = 1; $i <= 5; $i++) {
                            if ($i <= floor($avg_rating)) {
                                $stars .= '⭐';
                            } else {
                                $stars .= '☆';
                            }
                        }
                        
                        echo "
                        <div class='book-card'>
                            <div class='book-cover'>📖</div>
                            <div class='book-info'>
                                <h3>{$row['title']}</h3>
                                <div class='book-author'>بواسطة: {$row['author']}</div>
                                <div class='book-category'>{$row['category']}</div>
                                <div class='reading-time' style='background: #e3f2fd; color: #1976d2; padding: 0.3rem 0.8rem; border-radius: 15px; font-size: 0.8rem; display: inline-block; margin: 0.5rem 0;'>
                                    ⏱ {$row['reading_time']}
                                </div>
                                
                                <!-- عرض التقييم في البطاقة -->
                                <div style='display: flex; align-items: center; gap: 0.3rem; margin: 0.5rem 0;'>
                                    <div style='font-size: 0.8rem; color: #666;'>التقييم:</div>
                                    <div style='display: flex; align-items: center; gap: 0.1rem;'>
                                        {$stars}
                                        <span style='color: #666; font-size: 0.7rem;'>(" . round($avg_rating, 1) . ")</span>
                                    </div>
                                </div>
                                
                                <p style='color: #666; margin: 1rem 0; font-size: 0.9rem; line-height: 1.5;'>" . 
                                substr($row['summary'], 0, 120) . "...</p>
                                <div style='margin-top: 1rem; display: flex; gap: 0.5rem; flex-wrap: wrap;'>
                                    <a href='summary.php?id={$row['id']}' class='btn btn-success' style='padding: 0.5rem 1rem; font-size: 0.9rem;'>قراءة الملخص</a>
                                    {$favorite_btn}
                                </div>
                            </div>
                        </div>";
                    }
                } else {
                    if ($category) {
                        echo "
                        <div style='text-align: center; width: 100%; padding: 3rem; color: #666;'>
                            <div style='font-size: 4rem; margin-bottom: 1rem;'>📚</div>
                            <h3>لا توجد ملخصات في تصنيف: {$category}</h3>
                            <p>يمكنك استعراض جميع الملخصات المتاحة</p>
                            <a href='summaries.php' class='btn' style='margin-top: 1rem;'>عرض جميع الملخصات</a>
                        </div>";
                    } else {
                        echo "
                        <div style='text-align: center; width: 100%; padding: 3rem; color: #666;'>
                            <div style='font-size: 4rem; margin-bottom: 1rem;'>📚</div>
                            <h3>لا توجد ملخصات متاحة بعد</h3>
                            <p>اضغط على الرابط أدناه لإضافة الملخصات</p>
                            <a href='database_setup.php' class='btn' style='margin-top: 1rem;'>إضافة الملخصات</a>
                        </div>";
                    }
                }
                
                // Close statement if category filter was used
                if ($category) {
                    $stmt->close();
                }
                ?>
            </div>
        </section>
    </main>

    <footer style="background: rgba(0,0,0,0.8); color: white; text-align: center; padding: 2rem 0; margin-top: 3rem;">
        <div class="container">
            <p>© 2024 ملخصات - جميع الحقوق محفوظة</p>
        </div>
    </footer>

    <script>
        function searchSummaries() {
            const searchTerm = document.getElementById('search').value.toLowerCase();
            const bookCards = document.querySelectorAll('.book-card');
            
            bookCards.forEach(card => {
                const title = card.querySelector('h3').textContent.toLowerCase();
                const author = card.querySelector('.book-author').textContent.toLowerCase();
                
                if (title.includes(searchTerm) || author.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>
<?php $conn->close(); ?>