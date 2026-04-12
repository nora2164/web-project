<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>من نحن - ملخصات</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <nav class="container">
            <div class="logo">📚 ملخصات</div>
            <ul class="nav-links">
                <li><a href="index.php">الرئيسية</a></li>
                <li><a href="summaries.php">جميع الملخصات</a></li>
                <li><a href="categories.php">التصنيفات</a></li>
                <li><a href="favorites.php">المفضلة</a></li>
                <li><a href="about.php" class="active">من نحن</a></li>
                <li><a href="contact.php">اتصل بنا</a></li>
            </ul>
        </nav>
    </header>

    <main class="container">
        <section style="background: white; border-radius: 15px; padding: 2rem; margin-top: 2rem;">
            <h1 style="color: #333; text-align: center; margin-bottom: 2rem;">من نحن</h1>
            
            <div style="text-align: center; margin-bottom: 3rem;">
                <div style="font-size: 4rem; margin-bottom: 1rem;">📚</div>
                <h2 style="color: #667eea;">رسالتنا</h2>
                <p style="font-size: 1.1rem; line-height: 1.8; color: #666; max-width: 800px; margin: 0 auto;">
                    نحن منصة ملخصات الكتب الأولى في العالم العربي، نهدف إلى نشر المعرفة وتسهيل الوصول إلى أفضل الكتب في أقل وقت ممكن. نؤمن بأن القراءة هي بوابة التطور الشخصي والمهني.
                </p>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
                <div style="background: #f8f9fa; padding: 2rem; border-radius: 10px; text-align: center;">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">🎯</div>
                    <h3 style="color: #333;">رؤيتنا</h3>
                    <p style="color: #666;">أن يكون كل عربي قارئاً ومطلعاً على أفضل الكتب العالمية والعربية</p>
                </div>
                
                <div style="background: #f8f9fa; padding: 2rem; border-radius: 10px; text-align: center;">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">⚡</div>
                    <h3 style="color: #333;">ما نقدمه</h3>
                    <p style="color: #666;">ملخصات مختصرة ودقيقة لأفضل الكتب في مختلف المجالات</p>
                </div>
                
                <div style="background: #f8f9fa; padding: 2rem; border-radius: 10px; text-align: center;">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">❤</div>
                    <h3 style="color: #333;">قيمنا</h3>
                    <p style="color: #666;">الجودة، الدقة، التميز، وخدمة القارئ العربي</p>
                </div>
            </div>

            <div style="background: #e3f2fd; padding: 2rem; border-radius: 10px; margin-top: 3rem;">
                <h2 style="color: #1976d2; text-align: center; margin-bottom: 1rem;">إحصائياتنا</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; text-align: center;">
                    <div>
                        <div style="font-size: 2.5rem; font-weight: bold; color: #667eea;">50+</div>
                        <div style="color: #666;">ملخص كتاب</div>
                    </div>
                    <div>
                        <div style="font-size: 2.5rem; font-weight: bold; color: #667eea;">10,000+</div>
                        <div style="color: #666;">قارئ شهرياً</div>
                    </div>
                    <div>
                        <div style="font-size: 2.5rem; font-weight: bold; color: #667eea;">5</div>
                        <div style="color: #666;">مجالات معرفية</div>
                    </div>
                    <div>
                        <div style="font-size: 2.5rem; font-weight: bold; color: #667eea;">95%</div>
                        <div style="color: #666;">رضا العملاء</div>
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