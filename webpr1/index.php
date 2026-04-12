<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ملخصات - منصة ملخصات الكتب</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <nav class="container">
            <div class="logo">📚 ملخصات</div>
            <ul class="nav-links">
                <li><a href="index.php" class="active">الرئيسية</a></li>
                <li><a href="summaries.php">جميع الملخصات</a></li>
                <li><a href="categories.php">التصنيفات</a></li>
                <li><a href="favorites.php">المفضلة</a></li>
                <li><a href="about.php">من نحن</a></li>
                <li><a href="contact.php">اتصل بنا</a></li>
            </ul>
        </nav>
    </header>

    <main class="container">
        <section class="hero">
            <h1>اكتشف عالم المعرفة من خلال الملخصات</h1>
            <p>اقرأ ملخصات لأفضل الكتب في أقل وقت. وفر الوقت واكتسب المعرفة!</p>
            <div style="margin-top: 2rem;">
                <a href="summaries.php" class="btn">استعرض الملخصات</a>
                <a href="favorites.php" class="btn btn-success">ملخصاتي المفضلة</a>
            </div>
        </section>

        <section style="background: white; border-radius: 15px; padding: 2rem; margin-top: 2rem;">
            <h2 style="text-align: center; color: #333; margin-bottom: 2rem;">أحدث الملخصات</h2>
            <div class="books-grid">
                <?php
                $sql = "SELECT * FROM books ORDER BY created_at DESC LIMIT 6";
                $result = $conn->query($sql);
                
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "
                        <div class='book-card'>
                            <div class='book-cover'>📖</div>
                            <div class='book-info'>
                                <h3>{$row['title']}</h3>
                                <div class='book-author'>بواسطة: {$row['author']}</div>
                                <div class='book-category'>{$row['category']}</div>
                                <div class='reading-time'>{$row['reading_time']}</div>
                                <p style='color: #666; margin: 1rem 0; font-size: 0.9rem;'>" . 
                                substr($row['summary'], 0, 100) . "...</p>
                                <div style='margin-top: 1rem;'>
                                    <a href='summary.php?id={$row['id']}' class='btn'>قراءة الملخص</a>
                                </div>
                            </div>
                        </div>";
                    }
                } else {
                    echo "<p style='text-align: center; width: 100%; color: #666;'>لا توجد ملخصات متاحة حالياً. <a href='database_setup.php'>اضغط هنا لإضافة الملخصات</a></p>";
                }
                ?>
            </div>
        </section>

        <section style="background: rgba(255,255,255,0.9); border-radius: 15px; padding: 2rem; margin-top: 2rem;">
            <h2 style="text-align: center; color: #333; margin-bottom: 1rem;">لماذا ملخصات الكتب؟</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin-top: 2rem;">
                <div style="text-align: center; padding: 1.5rem;">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">⏱</div>
                    <h3 style="color: #333;">توفير الوقت</h3>
                    <p style="color: #666;">اقرأ الكتاب في دقائق بدلاً من ساعات</p>
                </div>
                <div style="text-align: center; padding: 1.5rem;">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">🎯</div>
                    <h3 style="color: #333;">تركيز على الأفكار</h3>
                    <p style="color: #666;">احصل على الأفكار الرئيسية فقط</p>
                </div>
                <div style="text-align: center; padding: 1.5rem;">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">❤</div>
                    <h3 style="color: #333;">المفضلة</h3>
                    <p style="color: #666;">احفظ الملخصات المفضلة لديك</p>
                </div>
            </div>
        </section>
    </main>

    <footer style="background: rgba(0,0,0,0.8); color: white; text-align: center; padding: 2rem 0; margin-top: 3rem;">
        <div class="container">
            <p>© 2024 ملخصات - جميع الحقوق محفوظة</p>
        </div>
    </footer>
</body>
</html>
<?php $conn->close(); ?>