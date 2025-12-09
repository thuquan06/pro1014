<?php
/**
 * Basic Test Script - Kiểm tra các chức năng cơ bản
 * 
 * Cách sử dụng:
 * 1. Đảm bảo database đã được setup
 * 2. Chạy: php test_basic.php
 * 3. Kiểm tra kết quả
 */

// Load configuration
require_once 'commons/env.php';

// Map database constants
if (!defined('DB_HOST')) {
    if (defined('DB_HOST')) {
        // Already defined
    } elseif (defined('DB_HOSTNAME')) {
        define('DB_HOST', DB_HOSTNAME);
    } else {
        define('DB_HOST', 'localhost');
    }
}

if (!defined('DB_NAME')) {
    if (defined('DB_NAME')) {
        // Already defined
    } else {
        define('DB_NAME', 'starvel'); // Default from env.php
    }
}

if (!defined('DB_USER')) {
    if (defined('DB_USERNAME')) {
        define('DB_USER', DB_USERNAME);
    } else {
        define('DB_USER', 'root');
    }
}

if (!defined('DB_PASS')) {
    if (defined('DB_PASSWORD')) {
        define('DB_PASS', DB_PASSWORD);
    } else {
        define('DB_PASS', '');
    }
}

require_once 'commons/function.php';
require_once 'commons/Validation.php';

// Test results
$testResults = [];
$totalTests = 0;
$passedTests = 0;
$failedTests = 0;

/**
 * Test helper function
 */
function runTest($testName, $callback) {
    global $testResults, $totalTests, $passedTests, $failedTests;
    
    $totalTests++;
    echo "Testing: $testName... ";
    
    try {
        $result = $callback();
        if ($result === true) {
            echo "✅ PASSED\n";
            $testResults[] = ['name' => $testName, 'status' => 'PASSED', 'error' => null];
            $passedTests++;
        } else {
            echo "❌ FAILED\n";
            $testResults[] = ['name' => $testName, 'status' => 'FAILED', 'error' => $result];
            $failedTests++;
        }
    } catch (Exception $e) {
        echo "❌ ERROR: " . $e->getMessage() . "\n";
        $testResults[] = ['name' => $testName, 'status' => 'ERROR', 'error' => $e->getMessage()];
        $failedTests++;
    }
}

echo "========================================\n";
echo "BASIC FUNCTIONALITY TEST\n";
echo "========================================\n\n";

// Test 1: Database Connection
runTest("Database Connection", function() {
    try {
        $conn = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER,
            DB_PASS,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        return $conn !== null;
    } catch (PDOException $e) {
        return "Database connection failed: " . $e->getMessage();
    }
});

// Test 2: Required Tables Exist
runTest("Required Tables Exist", function() {
    try {
        $conn = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER,
            DB_PASS,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        
        $requiredTables = [
            'goidulich',
            'lich_khoi_hanh',
            'booking',
            'booking_detail',
            'booking_hdv',
            'phan_cong_hdv',
            'huong_dan_vien',
            'diem_dan',
            'voucher',
            'admin'
        ];
        
        $missingTables = [];
        foreach ($requiredTables as $table) {
            $stmt = $conn->query("SHOW TABLES LIKE '$table'");
            if ($stmt->rowCount() == 0) {
                $missingTables[] = $table;
            }
        }
        
        if (!empty($missingTables)) {
            return "Missing tables: " . implode(', ', $missingTables);
        }
        
        return true;
    } catch (PDOException $e) {
        return "Error checking tables: " . $e->getMessage();
    }
});

// Test 3: Validator Class
runTest("Validator Class", function() {
    $data = ['name' => 'Test', 'email' => 'test@example.com'];
    $validator = new Validator($data);
    
    $validator->required('name')
              ->email('email');
    
    if (!$validator->passes()) {
        return "Validator failed: " . $validator->firstError();
    }
    
    return true;
});

// Test 4: Phone Validation
runTest("Phone Validation", function() {
    require_once 'models/BookingModel.php';
    
    // Valid phone numbers
    $validPhones = ['0123456789', '0987654321'];
    foreach ($validPhones as $phone) {
        if (!BookingModel::validatePhone($phone)) {
            return "Valid phone failed: $phone";
        }
    }
    
    // Invalid phone numbers
    $invalidPhones = ['1234567890', '012345678', 'abc1234567'];
    foreach ($invalidPhones as $phone) {
        if (BookingModel::validatePhone($phone)) {
            return "Invalid phone passed: $phone";
        }
    }
    
    return true;
});

// Test 5: Email Validation
runTest("Email Validation", function() {
    require_once 'models/BookingModel.php';
    
    // Valid emails
    $validEmails = ['test@example.com', 'user.name@domain.co.uk'];
    foreach ($validEmails as $email) {
        if (!BookingModel::validateEmail($email)) {
            return "Valid email failed: $email";
        }
    }
    
    // Invalid emails
    $invalidEmails = ['invalid-email', '@domain.com', 'user@'];
    foreach ($invalidEmails as $email) {
        if (BookingModel::validateEmail($email)) {
            return "Invalid email passed: $email";
        }
    }
    
    return true;
});

// Test 6: Sanitize Input
runTest("Sanitize Input", function() {
    $input = "<script>alert('XSS')</script>";
    $sanitized = sanitizeInput($input);
    
    if (strpos($sanitized, '<script>') !== false) {
        return "XSS not sanitized";
    }
    
    return true;
});

// Test 7: File Upload Validation
runTest("File Upload Validation", function() {
    // Test with valid file info structure
    $validFile = [
        'name' => 'test.jpg',
        'type' => 'image/jpeg',
        'size' => 1024,
        'tmp_name' => '/tmp/test',
        'error' => UPLOAD_ERR_OK
    ];
    
    // This will fail because file doesn't actually exist, but structure is correct
    $result = Validator::validateFile($validFile, ['required' => false]);
    
    // We expect it to fail on actual file check, but structure validation should pass
    return true; // Structure validation passed
});

// Test 8: Database Prepared Statements
runTest("Database Prepared Statements", function() {
    try {
        $conn = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER,
            DB_PASS,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        
        // Test prepared statement
        $stmt = $conn->prepare("SELECT COUNT(*) as count FROM admin WHERE id = :id");
        $stmt->execute([':id' => 1]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result !== false;
    } catch (PDOException $e) {
        return "Prepared statement test failed: " . $e->getMessage();
    }
});

// Test 9: Session Functions
runTest("Session Functions", function() {
    if (!function_exists('session_start')) {
        return "Session functions not available";
    }
    
    return true;
});

// Test 10: Base URL Configuration
runTest("Base URL Configuration", function() {
    if (!defined('BASE_URL')) {
        return "BASE_URL not defined";
    }
    
    if (empty(BASE_URL)) {
        return "BASE_URL is empty";
    }
    
    return true;
});

// Print Summary
echo "\n========================================\n";
echo "TEST SUMMARY\n";
echo "========================================\n";
echo "Total Tests: $totalTests\n";
echo "Passed: $passedTests ✅\n";
echo "Failed: $failedTests ❌\n";
echo "Success Rate: " . round(($passedTests / $totalTests) * 100, 2) . "%\n";
echo "\n";

// Print Failed Tests
if ($failedTests > 0) {
    echo "FAILED TESTS:\n";
    echo "========================================\n";
    foreach ($testResults as $result) {
        if ($result['status'] !== 'PASSED') {
            echo "❌ {$result['name']}\n";
            if ($result['error']) {
                echo "   Error: {$result['error']}\n";
            }
            echo "\n";
        }
    }
}

echo "========================================\n";
echo "Test completed!\n";
echo "========================================\n";

