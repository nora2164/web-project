-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 13 نوفمبر 2025 الساعة 10:27
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
(3, 'قوة التفكير الإيجابي', 'نورمان فينسنت بيل', 'تطوير الذات', 'كتاب قوة التفكير الإيجابي يوضح كيف يمكن لأفكارنا أن تشكل واقعنا. الكتاب يقدم تقنيات عملية لتحويل التفكير السلبي إلى إيجابي، وبناء الثقة بالنفس، وتحقيق النجاح في مختلف مجالات الحياة.', NULL, '10 دقيقة', '• أفكارك تخلق واقعك\n• ركز على الحلول وليس المشاكل\n• احط نفسك بأشخاص إيجابيين\n• الثقة بالنفس مفتاح النجاح', '2025-11-12 10:09:34');

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
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

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
