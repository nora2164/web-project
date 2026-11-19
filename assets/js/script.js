// ===== Keyboard shortcuts =====
document.addEventListener('keydown', function (e) {
    const search = document.getElementById('search') || document.querySelector('input[name="q"]');
    if (!search) return;

    // Ctrl + K → focus the search box
    if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
        e.preventDefault();
        search.focus();
    }
    // Escape → clear the search box
    if (e.key === 'Escape' && document.activeElement === search) {
        search.value = '';
    }
});

document.addEventListener('DOMContentLoaded', function () {
    // ===== Auto-hide flash messages after 5 seconds =====
    document.querySelectorAll('main > .message, main > .error').forEach(function (message) {
        setTimeout(function () {
            message.style.transition = 'opacity 0.5s';
            message.style.opacity = '0';
            setTimeout(function () { message.remove(); }, 500);
        }, 5000);
    });

    // ===== Star rating: highlight on hover, confirm on click =====
    const starButtons = document.querySelectorAll('.star-rating-btn');
    starButtons.forEach(function (btn) {
        const value = Number(btn.dataset.rating);

        btn.addEventListener('mouseenter', function () {
            starButtons.forEach(function (star) {
                star.textContent = Number(star.dataset.rating) <= value ? '⭐' : '☆';
            });
        });
        btn.addEventListener('click', function (e) {
            if (!confirm('هل تريد تقييم هذا الكتاب بـ ' + value + ' نجوم؟')) {
                e.preventDefault();
            }
        });
    });

    const starForm = document.querySelector('.star-form');
    if (starForm) {
        starForm.addEventListener('mouseleave', function () {
            starButtons.forEach(function (star) {
                star.textContent = star.classList.contains('rated') ? '⭐' : '☆';
            });
        });
    }
});

// ===== Favorites: drag & drop ordering (used on favorites.php) =====
function initializeFavoriteOrdering() {
    const container = document.getElementById('favorites-container');
    if (!container || typeof Sortable === 'undefined') return;

    new Sortable(container, {
        animation: 150,
        handle: '.drag-handle',
        ghostClass: 'sortable-ghost',
        chosenClass: 'sortable-chosen',
        dragClass: 'sortable-drag',
        onEnd: updateFavoriteOrder
    });
}

function updateFavoriteOrder() {
    const order = Array.from(document.querySelectorAll('#favorites-container .book-card'))
        .map(function (card, index) {
            return { favorite_id: card.dataset.favoriteId, display_order: index + 1 };
        });

    fetch('update_favorite_order.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ order: order })
    })
        .then(function (response) { return response.json(); })
        .then(function (data) {
            showNotification(data.message, data.success ? 'success' : 'error');
        })
        .catch(function () {
            showNotification('حدث خطأ في الاتصال', 'error');
        });
}

// ===== Small toast notification =====
function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = 'toast toast-' + type;
    notification.textContent = message;
    document.body.appendChild(notification);

    setTimeout(function () {
        notification.style.opacity = '0';
        setTimeout(function () { notification.remove(); }, 300);
    }, 3000);
}
