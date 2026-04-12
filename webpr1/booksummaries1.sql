-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 18 نوفمبر 2025 الساعة 19:44
-- إصدار الخادم: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `booksummaries`
--

-- --------------------------------------------------------

--
-- بنية الجدول `books`
--

CREATE TABLE `books` (
  `id` int(6) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `summary` text DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `reading_time` varchar(50) DEFAULT NULL,
  `key_points` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `category`, `summary`, `cover_image`, `reading_time`, `key_points`, `created_at`) VALUES
(1, 'العادات الذرية', 'جيمس كلير', 'تطوير الذات', 'ملخص كتاب العادات الذرية يركز على فكرة أن التغييرات الصغيرة والمستمرة تؤدي إلى نتائج كبيرة. الكتاب يقدم نظاماً عملياً لبناء عادات جيدة والتخلص من العادات السيئة من خلال أربع خطوات: جعل العادة واضحة، جذابة، سهلة، ومرضية.', NULL, '15 دقيقة', '• التحسن بنسبة 1% يومياً يؤدي إلى نتائج مذهلة على المدى الطويل\n• ركز على النظام وليس الأهداف\n• استخدم قاعدة الـ 5 دقائق للبدء\n• غير بيئتك لتغيير عاداتك', '2025-11-12 10:09:34'),
(2, 'الأب الغني والأب الفقير', 'روبرت كيوساكي', 'مال وأعمال', 'كتاب الأب الغني والأب الفقير يروي قصة مؤلفه الذي نشأ بوجود أبين: أحدهما غني والآخر فقير. الكتاب يعلم القراء الفرق بين التفكير المالي للفقراء والأثرياء، وأهمية التعليم المالي في تحقيق الحرية المالية.', NULL, '12 دقيقة', '• الأصول تجلب المال، الخصوم تأخذ المال\n• اعمل من أجل التعلم وليس من أجل المال فقط\n• استثمر في تعليمك المالي\n• ابدأ مشروعك الخاص', '2025-11-12 10:09:34'),
(3, 'قوة التفكير الإيجابي', 'نورمان فينسنت بيل', 'تطوير الذات', 'كتاب قوة التفكير الإيجابي يوضح كيف يمكن لأفكارنا أن تشكل واقعنا. الكتاب يقدم تقنيات عملية لتحويل التفكير السلبي إلى إيجابي، وبناء الثقة بالنفس، وتحقيق النجاح في مختلف مجالات الحياة.', NULL, '10 دقيقة', '• أفكارك تخلق واقعك\n• ركز على الحلول وليس المشاكل\n• احط نفسك بأشخاص إيجابيين\n• الثقة بالنفس مفتاح النجاح', '2025-11-12 10:09:34'),
(4, 'الرجل الذي حسب كل شيء', 'ويليام بوند', 'سيرة ذاتية', 'كتاب الرجل الذي حسب كل شيء يحكي قصة عالم الرياضيات العبقري الذي غير مفهومنا للعالم من خلال الأرقام. الكتاب يسلط الضوء على كيفية استخدام المنطق الرياضي في حل مشاكل الحياة اليومية واتخاذ القرارات المصيرية.', NULL, '14 دقيقة', '• الرياضيات ليست مجرد أرقام بل طريقة تفكير\n• استخدم البيانات في اتخاذ قراراتك\n• التعلم المستمر مفتاح التطور\n• ابق فضولياً تجاه العالم من حولك', '2025-11-17 20:49:03'),
(5, 'فن اللامبالاة', 'مارك مانسون', 'تطوير الذات', 'كتاب فن اللامبالاة يقدم نظرة واقعية للحياة بعيداً عن التفكير الإيجابي السطحي. المؤلف يشرح كيف أن اختيار ما نهتم به بحكمة هو مفتاح العيش حياة سعيدة وهادفة.', NULL, '13 دقيقة', '• ليس كل شيء يستحق الاهتمام\n• المشاكل جزء طبيعي من الحياة\n• الفشل ليس نهاية العالم\n• ركز على ما يمكنك التحكم فيه', '2025-11-17 20:49:03'),
(6, 'أربعون', 'أحمد الشقيري', 'ديني', 'كتاب أربعون يقدم خلاصة تجربة المؤلف خلال أربعين يوماً من العبادة والتأمل. الكتاب يجمع بين الحكمة العملية والبعد الروحي في رحلة نحو تحسين الذات وتقوية العلاقة مع الله.', NULL, '11 دقيقة', '• التغيير يبدأ بقرار صادق\n• الاستمرارية أهم من الكمية\n• المراجعة اليومية أساس التطور\n• التوازن بين الروح والمادة', '2025-11-17 20:49:03');

-- --------------------------------------------------------

--
-- بنية الجدول `favorites`
--

CREATE TABLE `favorites` (
  `id` int(6) UNSIGNED NOT NULL,
  `book_id` int(6) UNSIGNED DEFAULT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `display_order` int(6) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `favorites`
--

INSERT INTO `favorites` (`id`, `book_id`, `added_at`, `display_order`) VALUES
(19, 1, '2025-11-17 20:08:21', 2),
(20, 2, '2025-11-17 20:08:23', 1),
(21, 6, '2025-11-17 20:51:06', 6),
(23, 5, '2025-11-17 20:51:44', 4),
(24, 3, '2025-11-17 20:51:46', 5);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD KEY `book_id` (`book_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- قيود الجداول المُلقاة.
--

--
-- قيود الجداول `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
