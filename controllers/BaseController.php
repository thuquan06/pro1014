<?php
class BaseController {
    
    /**
     * Tải view và truyền dữ liệu
     *
     * @param string $view Tên file view (ví dụ: 'admin/dashboard')
     * @param array $data Dữ liệu cần truyền cho view
     * @param string $layout Layout để bao bọc view (ví dụ: 'admin')
     */
    protected function loadView($view, $data = [], $layout = null) {
        // Chuyển array $data thành các biến riêng lẻ
        extract($data);

        // Tạo đường dẫn đến file view
        $viewPath = './views/' . $view . '.php';

        if ($layout) {
            // Nếu có layout, tải layout
            $layoutPath = './views/layouts/' . $layout . '.php';
            
            // Bắt đầu bộ đệm đầu ra để lưu nội dung view
            ob_start();
            if (file_exists($viewPath)) {
                include $viewPath;
            } else {
                echo "Lỗi: Không tìm thấy view '$viewPath'";
            }
            // Lấy nội dung view và gán vào biến $content cho layout
            $content = ob_get_clean();
            
            // Tải file layout (layout sẽ include $content)
            if (file_exists($layoutPath)) {
                include $layoutPath;
            } else {
                echo "Lỗi: Không tìm thấy layout '$layoutPath'";
            }
        } else {
            // Nếu không có layout, chỉ cần tải view
            if (file_exists($viewPath)) {
                include $viewPath;
            } else {
                echo "Lỗi: Không tìm thấy view '$viewPath'";
            }
        }
    }

    /**
     * Chuyển hướng bằng URL
     */
    protected function redirect($url) {
        header('Location: ' . $url);
        exit();
    }
}