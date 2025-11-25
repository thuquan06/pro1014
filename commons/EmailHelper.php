<?php
/**
 * EmailHelper - Gửi email qua SMTP sử dụng PHPMailer
 * 
 * Hướng dẫn cài đặt PHPMailer:
 * 1. Download PHPMailer từ: https://github.com/PHPMailer/PHPMailer/releases
 * 2. Giải nén vào thư mục: vendor/PHPMailer/
 * 3. Hoặc sử dụng Composer: composer require phpmailer/phpmailer
 */

class EmailHelper {
    private $mailer;
    
    public function __construct() {
        // Kiểm tra xem PHPMailer đã được include chưa
        if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
            // Thử load PHPMailer từ vendor (nhiều cách)
            $possiblePaths = [
                PATH_ROOT . 'vendor/PHPMailer/src/',           // Cách 1: vendor/PHPMailer/src/
                PATH_ROOT . 'vendor/PHPMailer-7.0.1/src/',     // Cách 2: PHPMailer-7.0.1 (phiên bản cụ thể)
                PATH_ROOT . 'vendor/PHPMailer-6.9.1/src/',     // Cách 3: PHPMailer-6.9.1
                PATH_ROOT . 'vendor/PHPMailer-6.9.0/src/',     // Cách 4: PHPMailer-6.9.0
                PATH_ROOT . 'vendor/phpmailer/phpmailer/src/', // Cách 5: Composer
                __DIR__ . '/../vendor/PHPMailer/src/',         // Cách 6: Relative path
            ];
            
            // Tự động tìm tất cả thư mục PHPMailer-* trong vendor
            $vendorPath = PATH_ROOT . 'vendor';
            if (is_dir($vendorPath)) {
                $vendorDirs = scandir($vendorPath);
                foreach ($vendorDirs as $dir) {
                    if ($dir !== '.' && $dir !== '..' && is_dir($vendorPath . '/' . $dir)) {
                        // Tìm thư mục bắt đầu bằng PHPMailer
                        if (stripos($dir, 'phpmailer') === 0) {
                            $autoPath = $vendorPath . '/' . $dir . '/src/';
                            if (!in_array($autoPath, $possiblePaths) && file_exists($autoPath . 'PHPMailer.php')) {
                                $possiblePaths[] = $autoPath;
                            }
                        }
                    }
                }
            }
            
            $loaded = false;
            foreach ($possiblePaths as $phpmailerPath) {
                if (file_exists($phpmailerPath . 'PHPMailer.php')) {
                    require_once $phpmailerPath . 'PHPMailer.php';
                    require_once $phpmailerPath . 'SMTP.php';
                    require_once $phpmailerPath . 'Exception.php';
                    $loaded = true;
                    error_log("PHPMailer loaded from: {$phpmailerPath}");
                    break;
                }
            }
            
            if (!$loaded) {
                // Nếu không có PHPMailer, sử dụng mail() function của PHP
                error_log("PHPMailer not found. Using mail() function fallback.");
                $this->mailer = null;
                return;
            }
        }
        
        // Khởi tạo PHPMailer nếu có
        if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
            $this->mailer = new PHPMailer\PHPMailer\PHPMailer(true);
            $this->configureSMTP();
        }
    }
    
    /**
     * Cấu hình SMTP
     */
    private function configureSMTP() {
        if (!$this->mailer) {
            return;
        }
        
        try {
            // Server settings
            $this->mailer->isSMTP();
            $this->mailer->Host       = SMTP_HOST;
            $this->mailer->SMTPAuth   = SMTP_AUTH;
            $this->mailer->Username   = SMTP_USERNAME;
            $this->mailer->Password   = SMTP_PASSWORD;
            $this->mailer->SMTPSecure = SMTP_SECURE;
            $this->mailer->Port       = SMTP_PORT;
            $this->mailer->CharSet    = 'UTF-8';
            
            // Enable verbose debug output (chỉ trong dev)
            $this->mailer->SMTPDebug = 2; // Bật debug để xem lỗi chi tiết
            $this->mailer->Debugoutput = function($str, $level) {
                error_log("PHPMailer Debug (Level $level): $str");
            };
            
        } catch (Exception $e) {
            error_log("EmailHelper SMTP config error: " . $e->getMessage());
        }
    }
    
    /**
     * Gửi email
     * 
     * @param string $to Email người nhận
     * @param string $subject Tiêu đề
     * @param string $body Nội dung HTML
     * @param string $fromEmail Email người gửi (optional)
     * @param string $fromName Tên người gửi (optional)
     * @return bool
     */
    public function send($to, $subject, $body, $fromEmail = null, $fromName = null) {
        try {
            // Nếu không có PHPMailer, fallback về mail() function
            if (!$this->mailer) {
                return $this->sendViaMailFunction($to, $subject, $body, $fromEmail, $fromName);
            }
            
            // Sử dụng PHPMailer
            $this->mailer->setFrom(
                $fromEmail ?? SMTP_FROM_EMAIL,
                $fromName ?? SMTP_FROM_NAME
            );
            
            $this->mailer->addAddress($to);
            $this->mailer->isHTML(true);
            $this->mailer->Subject = $subject;
            $this->mailer->Body    = $body;
            $this->mailer->AltBody = strip_tags($body);
            
            $result = $this->mailer->send();
            
            if ($result) {
                error_log("Email sent successfully to: {$to}");
            } else {
                error_log("Failed to send email to: {$to}. Error: " . $this->mailer->ErrorInfo);
            }
            
            return $result;
            
        } catch (Exception $e) {
            error_log("EmailHelper send error: " . $e->getMessage());
            // Fallback về mail() function nếu PHPMailer lỗi
            return $this->sendViaMailFunction($to, $subject, $body, $fromEmail, $fromName);
        }
    }
    
    /**
     * Fallback: Gửi email bằng mail() function của PHP
     */
    private function sendViaMailFunction($to, $subject, $body, $fromEmail = null, $fromName = null) {
        $fromEmail = $fromEmail ?? SMTP_FROM_EMAIL;
        $fromName = $fromName ?? SMTP_FROM_NAME;
        
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: {$fromName} <{$fromEmail}>" . "\r\n";
        $headers .= "Reply-To: {$fromEmail}" . "\r\n";
        
        $result = @mail($to, $subject, $body, $headers);
        
        if ($result) {
            error_log("Email sent via mail() function to: {$to}");
        } else {
            error_log("Failed to send email via mail() function to: {$to}");
        }
        
        return $result;
    }
}

