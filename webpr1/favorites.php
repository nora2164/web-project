<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>المفضلة - ملخصات</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
</head>
<body>
    <header>
        <nav class="container">
            <div class="logo">📚 ملخصات</div>
            <ul class="nav-links">
                <li><a href="index.php">الرئيسية</a></li>
                <li><a href="summaries.php">جميع الملخصات</a></li>
                <li><a href="categories.php">التصنيفات</a></li>
                <li><a href="favorites.php" class="active">المفضلة</a></li>
                <li><a href="about.php">من نحن</a></li>
                <li><a href="contact.php">اتصل بنا</a></li>
            </ul>
        </nav>
    </header>

    <main class="container">
        <section style="background: white; border-radius: 15px; padding: 2rem;">
            <h1 style="color: #333; text-align: center; margin-bottom: 2rem;">❤ ملخصاتي المفضلة</h1>
            
            <?php
            // Handle messages
            if (isset($_GET['message'])) {
                echo "<div style='background: #d4edda; color: #155724; padding: 1rem; border-radius: 5px; margin-bottom: 1rem;'>{$_GET['message']}</div>";
            }
            if (isset($_GET['error'])) {
                echo "<div style='background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 5px; margin-bottom: 1rem;'>{$_GET['error']}</div>";
            }
            ?>

            <div style="background: #e3f2fd; padding: 1rem; border-radius: 10px; margin-bottom: 2rem;">
                <p style="margin: 0; color: #1976d2; text-align: center;">
                    💡 يمكنك سحب وإفلات الملخصات لإعادة ترتيبها حسب تفضيلك
                </p>
            </div>

            <div class="books-grid" id="favorites-container">
                <?php
                $sql = "SELECT b.*, f.id as favorite_id, f.display_order 
                        FROM books b 
                        INNER JOIN favorites f ON b.id = f.book_id 
                        ORDER BY f.display_order ASC, f.added_at DESC";
                $result = $conn->query($sql);
                
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "
                        <div class='book-card' data-favorite-id='{$row['favorite_id']}' data-book-id='{$row['id']}'>
                            <div class='book-cover'>❤</div>
                            <div class='book-info'>
                                <div style='display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;'>
                                    <h3 style='margin: 0;'>{$row['title']}</h3>
                                    <div style='background: #667eea; color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; cursor: move;' title='اسحب لإعادة الترتيب'>
                                        ↕
                                    </div>
                                </div>
                                <div class='book-author'>بواسطة: {$row['author']}</div>
                                <div class='book-category'>{$row['category']}</div>
                                <div class='reading-time' style='background: #e3f2fd; color: #1976d2; padding: 0.3rem 0.8rem; border-radius: 15px; font-size: 0.8rem; display: inline-block; margin: 0.5rem 0;'>
                                    ⏱ {$row['reading_time']}
                                </div>
                                <p style='color: #666; margin: 1rem 0; font-size: 0.9rem; line-height: 1.5;'>" . 
                                substr($row['summary'], 0, 120) . "...</p>
                                <div style='margin-top: 1rem; display: flex; gap: 0.5rem; flex-wrap: wrap;'>
                                    <a href='summary.php?id={$row['id']}' class='btn btn-success' style='padding: 0.5rem 1rem; font-size: 0.9rem;'>قراءة الملخص</a>
                                    <a href='remove_favorite.php?book_id={$row['id']}' class='btn btn-danger' style='padding: 0.5rem 1rem; font-size: 0.9rem;' 
                                       onclick='return confirm(\"هل تريد إزالة هذا الملخص من المفضلة؟\")'>إزالة</a>
                                </div>
                            </div>
                        </div>";
                    }
                } else {
                    echo "
                    <div style='text-align: center; width: 100%; padding: 3rem; color: #666;'>
                        <div style='font-size: 4rem; margin-bottom: 1rem;'>❤</div>
                        <h3>لا توجد ملخصات في المفضلة</h3>
                        <p>اذهب إلى صفحة الملخصات وأضف بعض الملخصات إلى المفضلة!</p>
                        <a href='summaries.php' class='btn' style='margin-top: 1rem;'>استعرض الملخصات</a>
                    </div>";
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
        // Initialize Sortable for drag and drop
        const favoritesContainer = document.getElementById('favorites-container');
        
        if (favoritesContainer) {
            const sortable = new Sortable(favoritesContainer, {
                animation: 150,
                handle: '.book-info > div:first-child div:last-child',
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                dragClass: 'sortable-drag',
                onEnd: function(evt) {
                    updateFavoriteOrder();
                }
            });
        }

        // Update order in database
        function updateFavoriteOrder() {
            const bookCards = document.querySelectorAll('.book-card');
            const orderData = [];
            
            bookCards.forEach((card, index) => {
                const favoriteId = card.getAttribute('data-favorite-id');
                orderData.push({
                    favorite_id: favoriteId,
                    display_order: index + 1
                });
            });
            
            // Send AJAX request to update order
            fetch('update_favorite_order.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ order: orderData })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('تم تحديث ترتيب الملخصات بنجاح', 'success');
                } else {
                    showNotification('حدث خطأ في تحديث الترتيب', 'error');
                }
            })
            .catch(error => {
                console.error('خطأ في الاتصال:', error);
                showNotification('حدث خطأ في الاتصال', 'error');
            });
        }

        // Show notification
        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 1rem 1.5rem;
                border-radius: 5px;
                color: white;
                z-index: 10000;
                font-weight: bold;
                transition: all 0.3s;
            `;
            
            if (type === 'success') {
                notification.style.background = '#28a745';
            } else {
                notification.style.background = '#dc3545';
            }
            
            notification.textContent = message;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.opacity = '0';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }
    </script>

    <style>
        .sortable-ghost {
            opacity: 0.4;
            background: #c8ebfb !important;
            border: 2px dashed #667eea !important;
        }
        
        .sortable-chosen {
            transform: rotate(2deg);
            box-shadow: 0 10px 30px rgba(0,0,0,0.2) !important;
        }
        
        .sortable-drag {
            opacity: 1 !important;
            transform: rotate(5deg);
            z-index: 1000;
        }
    </style>
</body>
</html>
<?php $conn->close(); ?>