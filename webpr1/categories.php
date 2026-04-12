<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>التصنيفات - ملخصات</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <nav class="container">
            <div class="logo">📚 ملخصات</div>
            <ul class="nav-links">
                <li><a href="index.php">الرئيسية</a></li>
                <li><a href="summaries.php">جميع الملخصات</a></li>
                <li><a href="categories.php" class="active">التصنيفات</a></li>
                <li><a href="favorites.php">المفضلة</a></li>
                <li><a href="about.php">من نحن</a></li>
                <li><a href="contact.php">اتصل بنا</a></li>
            </ul>
        </nav>
    </header>

    <main class="container">
        <section style="background: white; border-radius: 15px; padding: 2rem; margin-top: 2rem;">
            <h1 style="color: #333; text-align: center; margin-bottom: 2rem;">📂 التصنيفات</h1>
            
            <div class="books-grid">
                <!-- تطوير الذات -->
                <div class="book-card" onclick="location.href='summaries.php?category=تطوير الذات'" style="cursor: pointer;">
                    <div class="book-cover" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">🚀</div>
                    <div class="book-info">
                        <h3>تطوير الذات</h3>
                        <p style="color: #666; margin: 1rem 0; font-size: 0.9rem; line-height: 1.5;">
                            اكتشف طرقاً جديدة لتحسين نفسك وتطوير مهاراتك الشخصية والمهنية
                        </p>
                        <div style="background: #e3f2fd; color: #1976d2; padding: 0.5rem 1rem; border-radius: 15px; font-size: 0.9rem; display: inline-block;">
                            📚 3 ملخصات
                        </div>
                    </div>
                </div>

                <!-- مال وأعمال -->
                <div class="book-card" onclick="location.href='summaries.php?category=مال وأعمال'" style="cursor: pointer;">
                    <div class="book-cover" style="background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);">💼</div>
                    <div class="book-info">
                        <h3>مال وأعمال</h3>
                        <p style="color: #666; margin: 1rem 0; font-size: 0.9rem; line-height: 1.5;">
                            تعلم أسرار النجاح المالي وإدارة الأعمال من خبراء العالم
                        </p>
                        <div style="background: #e3f2fd; color: #1976d2; padding: 0.5rem 1rem; border-radius: 15px; font-size: 0.9rem; display: inline-block;">
                            📚 1 ملخص
                        </div>
                    </div>
                </div>

                <!-- سيرة ذاتية -->
                <div class="book-card" onclick="location.href='summaries.php?category=سيرة ذاتية'" style="cursor: pointer;">
                    <div class="book-cover" style="background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);">👤</div>
                    <div class="book-info">
                        <h3>سيرة ذاتية</h3>
                        <p style="color: #666; margin: 1rem 0; font-size: 0.9rem; line-height: 1.5;">
                            استلهم من حياة العظماء وقصص نجاحهم الملهمة
                        </p>
                        <div style="background: #e3f2fd; color: #1976d2; padding: 0.5rem 1rem; border-radius: 15px; font-size: 0.9rem; display: inline-block;">
                            📚 1 ملخص
                        </div>
                    </div>
                </div>

                <!-- ديني -->
                <div class="book-card" onclick="location.href='summaries.php?category=ديني'" style="cursor: pointer;">
                    <div class="book-cover" style="background: linear-gradient(135deg, #9c27b0 0%, #7b1fa2 100%);">🕌</div>
                    <div class="book-info">
                        <h3>ديني</h3>
                        <p style="color: #666; margin: 1rem 0; font-size: 0.9rem; line-height: 1.5;">
                            تعمق في فهم الدين وتزود بمعارف روحية قيمة
                        </p>
                        <div style="background: #e3f2fd; color: #1976d2; padding: 0.5rem 1rem; border-radius: 15px; font-size: 0.9rem; display: inline-block;">
                            📚 1 ملخص
                        </div>
                    </div>
                </div>

                <!-- علم النفس -->
                <div class="book-card" onclick="location.href='summaries.php?category=علم النفس'" style="cursor: pointer;">
                    <div class="book-cover" style="background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);">🧠</div>
                    <div class="book-info">
                        <h3>علم النفس</h3>
                        <p style="color: #666; margin: 1rem 0; font-size: 0.9rem; line-height: 1.5;">
                            افهم العقل البشري وسلوكيات الإنسان بشكل أعمق
                        </p>
                        <div style="background: #e3f2fd; color: #1976d2; padding: 0.5rem 1rem; border-radius: 15px; font-size: 0.9rem; display: inline-block;">
                            📚 قريباً
                        </div>
                    </div>
                </div>

                <!-- تكنولوجيا -->
                <div class="book-card" onclick="location.href='summaries.php?category=تكنولوجيا'" style="cursor: pointer;">
                    <div class="book-cover" style="background: linear-gradient(135deg, #607d8b 0%, #455a64 100%);">💻</div>
                    <div class="book-info">
                        <h3>تكنولوجيا</h3>
                        <p style="color: #666; margin: 1rem 0; font-size: 0.9rem; line-height: 1.5;">
                            ابق على اطلاع بأحدث trends التكنولوجيا والابتكار
                        </p>
                        <div style="background: #e3f2fd; color: #1976d2; padding: 0.5rem 1rem; border-radius: 15px; font-size: 0.9rem; display: inline-block;">
                            📚 قريباً
                        </div>
                    </div>
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