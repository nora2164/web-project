<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "booksummaries";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("فشل الاتصال: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS $dbname CHARACTER SET utf8 COLLATE utf8_general_ci";
if ($conn->query($sql) === TRUE) {
    echo "✅ تم إنشاء قاعدة البيانات: $dbname<br>";
} else {
    echo "❌ خطأ في إنشاء قاعدة البيانات: " . $conn->error . "<br>";
}

// Select database
$conn->select_db($dbname);

// Create books table
$sql = "CREATE TABLE IF NOT EXISTS books (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    category VARCHAR(100),
    summary TEXT,
    cover_image VARCHAR(255),
    reading_time VARCHAR(50),
    key_points TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "✅ تم إنشاء جدول الكتب<br>";
} else {
    echo "❌ خطأ في إنشاء جدول الكتب: " . $conn->error . "<br>";
}

// Create favorites table
$sql = "CREATE TABLE IF NOT EXISTS favorites (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    book_id INT(6) UNSIGNED,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    display_order INT(6) DEFAULT 0,
    FOREIGN KEY (book_id) REFERENCES books(id)
)";

if ($conn->query($sql) === TRUE) {
    echo "✅ تم إنشاء جدول المفضلة<br>";
} else {
    echo "❌ خطأ في إنشاء جدول المفضلة: " . $conn->error . "<br>";
}// Create ratings table
$sql = "CREATE TABLE IF NOT EXISTS ratings (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    book_id INT(6) UNSIGNED,
    rating TINYINT(1) NOT NULL,
    user_ip VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (book_id) REFERENCES books(id),
    UNIQUE KEY unique_rating (book_id, user_ip)
)";

if ($conn->query($sql) === TRUE) {
    echo "✅ تم إنشاء جدول التقييمات<br>";
} else {
    echo "❌ خطأ في إنشاء جدول التقييمات: " . $conn->error . "<br>";
}

// Add rating columns to books table
$sql = "ALTER TABLE books 
        ADD COLUMN average_rating DECIMAL(3,2) DEFAULT 0.00,
        ADD COLUMN rating_count INT(6) DEFAULT 0";

