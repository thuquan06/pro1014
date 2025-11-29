<?php
/**
 * PaymentController - Xử lý thanh toán MoMo
 */
class PaymentController extends BaseController
{
    private $hoadonModel;
    private $momoHelper;
    
    public function __construct()
    {
        // Đảm bảo env.php được load trước
        if (!defined('BASE_URL')) {
            require_once './commons/env.php';
        }
        
        require_once './models/HoadonModel.php';
        require_once './commons/MoMoPaymentHelper.php';
        
        $this->hoadonModel = new HoadonModel();
        $this->momoHelper = new MoMoPaymentHelper();
    }
    
    /**
     * Tạo yêu cầu thanh toán MoMo
     */
    public function createPayment()
    {
        // Bắt đầu output buffering để tránh output trước khi set header
        ob_start();
        
        // Set header JSON ngay từ đầu
        header('Content-Type: application/json; charset=utf-8');
        
        // Tắt hiển thị lỗi để tránh output HTML
        $oldErrorReporting = error_reporting(E_ALL);
        $oldDisplayErrors = ini_set('display_errors', 0);
        
        try {
            $hoadonId = $_POST['hoadon_id'] ?? $_GET['hoadon_id'] ?? null;
            
            if (!$hoadonId) {
                echo json_encode([
                    'success' => false,
                    'message' => 'ID hóa đơn không hợp lệ!'
                ], JSON_UNESCAPED_UNICODE);
                exit;
            }
            
            // Lấy thông tin hóa đơn
            $hoadon = $this->hoadonModel->getHoadonById($hoadonId);
            
            if (!$hoadon) {
                ob_clean();
                echo json_encode([
                    'success' => false,
                    'message' => 'Không tìm thấy hóa đơn!'
                ], JSON_UNESCAPED_UNICODE);
                ob_end_flush();
                exit;
            }
            
            // Kiểm tra trạng thái thanh toán
            if (!empty($hoadon['trang_thai_thanh_toan']) && $hoadon['trang_thai_thanh_toan'] == 1) {
                ob_clean();
                echo json_encode([
                    'success' => false,
                    'message' => 'Hóa đơn này đã được thanh toán!'
                ], JSON_UNESCAPED_UNICODE);
                ob_end_flush();
                exit;
            }
            
            // Tính tổng tiền
            $total = $this->hoadonModel->calculateTotal($hoadonId);
            
            if ($total <= 0) {
                ob_clean();
                echo json_encode([
                    'success' => false,
                    'message' => 'Số tiền thanh toán không hợp lệ!'
                ], JSON_UNESCAPED_UNICODE);
                ob_end_flush();
                exit;
            }
            
            // Tạo orderId (sử dụng id_hoadon)
            $orderId = 'HD' . str_pad($hoadonId, 8, '0', STR_PAD_LEFT);
            
            // Tạo mô tả đơn hàng
            require_once './models/TourModel.php';
            $tourModel = new TourModel();
            $tour = $tourModel->getTourByID($hoadon['id_goi']);
            $orderDescription = 'Thanh toán đặt tour: ' . ($tour['tengoi'] ?? 'Tour #' . $hoadonId);
            
            // Tạo yêu cầu thanh toán MoMo
            $paymentData = [
                'orderId' => $orderId,
                'amount' => $total,
                'description' => $orderDescription,
                'extraData' => json_encode(['hoadon_id' => $hoadonId])
            ];
            
            $result = $this->momoHelper->createPaymentRequest($paymentData);
            
            // Kiểm tra lỗi từ MoMo helper
            if ($result === false) {
                ob_clean();
                $errorMsg = 'Không thể tạo yêu cầu thanh toán. ';
                // Kiểm tra cấu hình MoMo
                $partnerCode = $this->momoHelper->partnerCode ?? '';
                $accessKey = $this->momoHelper->accessKey ?? '';
                $secretKey = $this->momoHelper->secretKey ?? '';
                
                if (empty($partnerCode) || empty($accessKey) || empty($secretKey)) {
                    $errorMsg .= 'Vui lòng cấu hình thông tin MoMo (Partner Code, Access Key, Secret Key) trong file commons/env.php!';
                    error_log("MoMo Payment: Missing configuration - Partner Code: " . (!empty($partnerCode) ? 'OK' : 'MISSING') . 
                             ", Access Key: " . (!empty($accessKey) ? 'OK' : 'MISSING') . 
                             ", Secret Key: " . (!empty($secretKey) ? 'OK' : 'MISSING'));
                } else {
                    $errorMsg .= 'Vui lòng thử lại sau hoặc kiểm tra log để biết chi tiết!';
                }
                echo json_encode([
                    'success' => false,
                    'message' => $errorMsg
                ], JSON_UNESCAPED_UNICODE);
                ob_end_flush();
                exit;
            }
            
            if ($result && isset($result['payUrl'])) {
                // Cập nhật thông tin thanh toán vào database
                $updateData = [
                    'phuong_thuc_thanh_toan' => 'momo',
                    'trang_thai_thanh_toan' => 2, // Đang xử lý
                    'so_tien_thanh_toan' => $total,
                    'payment_link' => $result['payUrl'],
                    'qr_code_url' => $result['qrCodeUrl'] ?? null
                ];
                
                $this->hoadonModel->updatePaymentInfo($hoadonId, $updateData);
                
                // Trả về kết quả
                ob_clean();
                echo json_encode([
                    'success' => true,
                    'payUrl' => $result['payUrl'],
                    'qrCodeUrl' => $result['qrCodeUrl'] ?? null,
                    'deeplink' => $result['deeplink'] ?? null,
                    'message' => 'Tạo yêu cầu thanh toán thành công!'
                ], JSON_UNESCAPED_UNICODE);
            } else {
                ob_clean();
                echo json_encode([
                    'success' => false,
                    'message' => 'MoMo API không trả về link thanh toán. Vui lòng thử lại sau!'
                ], JSON_UNESCAPED_UNICODE);
            }
            
        } catch (Exception $e) {
            error_log("Payment Create Error: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            
            // Xóa output buffer và trả về JSON error
            ob_clean();
            echo json_encode([
                'success' => false,
                'message' => 'Đã có lỗi xảy ra: ' . $e->getMessage() . '. Vui lòng kiểm tra log hoặc liên hệ hỗ trợ!'
            ], JSON_UNESCAPED_UNICODE);
        } catch (Error $e) {
            error_log("Payment Create Fatal Error: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            
            // Xóa output buffer và trả về JSON error
            ob_clean();
            echo json_encode([
                'success' => false,
                'message' => 'Lỗi nghiêm trọng: ' . $e->getMessage() . '. Vui lòng kiểm tra log!'
            ], JSON_UNESCAPED_UNICODE);
        } finally {
            // Khôi phục error reporting
            error_reporting($oldErrorReporting);
            if ($oldDisplayErrors !== false) {
                ini_set('display_errors', $oldDisplayErrors);
            }
            // Kết thúc output buffering
            ob_end_flush();
        }
        exit;
    }
    
    /**
     * Xử lý callback từ MoMo (IPN)
     */
    public function handleCallback()
    {
        try {
            // Lấy dữ liệu từ POST
            $data = $_POST;
            
            if (empty($data)) {
                // Nếu không có POST, thử lấy từ JSON
                $json = file_get_contents('php://input');
                $data = json_decode($json, true);
            }
            
            error_log("MoMo Callback Data: " . print_r($data, true));
            
            // Xác thực callback
            $verifyResult = $this->momoHelper->verifyCallback($data);
            
            if (!$verifyResult || !$verifyResult['valid']) {
                error_log("MoMo Callback Verification Failed");
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'Invalid signature']);
                exit;
            }
            
            // Lấy thông tin từ callback
            $orderId = $verifyResult['orderId'];
            $transId = $verifyResult['transId'];
            $amount = $verifyResult['amount'];
            $resultCode = $verifyResult['resultCode'];
            
            // Extract hoadon_id từ orderId (format: HD00000001)
            $hoadonId = intval(str_replace('HD', '', $orderId));
            
            if ($resultCode == 0) {
                // Thanh toán thành công
                $updateData = [
                    'trang_thai_thanh_toan' => 1, // Đã thanh toán
                    'ma_giao_dich_momo' => $transId,
                    'ngay_thanh_toan' => date('Y-m-d H:i:s')
                ];
                
                $this->hoadonModel->updatePaymentInfo($hoadonId, $updateData);
                
                // Cập nhật trạng thái hóa đơn thành "Đã xác nhận" nếu đang chờ
                $hoadon = $this->hoadonModel->getHoadonById($hoadonId);
                if ($hoadon && $hoadon['trangthai'] == 0) {
                    $this->hoadonModel->confirmHoadon($hoadonId);
                }
                
                // Gửi email xác nhận đặt tour sau khi thanh toán thành công
                try {
                    require_once './models/TourModel.php';
                    require_once './models/DeparturePlanModel.php';
                    require_once './commons/BookingEmailHelper.php';
                    
                    $tourModel = new TourModel();
                    $departurePlanModel = new DeparturePlanModel();
                    
                    $hoadon = $this->hoadonModel->getHoadonById($hoadonId);
                    $tour = $tourModel->getTourByID($hoadon['id_goi']);
                    
                    // Lấy thông tin lịch khởi hành nếu có
                    $departure = null;
                    if (!empty($hoadon['ngayvao'])) {
                        // Tìm departure plan theo ngày khởi hành
                        $departures = $departurePlanModel->getDeparturePlansByTourID($hoadon['id_goi']);
                        foreach ($departures as $dep) {
                            if ($dep['ngay_khoi_hanh'] == $hoadon['ngayvao']) {
                                $departure = $dep;
                                break;
                            }
                        }
                    }
                    
                    // Gửi email xác nhận đặt tour và thanh toán thành công
                    $emailSent = BookingEmailHelper::sendBookingConfirmation($hoadon, $tour, $departure);
                    
                    if ($emailSent) {
                        error_log("Booking confirmation email sent successfully after payment: Hoadon ID: $hoadonId");
                    } else {
                        error_log("Failed to send booking confirmation email after payment: Hoadon ID: $hoadonId");
                    }
                } catch (Exception $e) {
                    // Không chặn callback nếu gửi email lỗi
                    error_log("Error sending booking confirmation email after payment: " . $e->getMessage());
                }
                
                error_log("MoMo Payment Success: Hoadon ID: $hoadonId, Trans ID: $transId");
                
                http_response_code(200);
                echo json_encode(['status' => 'success', 'message' => 'Payment processed']);
            } else {
                // Thanh toán thất bại
                $updateData = [
                    'trang_thai_thanh_toan' => 0, // Chưa thanh toán
                    'ma_giao_dich_momo' => $transId
                ];
                
                $this->hoadonModel->updatePaymentInfo($hoadonId, $updateData);
                
                error_log("MoMo Payment Failed: Hoadon ID: $hoadonId, Result Code: $resultCode");
                
                http_response_code(200);
                echo json_encode(['status' => 'failed', 'message' => 'Payment failed']);
            }
            
        } catch (Exception $e) {
            error_log("Payment Callback Error: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Internal error']);
        }
        exit;
    }
    
    /**
     * Xử lý return URL từ MoMo (sau khi thanh toán)
     */
    public function handleReturn()
    {
        try {
            $orderId = $_GET['orderId'] ?? null;
            $resultCode = $_GET['resultCode'] ?? null;
            
            if (!$orderId) {
                header('Location: ' . BASE_URL . '?act=tours');
                exit;
            }
            
            // Extract hoadon_id từ orderId
            $hoadonId = intval(str_replace('HD', '', $orderId));
            
            // Lấy thông tin hóa đơn
            $hoadon = $this->hoadonModel->getHoadonById($hoadonId);
            
            if (!$hoadon) {
                header('Location: ' . BASE_URL . '?act=tours');
                exit;
            }
            
            // Redirect đến trang xác nhận với thông báo
            $message = '';
            if ($resultCode == 0) {
                $message = 'thanh-toan-thanh-cong';
            } else {
                $message = 'thanh-toan-that-bai';
            }
            
            header('Location: ' . BASE_URL . '?act=booking-confirm&id=' . $hoadonId . '&payment=' . $message);
            exit;
            
        } catch (Exception $e) {
            error_log("Payment Return Error: " . $e->getMessage());
            header('Location: ' . BASE_URL . '?act=tours');
            exit;
        }
    }
    
    /**
     * Lấy QR code thanh toán (nếu có)
     */
    public function getQRCode()
    {
        // Bắt đầu output buffering để tránh output trước khi set header
        ob_start();
        
        // Set header JSON ngay từ đầu
        header('Content-Type: application/json; charset=utf-8');
        
        // Tắt hiển thị lỗi để tránh output HTML
        $oldErrorReporting = error_reporting(E_ALL);
        $oldDisplayErrors = ini_set('display_errors', 0);
        
        try {
            $hoadonId = $_GET['hoadon_id'] ?? null;
            
            if (!$hoadonId) {
                echo json_encode([
                    'success' => false,
                    'message' => 'ID hóa đơn không hợp lệ!'
                ], JSON_UNESCAPED_UNICODE);
                exit;
            }
            
            $hoadon = $this->hoadonModel->getHoadonById($hoadonId);
            
            if (!$hoadon) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Không tìm thấy hóa đơn!'
                ], JSON_UNESCAPED_UNICODE);
                exit;
            }
            
