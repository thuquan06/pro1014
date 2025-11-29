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
    <!-- Header -->
    <section class="text-center mb-12">
        <h1 class="text-4xl md:text-5xl font-bold mb-6 text-primary">Liên hệ với chúng tôi</h1>
        <p class="text-xl text-text-muted-light dark:text-text-muted-dark max-w-3xl mx-auto">
            Chúng tôi luôn sẵn sàng lắng nghe và hỗ trợ bạn. Hãy để lại thông tin, chúng tôi sẽ phản hồi sớm nhất có thể.
        </p>
    </section>

    <!-- Message Alert -->
    <?php if (!empty($message)): ?>
    <div class="max-w-4xl mx-auto mb-8 p-4 rounded-lg <?= $messageType === 'success' ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200' ?>">
        <div class="flex items-center">
            <i class="fas <?= $messageType === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle' ?> mr-2"></i>
            <span><?= e($message) ?></span>
        </div>
    </div>
    <?php endif; ?>

    <div class="grid lg:grid-cols-3 gap-12">
        <!-- Contact Form -->
        <div class="lg:col-span-2">
            <div class="bg-surface-light dark:bg-surface-dark rounded-lg shadow-md p-8">
                <h2 class="text-2xl font-bold mb-6">Gửi tin nhắn</h2>
                <form method="POST" action="<?= BASE_URL ?>?act=contact" class="space-y-6">
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-semibold mb-2">
                                Họ và tên <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                required
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-surface-light dark:bg-surface-dark text-text-light dark:text-text-dark focus:ring-2 focus:ring-primary focus:border-transparent"
                                placeholder="Nhập họ và tên của bạn"
                            />
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-semibold mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                required
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-surface-light dark:bg-surface-dark text-text-light dark:text-text-dark focus:ring-2 focus:ring-primary focus:border-transparent"
                                placeholder="your.email@example.com"
                            />
                        </div>
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-semibold mb-2">
                            Số điện thoại
                        </label>
                        <input 
                            type="tel" 
                            id="phone" 
                            name="phone"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-surface-light dark:bg-surface-dark text-text-light dark:text-text-dark focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="0123 456 789"
                        />
                    </div>
                    <div>
                        <label for="subject" class="block text-sm font-semibold mb-2">
                            Chủ đề
                        </label>
                        <select 
                            id="subject" 
                            name="subject"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-surface-light dark:bg-surface-dark text-text-light dark:text-text-dark focus:ring-2 focus:ring-primary focus:border-transparent"
                        >
                            <option value="">Chọn chủ đề</option>
                            <option value="tour">Tư vấn tour</option>
                            <option value="booking">Đặt tour</option>
                            <option value="complaint">Khiếu nại</option>
                            <option value="feedback">Góp ý</option>
                            <option value="other">Khác</option>
                        </select>
                    </div>
                    <div>
                        <label for="message" class="block text-sm font-semibold mb-2">
                            Nội dung tin nhắn <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                            id="message" 
                            name="message" 
                            required
                            rows="6"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-surface-light dark:bg-surface-dark text-text-light dark:text-text-dark focus:ring-2 focus:ring-primary focus:border-transparent resize-vertical"
                            placeholder="Nhập nội dung tin nhắn của bạn..."
                        ></textarea>
                    </div>
                    <button 
                        type="submit"
                        class="w-full bg-primary hover:bg-primary/90 text-white font-semibold py-3 px-6 rounded-lg transition-colors"
                    >
                        <i class="fas fa-paper-plane mr-2"></i>
                        Gửi tin nhắn
                    </button>
                </form>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="space-y-6">
            <!-- Office Info -->
            <div class="bg-surface-light dark:bg-surface-dark rounded-lg shadow-md p-8">
                <h2 class="text-2xl font-bold mb-6">Thông tin liên hệ</h2>
                <div class="space-y-6">
                    <div class="flex items-start">
                        <div class="bg-primary/10 dark:bg-primary/20 rounded-full w-12 h-12 flex items-center justify-center mr-4 flex-shrink-0">
                            <i class="fas fa-map-marker-alt text-primary text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold mb-1">Địa chỉ</h3>
                            <p class="text-text-muted-light dark:text-text-muted-dark">
                                123 Đường ABC, Quận X<br>
                                Hà Nội, Việt Nam
                            </p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="bg-primary/10 dark:bg-primary/20 rounded-full w-12 h-12 flex items-center justify-center mr-4 flex-shrink-0">
                            <i class="fas fa-phone text-primary text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold mb-1">Hotline</h3>
                            <p class="text-text-muted-light dark:text-text-muted-dark">
                                <a href="tel:1900xxxx" class="text-primary hover:underline">1900 xxxx</a><br>
                                <a href="tel:+84901234567" class="text-primary hover:underline">+84 90 123 4567</a>
                            </p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="bg-primary/10 dark:bg-primary/20 rounded-full w-12 h-12 flex items-center justify-center mr-4 flex-shrink-0">
                            <i class="fas fa-envelope text-primary text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold mb-1">Email</h3>
                            <p class="text-text-muted-light dark:text-text-muted-dark">
                                <a href="mailto:info@starvel.com" class="text-primary hover:underline">info@starvel.com</a><br>
                                <a href="mailto:support@starvel.com" class="text-primary hover:underline">support@starvel.com</a>
                            </p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="bg-primary/10 dark:bg-primary/20 rounded-full w-12 h-12 flex items-center justify-center mr-4 flex-shrink-0">
                            <i class="fas fa-clock text-primary text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold mb-1">Giờ làm việc</h3>
                            <p class="text-text-muted-light dark:text-text-muted-dark">
                                Thứ 2 - Chủ nhật<br>
                                8:00 - 20:00
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Social Media -->
            <div class="bg-surface-light dark:bg-surface-dark rounded-lg shadow-md p-8">
                <h2 class="text-2xl font-bold mb-6">Kết nối với chúng tôi</h2>
                <div class="flex flex-wrap gap-4">
                    <a href="#" class="bg-blue-600 hover:bg-blue-700 text-white w-12 h-12 rounded-full flex items-center justify-center transition-colors" title="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="bg-pink-600 hover:bg-pink-700 text-white w-12 h-12 rounded-full flex items-center justify-center transition-colors" title="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="bg-red-600 hover:bg-red-700 text-white w-12 h-12 rounded-full flex items-center justify-center transition-colors" title="YouTube">
                        <i class="fab fa-youtube"></i>
                    </a>
                    <a href="#" class="bg-black hover:bg-gray-800 text-white w-12 h-12 rounded-full flex items-center justify-center transition-colors" title="TikTok">
                        <i class="fab fa-tiktok"></i>
                    </a>
                    <a href="https://zalo.me/1900xxxx" target="_blank" class="bg-blue-500 hover:bg-blue-600 text-white w-12 h-12 rounded-full flex items-center justify-center transition-colors" title="Zalo">
                        <i class="fab fa-facebook-messenger"></i>
                    </a>
                </div>
            </div>

            <!-- Map Placeholder -->
            <div class="bg-surface-light dark:bg-surface-dark rounded-lg shadow-md overflow-hidden">
                <div class="aspect-video bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                    <div class="text-center text-text-muted-light dark:text-text-muted-dark">
                        <i class="fas fa-map-marked-alt text-4xl mb-2"></i>
                        <p>Bản đồ</p>
                        <p class="text-sm">(Có thể tích hợp Google Maps)</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ Section -->
    <section class="mt-16">
        <h2 class="text-3xl font-bold text-center mb-12">Câu hỏi thường gặp</h2>
        <div class="max-w-4xl mx-auto space-y-4">
            <div class="bg-surface-light dark:bg-surface-dark rounded-lg shadow-md p-6">
                <h3 class="font-semibold text-lg mb-2">
                    <i class="fas fa-question-circle text-primary mr-2"></i>
                    Làm thế nào để đặt tour?
                </h3>
                <p class="text-text-muted-light dark:text-text-muted-dark">
                    Bạn có thể đặt tour trực tiếp trên website bằng cách chọn tour yêu thích và điền thông tin đặt tour. 
                    Hoặc liên hệ hotline 1900 xxxx để được tư vấn và hỗ trợ đặt tour.
                </p>
            </div>
            <div class="bg-surface-light dark:bg-surface-dark rounded-lg shadow-md p-6">
                <h3 class="font-semibold text-lg mb-2">
                    <i class="fas fa-question-circle text-primary mr-2"></i>
                    Phương thức thanh toán nào được chấp nhận?
                </h3>
                <p class="text-text-muted-light dark:text-text-muted-dark">
                    Chúng tôi chấp nhận thanh toán bằng tiền mặt, chuyển khoản ngân hàng, thẻ tín dụng/ghi nợ, 
                    và các ví điện tử phổ biến như MoMo, ZaloPay.
                </p>
            </div>
            <div class="bg-surface-light dark:bg-surface-dark rounded-lg shadow-md p-6">
                <h3 class="font-semibold text-lg mb-2">
                    <i class="fas fa-question-circle text-primary mr-2"></i>
                    Chính sách hủy tour như thế nào?
                </h3>
                <p class="text-text-muted-light dark:text-text-muted-dark">
                    Chính sách hủy tour tùy thuộc vào từng tour cụ thể. Vui lòng xem chi tiết trong phần 
                    "Chính sách" của tour bạn quan tâm hoặc liên hệ hotline để được tư vấn cụ thể.
                </p>
            </div>
        </div>
    </section>
</div>

