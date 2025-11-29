<?php
// Helper functions
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/pro1014/');
}

function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

function getImageUrl($imagePath, $fallback = null) {
    if (empty($imagePath)) {
        return $fallback ?? 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&q=80';
    }
    if (preg_match('/^https?:\/\//', $imagePath)) {
        return $imagePath;
    }
    if (strpos($imagePath, BASE_URL) === false) {
        return BASE_URL . ltrim($imagePath, '/');
    }
    return $imagePath;
}

function formatDate($date) {
    if (empty($date)) return '';
    $timestamp = strtotime($date);
    return date('d/m/Y', $timestamp);
}
?>

<div class="max-w-[1440px] mx-auto px-10 py-12">
    <div class="grid lg:grid-cols-4 gap-8">
        <!-- Main Content -->
        <article class="lg:col-span-3">
            <!-- Header -->
            <header class="mb-8">
                <h1 class="text-4xl md:text-5xl font-bold mb-4 text-text-light dark:text-text-dark">
                    <?= e($blog['chude'] ?? 'Không có tiêu đề') ?>
                </h1>
                <div class="flex flex-wrap items-center gap-4 text-text-muted-light dark:text-text-muted-dark">
                    <div class="flex items-center">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        <span><?= formatDate($blog['ngaydang'] ?? '') ?></span>
                    </div>
                    <?php if (!empty($blog['nguoiviet'])): ?>
                        <div class="flex items-center">
                            <i class="fas fa-user mr-2"></i>
                            <span><?= e($blog['nguoiviet']) ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="flex items-center">
                        <i class="fas fa-clock mr-2"></i>
                        <span>5 phút đọc</span>
                    </div>
                </div>
            </header>

            <!-- Featured Image -->
            <?php if (!empty($blog['hinhanh'])): ?>
                <div class="mb-8 rounded-lg overflow-hidden">
                    <img 
                        alt="<?= e($blog['chude'] ?? '') ?>" 
                        class="w-full h-96 object-cover" 
                        src="<?= e(getImageUrl($blog['hinhanh'])) ?>"
                    />
                </div>
            <?php endif; ?>

            <!-- Summary -->
            <?php if (!empty($blog['tomtat'])): ?>
                <div class="bg-primary/10 dark:bg-primary/20 border-l-4 border-primary p-6 mb-8 rounded-r-lg">
                    <p class="text-lg font-semibold text-text-light dark:text-text-dark italic">
                        <?= e($blog['tomtat']) ?>
                    </p>
                </div>
            <?php endif; ?>

            <!-- Content -->
            <div class="prose dark:prose-invert max-w-none mb-8">
                <?php if (!empty($blog['noidung'])): ?>
                    <div class="blog-content">
                        <?= $blog['noidung'] ?>
                    </div>
                <?php else: ?>
                    <p class="text-text-muted-light dark:text-text-muted-dark">
                        Nội dung đang được cập nhật...
                    </p>
                <?php endif; ?>
            </div>

            <!-- Share Buttons -->
            <div class="border-t border-gray-300 dark:border-gray-600 pt-8 mb-8">
                <h3 class="text-lg font-semibold mb-4">Chia sẻ bài viết</h3>
                <div class="flex flex-wrap gap-3">
                    <a 
                        href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(BASE_URL . '?act=blog-detail&id=' . $blog['id_blog']) ?>" 
                        target="_blank"
                        class="flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors"
                    >
                        <i class="fab fa-facebook-f mr-2"></i>
                        Facebook
                    </a>
                    <a 
                        href="https://twitter.com/intent/tweet?url=<?= urlencode(BASE_URL . '?act=blog-detail&id=' . $blog['id_blog']) ?>&text=<?= urlencode($blog['chude'] ?? '') ?>" 
                        target="_blank"
                        class="flex items-center px-4 py-2 bg-blue-400 hover:bg-blue-500 text-white rounded-lg transition-colors"
                    >
                        <i class="fab fa-twitter mr-2"></i>
                        Twitter
                    </a>
                    <a 
                        href="https://www.linkedin.com/shareArticle?url=<?= urlencode(BASE_URL . '?act=blog-detail&id=' . $blog['id_blog']) ?>" 
                        target="_blank"
                        class="flex items-center px-4 py-2 bg-blue-800 hover:bg-blue-900 text-white rounded-lg transition-colors"
                    >
                        <i class="fab fa-linkedin-in mr-2"></i>
                        LinkedIn
                    </a>
                    <button 
                        onclick="copyToClipboard('<?= BASE_URL ?>?act=blog-detail&id=<?= $blog['id_blog'] ?>')"
                        class="flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors"
                    >
                        <i class="fas fa-link mr-2"></i>
                        Copy Link
                    </button>
                </div>
            </div>
        </article>

        <!-- Sidebar -->
        <aside class="lg:col-span-1 space-y-6">
            <!-- Related Blogs -->
            <?php if (!empty($relatedBlogs)): ?>
                <div class="bg-surface-light dark:bg-surface-dark rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold mb-4">Bài viết liên quan</h2>
                    <div class="space-y-4">
                        <?php foreach ($relatedBlogs as $related): ?>
                            <?php
                            $relatedImage = getImageUrl($related['hinhanh'] ?? '');
                            $relatedUrl = BASE_URL . '?act=blog-detail&id=' . $related['id_blog'];
                            ?>
                            <a href="<?= $relatedUrl ?>" class="block group">
                                <div class="flex space-x-3">
                                    <img 
                                        alt="<?= e($related['chude'] ?? '') ?>" 
                                        class="w-20 h-20 object-cover rounded-lg group-hover:scale-105 transition-transform" 
                                        src="<?= e($relatedImage) ?>"
                                    />
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-sm line-clamp-2 group-hover:text-primary transition-colors">
                                            <?= e($related['chude'] ?? '') ?>
                                        </h3>
                                        <p class="text-xs text-text-muted-light dark:text-text-muted-dark mt-1">
                                            <?= formatDate($related['ngaydang'] ?? '') ?>
                                        </p>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- CTA -->
            <div class="bg-primary text-white rounded-lg p-6 text-center">
                <h3 class="text-xl font-bold mb-2">Đặt tour ngay!</h3>
                <p class="text-sm mb-4 opacity-90">
                    Khám phá các tour du lịch hấp dẫn
                </p>
                <a 
                    href="<?= BASE_URL ?>?act=tours" 
                    class="inline-block bg-white text-primary font-semibold px-6 py-2 rounded-lg hover:bg-gray-100 transition-colors"
                >
                    Xem tour
                </a>
            </div>

            <!-- Back to Blog -->
            <a 
                href="<?= BASE_URL ?>?act=blog" 
                class="block bg-surface-light dark:bg-surface-dark rounded-lg shadow-md p-6 text-center hover:shadow-lg transition-shadow"
            >
                <i class="fas fa-arrow-left mr-2"></i>
                Quay lại danh sách tin tức
            </a>
        </aside>
    </div>
</div>

<style>
.blog-content {
    color: var(--text-light);
    line-height: 1.8;
}

.dark .blog-content {
    color: var(--text-dark);
}

.blog-content p {
    margin-bottom: 1.5rem;
}

.blog-content h2,
.blog-content h3,
.blog-content h4 {
    font-weight: bold;
    margin-top: 2rem;
    margin-bottom: 1rem;
}

.blog-content h2 {
    font-size: 1.875rem;
}

.blog-content h3 {
    font-size: 1.5rem;
}

.blog-content img {
    max-width: 100%;
    height: auto;
    border-radius: 0.5rem;
    margin: 1.5rem 0;
}

.blog-content ul,
.blog-content ol {
    margin-left: 1.5rem;
    margin-bottom: 1.5rem;
}

.blog-content li {
    margin-bottom: 0.5rem;
}

.blog-content a {
    color: var(--primary);
    text-decoration: underline;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('Đã copy link vào clipboard!');
    }, function(err) {
        console.error('Could not copy text: ', err);
    });
}
</script>