            if (!empty($hoadon['qr_code_url'])) {
                echo json_encode([
                    'success' => true,
                    'qrCodeUrl' => $hoadon['qr_code_url']
                ], JSON_UNESCAPED_UNICODE);
            } else {
                // Nếu chưa có QR code, thử tạo payment request mới
                $total = $this->hoadonModel->calculateTotal($hoadonId);
                if ($total > 0) {
                    // Tạo payment request
                    $orderId = 'HD' . str_pad($hoadonId, 8, '0', STR_PAD_LEFT);
                    require_once './models/TourModel.php';
                    $tourModel = new TourModel();
                    $tour = $tourModel->getTourByID($hoadon['id_goi']);
                    $orderDescription = 'Thanh toán đặt tour: ' . ($tour['tengoi'] ?? 'Tour #' . $hoadonId);
                    
                    $paymentData = [
                        'orderId' => $orderId,
                        'amount' => $total,
                        'description' => $orderDescription,
                        'extraData' => json_encode(['hoadon_id' => $hoadonId])
                    ];
                    
                    $result = $this->momoHelper->createPaymentRequest($paymentData);
                    
                    if ($result && isset($result['qrCodeUrl'])) {
                        // Cập nhật QR code vào database
                        $this->hoadonModel->updatePaymentInfo($hoadonId, [
                            'qr_code_url' => $result['qrCodeUrl'],
                            'payment_link' => $result['payUrl'] ?? null
                        ]);
                        
                        echo json_encode([
                            'success' => true,
                            'qrCodeUrl' => $result['qrCodeUrl']
                        ], JSON_UNESCAPED_UNICODE);
                    } else {
                        echo json_encode([
                            'success' => false,
                            'message' => 'Không thể tạo mã QR thanh toán. Vui lòng thử lại!'
                        ], JSON_UNESCAPED_UNICODE);
                    }
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Chưa có mã QR thanh toán và không thể tạo mới!'
                    ], JSON_UNESCAPED_UNICODE);
                }
            }
            
        } catch (Exception $e) {
            error_log("Get QR Code Error: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            
            // Xóa output buffer và trả về JSON error
            ob_clean();
            echo json_encode([
                'success' => false,
                'message' => 'Đã có lỗi xảy ra: ' . $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        } catch (Error $e) {
            error_log("Get QR Code Fatal Error: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            
            // Xóa output buffer và trả về JSON error
            ob_clean();
            echo json_encode([
                'success' => false,
                'message' => 'Lỗi nghiêm trọng: ' . $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        } finally {
            // Khôi phục error reporting
            error_reporting($oldErrorReporting);
            if ($oldDisplayErrors !== false) {
                ini_set('display_errors', $oldDisplayErrors);
            }
            // Kết thúc output buffering
            ob_end_flush();
        }
        exit;
    }
}

