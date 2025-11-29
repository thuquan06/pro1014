<?php
/**
 * MoMo Payment Helper
 * Xử lý tích hợp thanh toán MoMo
 */
class MoMoPaymentHelper
{
    // MoMo API Endpoints
    const MOMO_API_ENDPOINT_SANDBOX = 'https://test-payment.momo.vn/v2/gateway/api/create';
    const MOMO_API_ENDPOINT_PRODUCTION = 'https://payment.momo.vn/v2/gateway/api/create';
    
    // Cấu hình MoMo (sẽ được lấy từ env.php hoặc config)
    private $partnerCode;
    private $accessKey;
    private $secretKey;
    private $endpoint;
    private $returnUrl;
    private $notifyUrl;
    private $isProduction;
    
    public function __construct()
    {
        // Đảm bảo BASE_URL được định nghĩa
        if (!defined('BASE_URL')) {
            require_once __DIR__ . '/env.php';
        }
        
        // Lấy cấu hình từ env.php hoặc config
        $this->isProduction = defined('MOMO_PRODUCTION') ? MOMO_PRODUCTION : false;
        $this->partnerCode = defined('MOMO_PARTNER_CODE') ? MOMO_PARTNER_CODE : '';
        $this->accessKey = defined('MOMO_ACCESS_KEY') ? MOMO_ACCESS_KEY : '';
        $this->secretKey = defined('MOMO_SECRET_KEY') ? MOMO_SECRET_KEY : '';
        
        $this->endpoint = $this->isProduction 
            ? self::MOMO_API_ENDPOINT_PRODUCTION 
            : self::MOMO_API_ENDPOINT_SANDBOX;
            
        $baseUrl = defined('BASE_URL') ? BASE_URL : 'http://localhost:8888/pro1014/';
        $this->returnUrl = $baseUrl . '?act=payment-return';
        $this->notifyUrl = $baseUrl . '?act=payment-callback';
    }
    
    /**
     * Getter methods để kiểm tra cấu hình
     */
    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }
        return null;
    }
    
    /**
     * Tạo yêu cầu thanh toán MoMo
     * 
     * @param array $orderInfo Thông tin đơn hàng
     * @return array|false Kết quả trả về từ MoMo hoặc false nếu lỗi
     */
    public function createPaymentRequest($orderInfo)
    {
        try {
            // Validate required fields
            if (empty($this->partnerCode) || empty($this->accessKey) || empty($this->secretKey)) {
                error_log("MoMo Payment: Missing configuration");
                return false;
            }
            
            $orderId = $orderInfo['orderId'] ?? null;
            $amount = $orderInfo['amount'] ?? 0;
            $orderDescription = $orderInfo['description'] ?? 'Thanh toán đặt tour';
            $extraData = $orderInfo['extraData'] ?? '';
            
            if (empty($orderId) || $amount <= 0) {
                error_log("MoMo Payment: Invalid order info");
                return false;
            }
            
            // Tạo requestId và requestType
            $requestId = time() . '';
            $requestType = "captureWallet"; // Hoặc "payWithATM", "payWithCC"
            
            // Tạo raw signature (theo thứ tự alphabet)
            $rawHash = "accessKey=" . $this->accessKey . 
                      "&amount=" . $amount . 
                      "&extraData=" . urlencode($extraData) . 
                      "&ipnUrl=" . urlencode($this->notifyUrl) . 
                      "&orderId=" . $orderId . 
                      "&orderInfo=" . urlencode($orderDescription) . 
                      "&partnerCode=" . $this->partnerCode . 
                      "&redirectUrl=" . urlencode($this->returnUrl) . 
                      "&requestId=" . $requestId . 
                      "&requestType=" . $requestType;
            
            // Tạo signature
            $signature = hash_hmac('sha256', $rawHash, $this->secretKey);
            
            // Tạo data array
            $data = array(
                'partnerCode' => $this->partnerCode,
                'partnerName' => "StarVel Travel",
                'storeId' => "StarVel",
                'requestId' => $requestId,
                'amount' => $amount,
                'orderId' => $orderId,
                'orderInfo' => $orderDescription,
                'redirectUrl' => $this->returnUrl,
                'ipnUrl' => $this->notifyUrl,
                'lang' => 'vi',
                'extraData' => $extraData,
                'requestType' => $requestType,
                'signature' => $signature
            );
            
            // Gửi request đến MoMo API
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->endpoint);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json'
            ));
            
            $result = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($httpCode == 200) {
                $jsonResult = json_decode($result, true);
                
                if (isset($jsonResult['payUrl'])) {
                    return [
                        'success' => true,
                        'payUrl' => $jsonResult['payUrl'],
                        'deeplink' => $jsonResult['deeplink'] ?? null,
                        'qrCodeUrl' => $jsonResult['qrCodeUrl'] ?? null,
                        'requestId' => $requestId,
                        'orderId' => $orderId
                    ];
                } else {
                    error_log("MoMo Payment Error: " . print_r($jsonResult, true));
                    return false;
                }
            } else {
                error_log("MoMo Payment HTTP Error: " . $httpCode . " - " . $result);
                return false;
            }
            
        } catch (Exception $e) {
            error_log("MoMo Payment Exception: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Xác thực callback từ MoMo
     * 
     * @param array $data Dữ liệu callback từ MoMo
     * @return bool True nếu hợp lệ, false nếu không
     */
    public function verifyCallback($data)
    {
        try {
            $orderId = $data['orderId'] ?? '';
            $partnerCode = $data['partnerCode'] ?? '';
            $amount = $data['amount'] ?? 0;
            $orderInfo = $data['orderInfo'] ?? '';
            $orderType = $data['orderType'] ?? '';
            $transId = $data['transId'] ?? '';
            $resultCode = $data['resultCode'] ?? '';
            $message = $data['message'] ?? '';
            $payType = $data['payType'] ?? '';
            $responseTime = $data['responseTime'] ?? '';
            $extraData = $data['extraData'] ?? '';
            $signature = $data['signature'] ?? '';
            
            // Tạo raw signature để verify (theo thứ tự alphabet)
            $rawHash = "accessKey=" . $this->accessKey . 
                      "&amount=" . $amount . 
                      "&extraData=" . urlencode($extraData) . 
                      "&message=" . urlencode($message) . 
                      "&orderId=" . $orderId . 
                      "&orderInfo=" . urlencode($orderInfo) . 
                      "&orderType=" . $orderType . 
                      "&partnerCode=" . $partnerCode . 
                      "&payType=" . $payType . 
                      "&requestId=" . ($data['requestId'] ?? '') . 
                      "&responseTime=" . $responseTime . 
                      "&resultCode=" . $resultCode . 
                      "&transId=" . $transId;
            
            $checkSignature = hash_hmac('sha256', $rawHash, $this->secretKey);
            
            if ($checkSignature === $signature && $partnerCode === $this->partnerCode) {
                return [
                    'valid' => true,
                    'orderId' => $orderId,
                    'transId' => $transId,
                    'amount' => $amount,
                    'resultCode' => $resultCode,
                    'message' => $message
                ];
            }
            
            return false;
            
        } catch (Exception $e) {
            error_log("MoMo Verify Callback Exception: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Tạo mã QR tĩnh (nếu cần)
     * 
     * @param float $amount Số tiền
     * @param string $description Mô tả
     * @return string|false URL mã QR hoặc false nếu lỗi
     */
    public function generateStaticQR($amount, $description = '')
    {
        // Implementation cho QR tĩnh nếu cần
        // Có thể sử dụng thư viện QR code generator
        return false;
    }
}

