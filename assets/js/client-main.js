/**
 * StarVel Travel - Client JavaScript
 * File JS chung cho tất cả trang client
 */

// ===== MOBILE MENU TOGGLE =====
function toggleMobileMenu() {
    const navMenu = document.getElementById('navMenu');
    if (navMenu) {
        navMenu.classList.toggle('active');

        // Toggle icon
        const toggle = document.querySelector('.mobile-menu-toggle i');
        if (toggle) {
            toggle.classList.toggle('fa-bars');
            toggle.classList.toggle('fa-times');
        }
    }
}

// Close mobile menu when clicking outside
document.addEventListener('click', function(event) {
    const navMenu = document.getElementById('navMenu');
    const toggle = document.querySelector('.mobile-menu-toggle');

    if (navMenu && toggle) {
        if (!navMenu.contains(event.target) && !toggle.contains(event.target)) {
            navMenu.classList.remove('active');
            const icon = toggle.querySelector('i');
            if (icon) {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        }
    }
});

// ===== HEADER SCROLL EFFECT =====
window.addEventListener('scroll', function() {
    const header = document.querySelector('.header');
    if (header) {
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    }
});

// ===== SEARCH FORM =====
function handleSearch(event) {
    event.preventDefault();
    const searchInput = document.querySelector('.header-search input');
    if (searchInput) {
        const query = searchInput.value.trim();
        if (query) {
            window.location.href = `${BASE_URL}?act=search&q=${encodeURIComponent(query)}`;
        }
    }
}

// ===== SMOOTH SCROLL FOR ANCHOR LINKS =====
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        const href = this.getAttribute('href');
        if (href !== '#' && href !== '#!') {
            e.preventDefault();
            const target = document.querySelector(href);
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        }
    });
});

// ===== IMAGE LAZY LOADING =====
if ('loading' in HTMLImageElement.prototype) {
    const images = document.querySelectorAll('img[loading="lazy"]');
    images.forEach(img => {
        img.src = img.dataset.src;
    });
} else {
    // Fallback for browsers that don't support lazy loading
    const script = document.createElement('script');
    script.src = 'https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js';
    document.body.appendChild(script);
}

// ===== FORMAT PRICE =====
function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(price);
}

// ===== FORMAT DATE =====
function formatDate(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('vi-VN', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
}

// ===== SHOW TOAST MESSAGE =====
function showToast(message, type = 'info') {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `alert alert-${type}`;
    toast.style.cssText = `
        position: fixed;
        top: 80px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        animation: slideInRight 0.3s ease-out;
    `;
    toast.textContent = message;

    document.body.appendChild(toast);

    // Auto remove after 3 seconds
    setTimeout(() => {
        toast.style.animation = 'slideOutRight 0.3s ease-out';
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 300);
    }, 3000);
}

// ===== CONFIRM ACTION =====
function confirmAction(message) {
    return confirm(message || 'Bạn có chắc chắn muốn thực hiện hành động này?');
}

// ===== PHONE NUMBER VALIDATION =====
function validatePhone(phone) {
    const phoneRegex = /^(0|\+84)(3|5|7|8|9)[0-9]{8}$/;
    return phoneRegex.test(phone);
}

// ===== EMAIL VALIDATION =====
function validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// ===== FORM VALIDATION =====
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;

    let isValid = true;
    const inputs = form.querySelectorAll('input[required], textarea[required], select[required]');

    inputs.forEach(input => {
        const errorElement = input.parentElement.querySelector('.form-error');

        // Remove previous error
        if (errorElement) {
            errorElement.remove();
        }

        // Validate
        if (!input.value.trim()) {
            isValid = false;
            showFieldError(input, 'Trường này là bắt buộc');
        } else if (input.type === 'email' && !validateEmail(input.value)) {
            isValid = false;
            showFieldError(input, 'Email không hợp lệ');
        } else if (input.type === 'tel' && !validatePhone(input.value)) {
            isValid = false;
            showFieldError(input, 'Số điện thoại không hợp lệ');
        }
    });

    return isValid;
}

function showFieldError(input, message) {
    const error = document.createElement('div');
    error.className = 'form-error';
    error.textContent = message;
    input.parentElement.appendChild(error);
    input.classList.add('is-invalid');
}

// ===== BACK TO TOP BUTTON =====
const backToTopButton = document.createElement('button');
backToTopButton.innerHTML = '<i class="fas fa-arrow-up"></i>';
backToTopButton.className = 'back-to-top';
backToTopButton.style.cssText = `
    position: fixed;
    bottom: 100px;
    right: 30px;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: var(--primary);
    color: white;
    border: none;
    cursor: pointer;
    display: none;
    align-items: center;
    justify-content: center;
    box-shadow: var(--shadow-lg);
    z-index: 998;
    transition: var(--transition);
`;

document.body.appendChild(backToTopButton);

window.addEventListener('scroll', function() {
    if (window.scrollY > 300) {
        backToTopButton.style.display = 'flex';
    } else {
        backToTopButton.style.display = 'none';
    }
});

backToTopButton.addEventListener('click', function() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
});

// ===== SET ACTIVE MENU =====
function setActiveMenu() {
    const currentUrl = window.location.href;
    const menuLinks = document.querySelectorAll('.nav-menu a');

    menuLinks.forEach(link => {
        if (link.href === currentUrl) {
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    });
}

// Run on page load
document.addEventListener('DOMContentLoaded', function() {
    setActiveMenu();
});

// ===== COPY TO CLIPBOARD =====
function copyToClipboard(text) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(() => {
            showToast('Đã sao chép!', 'success');
        });
    } else {
        // Fallback for older browsers
        const textarea = document.createElement('textarea');
        textarea.value = text;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
        showToast('Đã sao chép!', 'success');
    }
}

// ===== DEBOUNCE FUNCTION =====
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// ===== LOADING OVERLAY =====
function showLoading() {
    const overlay = document.createElement('div');
    overlay.id = 'loadingOverlay';
    overlay.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    `;
    overlay.innerHTML = '<div class="spinner"></div>';
    document.body.appendChild(overlay);
}

function hideLoading() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) {
        overlay.remove();
    }
}

// ===== EXPORT FUNCTIONS =====
window.StarVel = {
    toggleMobileMenu,
    handleSearch,
    formatPrice,
    formatDate,
    showToast,
    confirmAction,
    validatePhone,
    validateEmail,
    validateForm,
    copyToClipboard,
    showLoading,
    hideLoading,
    debounce
};

console.log('🚀 StarVel Travel Client JS Loaded');
