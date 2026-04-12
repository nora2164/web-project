// Search functionality
function searchSummaries() {
    const searchTerm = document.getElementById('search').value.toLowerCase();
    const bookCards = document.querySelectorAll('.book-card');
    
    bookCards.forEach(card => {
        const title = card.querySelector('h3').textContent.toLowerCase();
        const author = card.querySelector('.book-author').textContent.toLowerCase();
        const category = card.querySelector('.book-category').textContent.toLowerCase();
        
        if (title.includes(searchTerm) || author.includes(searchTerm) || category.includes(searchTerm)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

// Smooth scroll to top
function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

// Add smooth scrolling to all links
document.addEventListener('DOMContentLoaded', function() {
    const links = document.querySelectorAll('a[href^="#"]');
    
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // Auto-hide messages after 5 seconds
    const messages = document.querySelectorAll('.message, .error');
    messages.forEach(message => {
        setTimeout(() => {
            message.style.opacity = '0';
            message.style.transition = 'opacity 0.5s';
            setTimeout(() => message.remove(), 500);
        }, 5000);
    });
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl + K to focus search
    if (e.ctrlKey && e.key === 'k') {
        e.preventDefault();
        const search = document.getElementById('search');
        if (search) search.focus();
    }
    
    // Escape to clear search
    if (e.key === 'Escape') {
        const search = document.getElementById('search');
        if (search) {
            search.value = '';
            searchSummaries();
        }
    }
});

// Favorite ordering functionality
function initializeFavoriteOrdering() {
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
}

// Update favorite order in database
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