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

function truncateText($text, $length = 150) {
    if (mb_strlen($text) <= $length) return $text;
    return mb_substr($text, 0, $length) . '...';
}
?>

<div class="max-w-[1440px] mx-auto px-10 py-12">
    <!-- Header -->
    <section class="text-center mb-12">
        <h1 class="text-4xl md:text-5xl font-bold mb-6 text-primary">Tin tức & Blog</h1>
        <p class="text-xl text-text-muted-light dark:text-text-muted-dark max-w-3xl mx-auto">
            Cập nhật tin tức du lịch, kinh nghiệm và hướng dẫn từ StarVel Travel
        </p>
    </section>

    <div class="grid lg:grid-cols-4 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-3">
            <?php if (empty($blogs)): ?>
                <div class="bg-surface-light dark:bg-surface-dark rounded-lg shadow-md p-12 text-center">
                    <i class="fas fa-newspaper text-6xl text-text-muted-light dark:text-text-muted-dark mb-4"></i>
                    <h2 class="text-2xl font-bold mb-2">Chưa có bài viết nào</h2>
                    <p class="text-text-muted-light dark:text-text-muted-dark">
                        Các bài viết sẽ được cập nhật sớm nhất có thể.
                    </p>
                </div>
            <?php else: ?>
                <div class="space-y-8">
                    <?php foreach ($blogs as $blog): ?>
                        <?php
                        $blogImage = getImageUrl($blog['hinhanh'] ?? '');
                        $blogUrl = BASE_URL . '?act=blog-detail&id=' . $blog['id_blog'];
                        $blogDate = formatDate($blog['ngaydang'] ?? '');
                        ?>
                        <article class="bg-surface-light dark:bg-surface-dark rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow">
                            <div class="md:flex">
                                <div class="md:w-1/3">
                                    <a href="<?= $blogUrl ?>">
                                        <img 
                                            alt="<?= e($blog['chude'] ?? '') ?>" 
                                            class="w-full h-64 md:h-full object-cover hover:scale-105 transition-transform duration-300" 
                                            src="<?= e($blogImage) ?>"
                                        />
                                    </a>
                                </div>
                                <div class="md:w-2/3 p-6">
                                    <div class="flex items-center text-sm text-text-muted-light dark:text-text-muted-dark mb-3">
                                        <i class="fas fa-calendar-alt mr-2"></i>
                                        <span><?= e($blogDate) ?></span>
                                        <?php if (!empty($blog['nguoiviet'])): ?>
                                            <span class="mx-2">•</span>
                                            <i class="fas fa-user mr-2"></i>
                                            <span><?= e($blog['nguoiviet']) ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <h2 class="text-2xl font-bold mb-3">
                                        <a href="<?= $blogUrl ?>" class="text-text-light dark:text-text-dark hover:text-primary transition-colors">
                                            <?= e($blog['chude'] ?? 'Không có tiêu đề') ?>
                                        </a>
                                    </h2>
                                    <?php if (!empty($blog['tomtat'])): ?>
                                        <p class="text-text-muted-light dark:text-text-muted-dark mb-4 line-clamp-3">
                                            <?= e(truncateText($blog['tomtat'], 200)) ?>
                                        </p>
                                    <?php endif; ?>
                                    <a 
                                        href="<?= $blogUrl ?>" 
                                        class="inline-flex items-center text-primary hover:text-primary/80 font-semibold transition-colors"
                                    >
                                        Đọc thêm
                                        <i class="fas fa-arrow-right ml-2"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <div class="mt-12 flex justify-center items-center space-x-2">
                        <?php if ($currentPage > 1): ?>
                            <a 
                                href="<?= BASE_URL ?>?act=blog&page=<?= $currentPage - 1 ?>" 
                                class="px-4 py-2 bg-surface-light dark:bg-surface-dark border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                            >
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        <?php endif; ?>
                        
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <?php if ($i == 1 || $i == $totalPages || ($i >= $currentPage - 2 && $i <= $currentPage + 2)): ?>
                                <a 
                                    href="<?= BASE_URL ?>?act=blog&page=<?= $i ?>" 
                                    class="px-4 py-2 <?= $i == $currentPage ? 'bg-primary text-white' : 'bg-surface-light dark:bg-surface-dark border border-gray-300 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700' ?> rounded-lg transition-colors"
                                >
                                    <?= $i ?>
                                </a>
                            <?php elseif ($i == $currentPage - 3 || $i == $currentPage + 3): ?>
                                <span class="px-2 text-text-muted-light dark:text-text-muted-dark">...</span>
                            <?php endif; ?>
                        <?php endfor; ?>
                        
                        <?php if ($currentPage < $totalPages): ?>
                            <a 
                                href="<?= BASE_URL ?>?act=blog&page=<?= $currentPage + 1 ?>" 
                                class="px-4 py-2 bg-surface-light dark:bg-surface-dark border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                            >
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Featured Blogs -->
            <?php if (!empty($featuredBlogs)): ?>
                <div class="bg-surface-light dark:bg-surface-dark rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold mb-4">Bài viết nổi bật</h2>
                    <div class="space-y-4">
                        <?php foreach ($featuredBlogs as $featured): ?>
                            <?php
                            $featuredImage = getImageUrl($featured['hinhanh'] ?? '');
                            $featuredUrl = BASE_URL . '?act=blog-detail&id=' . $featured['id_blog'];
                            ?>
                            <a href="<?= $featuredUrl ?>" class="block group">
                                <div class="flex space-x-3">
                                    <img 
                                        alt="<?= e($featured['chude'] ?? '') ?>" 
                                        class="w-20 h-20 object-cover rounded-lg group-hover:scale-105 transition-transform" 
                                        src="<?= e($featuredImage) ?>"
                                    />
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-sm line-clamp-2 group-hover:text-primary transition-colors">
                                            <?= e($featured['chude'] ?? '') ?>
                                        </h3>
                                        <p class="text-xs text-text-muted-light dark:text-text-muted-dark mt-1">
                                            <?= formatDate($featured['ngaydang'] ?? '') ?>
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
        </div>
    </div>
</div>

<style>
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

