<?php
class BaseController {

    protected function loadView($view, $data = [], $layout = 'admin/layout') {
        // đưa data ra scope của view
        if (is_array($data)) extract($data);

        // Project root = thư mục cha của /controllers
        $ROOT = rtrim(dirname(__DIR__), '/\\');

        // build absolute paths
        $viewPath   = $ROOT . '/views/' . $view   . '.php';   // ví dụ: .../views/admin/tours/create.php
        $layoutPath = $ROOT . '/views/' . $layout . '.php';   // ví dụ: .../views/admin/layout.php

        // nạp view => $content
        ob_start();
        if (is_file($viewPath)) {
            include $viewPath;
        } else {
            echo "❌ Không tìm thấy view: " . $viewPath;
        }
        $content = ob_get_clean();

        // nếu view không in gì, báo debug rõ ràng (để bạn nhìn thấy ngay)
        if ($content === '' || $content === null) {
            $content = "<div class='errorWrap'>View trống hoặc không in nội dung: <code>"
                     . htmlspecialchars($viewPath) . "</code></div>";
        }

        // nạp layout
        if (is_file($layoutPath)) {
            include $layoutPath;
        } else {
            echo "❌ Không tìm thấy layout: " . $layoutPath;
        }
    }

    protected function redirect($url) {
        header('Location: ' . $url);
        exit();
    }
}
