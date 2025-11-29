<?php
// Helper functions
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/pro1014/');
}

function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
?>

<div class="max-w-[1440px] mx-auto px-10 py-12">
    <!-- Hero Section -->
    <section class="text-center mb-16">
        <h1 class="text-4xl md:text-5xl font-bold mb-6 text-primary">Về StarVel Travel</h1>
        <p class="text-xl text-text-muted-light dark:text-text-muted-dark max-w-3xl mx-auto">
            Chuyên cung cấp các tour du lịch trong nước và quốc tế chất lượng cao với giá cả hợp lý
        </p>
    </section>

    <!-- About Content -->
    <section class="mb-16">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-3xl font-bold mb-6">Câu chuyện của chúng tôi</h2>
                <div class="space-y-4 text-text-muted-light dark:text-text-muted-dark">
                    <p>
                        StarVel Travel được thành lập với sứ mệnh mang đến những trải nghiệm du lịch đáng nhớ cho mọi khách hàng. 
                        Với hơn 10 năm kinh nghiệm trong ngành du lịch, chúng tôi tự hào là một trong những công ty du lịch 
                        hàng đầu tại Việt Nam.
                    </p>
                    <p>
                        Chúng tôi chuyên tổ chức các tour du lịch trong nước và quốc tế, từ những điểm đến quen thuộc đến những 
                        hành trình khám phá độc đáo. Mỗi tour được thiết kế cẩn thận để đảm bảo khách hàng có được trải nghiệm 
                        tốt nhất với giá cả hợp lý nhất.
                    </p>
                    <p>
                        Đội ngũ nhân viên giàu kinh nghiệm và nhiệt tình của chúng tôi luôn sẵn sàng hỗ trợ bạn trong suốt hành trình, 
                        từ khâu tư vấn, đặt tour đến khi kết thúc chuyến đi.
                    </p>
                </div>
            </div>
            <div class="relative rounded-lg overflow-hidden shadow-xl">
                <img 
                    alt="Đội ngũ StarVel Travel" 
                    class="w-full h-96 object-cover" 
                    src="https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=800&q=80"
                />
            </div>
        </div>
    </section>

    <!-- Statistics -->
    <section class="mb-16">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-surface-light dark:bg-surface-dark rounded-lg p-8 text-center shadow-md">
                <div class="text-5xl font-bold text-primary mb-4"><?= number_format($totalTours) ?>+</div>
                <h3 class="text-xl font-semibold mb-2">Tour du lịch</h3>
                <p class="text-text-muted-light dark:text-text-muted-dark">Đa dạng các tour trong nước và quốc tế</p>
            </div>
            <div class="bg-surface-light dark:bg-surface-dark rounded-lg p-8 text-center shadow-md">
                <div class="text-5xl font-bold text-primary mb-4"><?= number_format($totalCustomers) ?>+</div>
                <h3 class="text-xl font-semibold mb-2">Khách hàng</h3>
                <p class="text-text-muted-light dark:text-text-muted-dark">Khách hàng đã tin tưởng và sử dụng dịch vụ</p>
            </div>
            <div class="bg-surface-light dark:bg-surface-dark rounded-lg p-8 text-center shadow-md">
                <div class="text-5xl font-bold text-primary mb-4"><?= number_format($totalDestinations) ?>+</div>
                <h3 class="text-xl font-semibold mb-2">Điểm đến</h3>
                <p class="text-text-muted-light dark:text-text-muted-dark">Điểm đến hấp dẫn trên khắp thế giới</p>
            </div>
        </div>
    </section>

    <!-- Values -->
    <section class="mb-16">
        <h2 class="text-3xl font-bold text-center mb-12">Giá trị cốt lõi</h2>
        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-surface-light dark:bg-surface-dark rounded-lg p-6 shadow-md">
                <div class="text-4xl mb-4 text-primary">
                    <i class="fas fa-heart"></i>
                </div>
                <h3 class="text-xl font-semibold mb-3">Tận tâm</h3>
                <p class="text-text-muted-light dark:text-text-muted-dark">
                    Chúng tôi luôn đặt khách hàng làm trung tâm, tận tâm phục vụ và đảm bảo sự hài lòng của mọi khách hàng.
                </p>
            </div>
            <div class="bg-surface-light dark:bg-surface-dark rounded-lg p-6 shadow-md">
                <div class="text-4xl mb-4 text-primary">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3 class="text-xl font-semibold mb-3">Chất lượng</h3>
                <p class="text-text-muted-light dark:text-text-muted-dark">
                    Cam kết mang đến những tour du lịch chất lượng cao với dịch vụ tốt nhất và giá cả hợp lý nhất.
                </p>
            </div>
            <div class="bg-surface-light dark:bg-surface-dark rounded-lg p-6 shadow-md">
                <div class="text-4xl mb-4 text-primary">
                    <i class="fas fa-star"></i>
                </div>
                <h3 class="text-xl font-semibold mb-3">Uy tín</h3>
                <p class="text-text-muted-light dark:text-text-muted-dark">
                    Xây dựng niềm tin qua nhiều năm hoạt động với đội ngũ chuyên nghiệp và dịch vụ đáng tin cậy.
                </p>
            </div>
        </div>
    </section>

    <!-- Mission & Vision -->
    <section class="mb-16">
        <div class="grid md:grid-cols-2 gap-12">
            <div class="bg-surface-light dark:bg-surface-dark rounded-lg p-8 shadow-md">
                <h2 class="text-2xl font-bold mb-4 text-primary">
                    <i class="fas fa-bullseye mr-2"></i>Sứ mệnh
                </h2>
                <p class="text-text-muted-light dark:text-text-muted-dark leading-relaxed">
                    Mang đến những trải nghiệm du lịch đáng nhớ và ý nghĩa cho mọi khách hàng, góp phần quảng bá vẻ đẹp 
                    của Việt Nam và các điểm đến trên thế giới. Chúng tôi cam kết cung cấp dịch vụ du lịch chất lượng cao 
                    với giá cả hợp lý, đảm bảo an toàn và sự hài lòng của khách hàng.
                </p>
            </div>
            <div class="bg-surface-light dark:bg-surface-dark rounded-lg p-8 shadow-md">
                <h2 class="text-2xl font-bold mb-4 text-primary">
                    <i class="fas fa-eye mr-2"></i>Tầm nhìn
                </h2>
                <p class="text-text-muted-light dark:text-text-muted-dark leading-relaxed">
                    Trở thành công ty du lịch hàng đầu tại Việt Nam, được khách hàng tin tưởng và yêu mến. Chúng tôi mong muốn 
                    mở rộng mạng lưới dịch vụ, phát triển các tour du lịch độc đáo và đa dạng, đồng thời áp dụng công nghệ hiện đại 
                    để nâng cao chất lượng phục vụ khách hàng.
                </p>
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section class="mb-16">
        <h2 class="text-3xl font-bold text-center mb-12">Tại sao chọn StarVel Travel?</h2>
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="text-center">
                <div class="bg-primary/10 dark:bg-primary/20 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-map-marked-alt text-3xl text-primary"></i>
                </div>
                <h3 class="font-semibold mb-2">Đa dạng điểm đến</h3>
                <p class="text-sm text-text-muted-light dark:text-text-muted-dark">
                    Hàng trăm tour trong nước và quốc tế
                </p>
            </div>
            <div class="text-center">
                <div class="bg-primary/10 dark:bg-primary/20 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-dollar-sign text-3xl text-primary"></i>
                </div>
                <h3 class="font-semibold mb-2">Giá cả hợp lý</h3>
                <p class="text-sm text-text-muted-light dark:text-text-muted-dark">
                    Giá tốt nhất thị trường, nhiều ưu đãi
                </p>
            </div>
            <div class="text-center">
                <div class="bg-primary/10 dark:bg-primary/20 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-headset text-3xl text-primary"></i>
                </div>
                <h3 class="font-semibold mb-2">Hỗ trợ 24/7</h3>
                <p class="text-sm text-text-muted-light dark:text-text-muted-dark">
                    Đội ngũ tư vấn nhiệt tình, chuyên nghiệp
                </p>
            </div>
            <div class="text-center">
                <div class="bg-primary/10 dark:bg-primary/20 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-certificate text-3xl text-primary"></i>
                </div>
                <h3 class="font-semibold mb-2">Đảm bảo chất lượng</h3>
                <p class="text-sm text-text-muted-light dark:text-text-muted-dark">
                    Dịch vụ được kiểm định và đánh giá cao
                </p>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-primary text-white rounded-lg p-12 text-center">
        <h2 class="text-3xl font-bold mb-4">Sẵn sàng khám phá thế giới?</h2>
        <p class="text-xl mb-8 opacity-90">
            Hãy để StarVel Travel đồng hành cùng bạn trong những chuyến đi đáng nhớ
        </p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="<?= BASE_URL ?>?act=tours" class="bg-white text-primary font-semibold px-8 py-3 rounded-lg hover:bg-gray-100 transition-colors">
                Xem các tour
            </a>
            <a href="<?= BASE_URL ?>?act=contact" class="bg-transparent border-2 border-white text-white font-semibold px-8 py-3 rounded-lg hover:bg-white/10 transition-colors">
                Liên hệ với chúng tôi
            </a>
        </div>
    </section>
</div>