if ($conn->query($sql) === TRUE) {
    echo "✅ تم إضافة أعمدة التقييم لجدول الكتب<br>";
} else {
    echo "📖 أعمدة التقييم موجودة مسبقاً<br>";
}
// Add sample book summaries
$sample_books = [
    [
        'title' => 'العادات الذرية',
        'author' => 'جيمس كلير',
        'category' => 'تطوير الذات',
        'reading_time' => '15 دقيقة',
        'summary' => "ملخص كتاب العادات الذرية يركز على فكرة أن التغييرات الصغيرة والمستمرة تؤدي إلى نتائج كبيرة. الكتاب يقدم نظاماً عملياً لبناء عادات جيدة والتخلص من العادات السيئة من خلال أربع خطوات: جعل العادة واضحة، جذابة، سهلة، ومرضية.",
        'key_points' => "• التحسن بنسبة 1% يومياً يؤدي إلى نتائج مذهلة على المدى الطويل\n• ركز على النظام وليس الأهداف\n• استخدم قاعدة الـ 5 دقائق للبدء\n• غير بيئتك لتغيير عاداتك"
    ],
    [
        'title' => 'الأب الغني والأب الفقير',
        'author' => 'روبرت كيوساكي',
        'category' => 'مال وأعمال',
        'reading_time' => '12 دقيقة',
        'summary' => "كتاب الأب الغني والأب الفقير يروي قصة مؤلفه الذي نشأ بوجود أبين: أحدهما غني والآخر فقير. الكتاب يعلم القراء الفرق بين التفكير المالي للفقراء والأثرياء، وأهمية التعليم المالي في تحقيق الحرية المالية.",
        'key_points' => "• الأصول تجلب المال، الخصوم تأخذ المال\n• اعمل من أجل التعلم وليس من أجل المال فقط\n• استثمر في تعليمك المالي\n• ابدأ مشروعك الخاص"
    ],
    [
        'title' => 'قوة التفكير الإيجابي',
        'author' => 'نورمان فينسنت بيل',
        'category' => 'تطوير الذات',
        'reading_time' => '10 دقيقة',
        'summary' => "كتاب قوة التفكير الإيجابي يوضح كيف يمكن لأفكارنا أن تشكل واقعنا. الكتاب يقدم تقنيات عملية لتحويل التفكير السلبي إلى إيجابي، وبناء الثقة بالنفس، وتحقيق النجاح في مختلف مجالات الحياة.",
        'key_points' => "• أفكارك تخلق واقعك\n• ركز على الحلول وليس المشاكل\n• احط نفسك بأشخاص إيجابيين\n• الثقة بالنفس مفتاح النجاح"
    ],
    [
        'title' => 'الرجل الذي حسب كل شيء',
        'author' => 'ويليام بوند',
        'category' => 'سيرة ذاتية',
        'reading_time' => '14 دقيقة',
        'summary' => "كتاب الرجل الذي حسب كل شيء يحكي قصة عالم الرياضيات العبقري الذي غير مفهومنا للعالم من خلال الأرقام. الكتاب يسلط الضوء على كيفية استخدام المنطق الرياضي في حل مشاكل الحياة اليومية واتخاذ القرارات المصيرية.",
        'key_points' => "• الرياضيات ليست مجرد أرقام بل طريقة تفكير\n• استخدم البيانات في اتخاذ قراراتك\n• التعلم المستمر مفتاح التطور\n• ابق فضولياً تجاه العالم من حولك"
    ],
    [
        'title' => 'فن اللامبالاة',
        'author' => 'مارك مانسون',
        'category' => 'تطوير الذات',
        'reading_time' => '13 دقيقة',
        'summary' => "كتاب فن اللامبالاة يقدم نظرة واقعية للحياة بعيداً عن التفكير الإيجابي السطحي. المؤلف يشرح كيف أن اختيار ما نهتم به بحكمة هو مفتاح العيش حياة سعيدة وهادفة.",
        'key_points' => "• ليس كل شيء يستحق الاهتمام\n• المشاكل جزء طبيعي من الحياة\n• الفشل ليس نهاية العالم\n• ركز على ما يمكنك التحكم فيه"
    ],
    [
        'title' => 'أربعون',
        'author' => 'أحمد الشقيري',
        'category' => 'ديني',
        'reading_time' => '11 دقيقة',
        'summary' => "كتاب أربعون يقدم خلاصة تجربة المؤلف خلال أربعين يوماً من العبادة والتأمل. الكتاب يجمع بين الحكمة العملية والبعد الروحي في رحلة نحو تحسين الذات وتقوية العلاقة مع الله.",
        'key_points' => "• التغيير يبدأ بقرار صادق\n• الاستمرارية أهم من الكمية\n• المراجعة اليومية أساس التطور\n• التوازن بين الروح والمادة"
    ]
];


foreach ($sample_books as $book) {
    // Check if book already exists
    $check_sql = "SELECT id FROM books WHERE title = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $book['title']);
    $check_stmt->execute();
    $check_stmt->store_result();
    
    if ($check_stmt->num_rows == 0) {
        // Insert new book
        $insert_sql = "INSERT INTO books (title, author, category, reading_time, summary, key_points) 
                       VALUES (?, ?, ?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("ssssss", $book['title'], $book['author'], $book['category'], $book['reading_time'], $book['summary'], $book['key_points']);
        
        if ($insert_stmt->execute()) {
            echo "✅ تم إضافة ملخص: {$book['title']}<br>";
        } else {
            echo "❌ خطأ في إضافة: {$book['title']}<br>";
        }
        $insert_stmt->close();
    } else {
        echo "📖 الملخص موجود مسبقاً: {$book['title']}<br>";
    }
    $check_stmt->close();
}

echo "<h3 style='color: green;'>🎉 اكتمل إنشاء قاعدة البيانات والملخصات بنجاح!</h3>";
echo "<a href='index.php' style='display: inline-block; padding: 10px 20px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; margin-top: 20px;'>اذهب إلى الصفحة الرئيسية</a>";

$conn->close();
?>