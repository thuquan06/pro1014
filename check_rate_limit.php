<?php
/**
 * Check Rate Limit Status
 * Xem trạng thái rate limit hiện tại
 */

session_start();

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Rate Limit</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            background: #1e1e1e;
            color: #00ff00;
        }
        .container {
            background: #2d2d2d;
            padding: 30px;
            border-radius: 10px;
            border: 2px solid #00ff00;
            box-shadow: 0 0 20px rgba(0,255,0,0.3);
        }
        h1 {
            color: #00ff00;
            text-align: center;
            margin-bottom: 30px;
            text-shadow: 0 0 10px #00ff00;
        }
        .info-box {
            background: #1e1e1e;
            padding: 20px;
            margin: 15px 0;
            border-left: 4px solid #00ff00;
        }
        .key {
            color: #ffff00;
            font-weight: bold;
        }
        .value {
            color: #00ff00;
        }
        .ip {
            color: #00ffff;
        }
        .session-data {
            background: #1a1a1a;
            padding: 15px;
            margin: 10px 0;
            border: 1px solid #444;
            overflow-x: auto;
        }
        pre {
            margin: 0;
            color: #00ff00;
        }
        .btn {
            display: inline-block;
            padding: 12px 25px;
            margin: 10px 5px;
            background: #00ff00;
            color: #000;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            border: none;
            cursor: pointer;
        }
        .btn:hover {
            background: #00cc00;
            box-shadow: 0 0 15px #00ff00;
        }
        .btn-danger {
            background: #ff0000;
            color: #fff;
        }
        .btn-danger:hover {
            background: #cc0000;
            box-shadow: 0 0 15px #ff0000;
        }
        .status {
            padding: 5px 10px;
            border-radius: 3px;
            display: inline-block;
        }
        .status-ok {
            background: #00ff00;
            color: #000;
        }
        .status-locked {
            background: #ff0000;
            color: #fff;
        }
        .center {
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>⚡ RATE LIMIT STATUS ⚡</h1>
        
        <?php
        // Get IP
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $sessionId = session_id();
        
        echo "<div class='info-box'>";
        echo "<div><span class='key'>IP Address:</span> <span class='ip'>{$ip}</span></div>";
        echo "<div><span class='key'>Session ID:</span> <span class='value'>{$sessionId}</span></div>";
        echo "</div>";
        
        // Find all rate limit keys
        $rateLimitKeys = [];
        foreach ($_SESSION as $key => $value) {
            if (strpos($key, 'rate_limit_') === 0) {
                $rateLimitKeys[$key] = $value;
            }
        }
        
        if (empty($rateLimitKeys)) {
            echo "<div class='info-box'>";
            echo "<h3>✓ NO RATE LIMITS ACTIVE</h3>";
            echo "<p>Chưa có rate limit nào được ghi nhận.</p>";
            echo "<p>Bạn có thể đăng nhập tự do (5 lần thử).</p>";
            echo "</div>";
        } else {
            echo "<div class='info-box'>";
            echo "<h3>⚠️ ACTIVE RATE LIMITS: " . count($rateLimitKeys) . "</h3>";
            echo "</div>";
            
            foreach ($rateLimitKeys as $key => $data) {
                $now = time();
                $attempts = $data['attempts'] ?? 0;
                $firstAttempt = $data['first_attempt'] ?? $now;
                $lockedUntil = $data['locked_until'] ?? 0;
                
                $remaining = max(0, 5 - $attempts);
                $isLocked = $lockedUntil > $now;
                
                echo "<div class='session-data'>";
                echo "<div><span class='key'>Key:</span> <span class='value'>{$key}</span></div>";
                echo "<div><span class='key'>Attempts:</span> <span class='value'>{$attempts}/5</span></div>";
                echo "<div><span class='key'>Remaining:</span> <span class='value'>{$remaining}</span></div>";
                echo "<div><span class='key'>First Attempt:</span> <span class='value'>" . date('H:i:s', $firstAttempt) . "</span></div>";
                
                if ($isLocked) {
                    $waitTime = $lockedUntil - $now;
                    $waitMinutes = ceil($waitTime / 60);
                    echo "<div><span class='key'>Status:</span> <span class='status status-locked'>🔒 LOCKED</span></div>";
                    echo "<div><span class='key'>Unlock in:</span> <span class='value'>{$waitMinutes} phút</span></div>";
                    echo "<div><span class='key'>Locked until:</span> <span class='value'>" . date('H:i:s', $lockedUntil) . "</span></div>";
                } else {
                    if ($attempts > 0) {
                        echo "<div><span class='key'>Status:</span> <span class='status' style='background:#ffff00;color:#000'>⚠️ WARNING</span></div>";
                    } else {
                        echo "<div><span class='key'>Status:</span> <span class='status status-ok'>✓ OK</span></div>";
                    }
                }
                
                echo "</div>";
            }
        }
        
        // Show all session data (for debug)
        echo "<div class='info-box'>";
        echo "<h3>📊 SESSION DATA DEBUG</h3>";
        echo "<div class='session-data'>";
        echo "<pre>";
        print_r($_SESSION);
        echo "</pre>";
        echo "</div>";
        echo "</div>";
        ?>
        
        <div class="center">
            <a href="reset_rate_limit.php" class="btn btn-danger">🔓 Reset Rate Limit</a>
            <a href="?act=login" class="btn">← Quay lại Login</a>
            <button onclick="location.reload()" class="btn">🔄 Refresh</button>
        </div>
    </div>
</body>
</html>
