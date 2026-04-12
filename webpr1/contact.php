<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اتصل بنا - ملخصات</title>
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
                <li><a href="about.php">من نحن</a></li>
                <li><a href="contact.php" class="active">اتصل بنا</a></li>
            </ul>
        </nav>
    </header>

    <main class="container">
        <section style="background: white; border-radius: 15px; padding: 2rem; margin-top: 2rem;">
            <h1 style="color: #333; text-align: center; margin-bottom: 2rem;">اتصل بنا</h1>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 3rem;">
                <!-- Contact Form -->
                <div>
                    <h2 style="color: #667eea; margin-bottom: 1.5rem;">أرسل رسالة</h2>
                    <form style="display: flex; flex-direction: column; gap: 1rem;">
                        <div>
                            <label style="display: block; margin-bottom: 0.5rem; color: #333; font-weight: 500;">الاسم الكامل</label>
                            <input type="text" style="width: 100%; padding: 1rem; border: 2px solid #e0e0e0; border-radius: 10px; font-size: 1rem;" placeholder="أدخل اسمك الكامل">
                        </div>
                        
                        <div>
                            <label style="display: block; margin-bottom: 0.5rem; color: #333; font-weight: 500;">البريد الإلكتروني</label>
                            <input type="email" style="width: 100%; padding: 1rem; border: 2px solid #e0e0e0; border-radius: 10px; font-size: 1rem;" placeholder="example@email.com">
                        </div>
                        
                        <div>
                            <label style="display: block; margin-bottom: 0.5rem; color: #333; font-weight: 500;">الموضوع</label>
                            <select style="width: 100%; padding: 1rem; border: 2px solid #e0e0e0; border-radius: 10px; font-size: 1rem;">
                                <option value="">اختر الموضوع</option>
                                <option value="suggestion">مقترح</option>
                                <option value="complaint">شكوى</option>
                                <option value="inquiry">استفسار</option>
                                <option value="cooperation">تعاون</option>
                            </select>
                        </div>
                        
                        <div>
                            <label style="display: block; margin-bottom: 0.5rem; color: #333; font-weight: 500;">الرسالة</label>
                            <textarea rows="5" style="width: 100%; padding: 1rem; border: 2px solid #e0e0e0; border-radius: 10px; font-size: 1rem; resize: vertical;" placeholder="اكتب رسالتك هنا..."></textarea>
                        </div>
                        
                        <button type="submit" class="btn" style="align-self: flex-start;">إرسال الرسالة</button>
                    </form>
                </div>

                <!-- Contact Info -->
                <div>
                    <h2 style="color: #667eea; margin-bottom: 1.5rem;">معلومات الاتصال</h2>
                    
                    <div style="background: #f8f9fa; padding: 2rem; border-radius: 10px;">
                        <div style="display: flex; align-items: center; margin-bottom: 1.5rem;">
                            <div style="background: #667eea; color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-left: 1rem;">
                                📧
                            </div>
                            <div>
                                <h3 style="color: #333; margin: 0;">البريد الإلكتروني</h3>
                                <p style="color: #666; margin: 0;">info@booksummaries.com</p>
                            </div>
                        </div>
                        
                        <div style="display: flex; align-items: center; margin-bottom: 1.5rem;">
                            <div style="background: #667eea; color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-left: 1rem;">
                                📱
                            </div>
                            <div>
                                <h3 style="color: #333; margin: 0;">الهاتف</h3>
                                <p style="color: #666; margin: 0;">+966 50 123 4567</p>
                            </div>
                        </div>
                        
                        <div style="display: flex; align-items: center; margin-bottom: 1.5rem;">
                            <div style="background: #667eea; color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-left: 1rem;">
                                🕒
                            </div>
                            <div>
                                <h3 style="color: #333; margin: 0;">ساعات العمل</h3>
                                <p style="color: #666; margin: 0;">الأحد - الخميس: 8 صباحاً - 5 مساءً</p>
                            </div>
                        </div>
                        
                        <div style="display: flex; align-items: center;">
                            <div style="background: #667eea; color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-left: 1rem;">
                                📍
                            </div>
                            <div>
                                <h3 style="color: #333; margin: 0;">العنوان</h3>
                                <p style="color: #666; margin: 0;">الرياض، المملكة العربية السعودية</p>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div style="margin-top: 2rem;">
                        <h3 style="color: #333; margin-bottom: 1rem;">تابعنا على</h3>
                        <div style="display: flex; gap: 1rem;">
                            <a href="#" style="background: #667eea; color: white; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; font-size: 1.2rem;">📘</a>
                            <a href="#" style="background: #667eea; color: white; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; font-size: 1.2rem;">🐦</a>
                            <a href="#" style="background: #667eea; color: white; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; font-size: 1.2rem;">📷</a>
                            <a href="#" style="background: #667eea; color: white; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; font-size: 1.2rem;">🎬</a>
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