<?php
/**
 * VoucherModel - Quản lý mã voucher
 */
class VoucherModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->ensureTableExists();
    }

    private function ensureTableExists()
    {
        $exists = $this->conn->query("SHOW TABLES LIKE 'vouchers'")->rowCount() > 0;
        if (!$exists) {
            $sql = "CREATE TABLE `vouchers` (
                `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `code` VARCHAR(50) NOT NULL UNIQUE,
                `discount_type` ENUM('percent','amount') NOT NULL DEFAULT 'percent',
                `discount_value` DECIMAL(15,2) NOT NULL DEFAULT 0,
                `start_date` DATE NULL DEFAULT NULL,
                `end_date` DATE NULL DEFAULT NULL,
                `usage_limit` INT(11) NULL DEFAULT NULL,
                `used_count` INT(11) NOT NULL DEFAULT 0,
                `min_order_amount` DECIMAL(15,2) NULL DEFAULT NULL,
                `is_active` TINYINT(1) NOT NULL DEFAULT 1,
                `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
            $this->conn->exec($sql);
        } else {
            $columns = $this->conn->query("SHOW COLUMNS FROM vouchers")->fetchAll(PDO::FETCH_COLUMN);
            $need = [
                'discount_type' => "ALTER TABLE vouchers ADD COLUMN `discount_type` ENUM('percent','amount') NOT NULL DEFAULT 'percent' AFTER code",
                'discount_value' => "ALTER TABLE vouchers ADD COLUMN `discount_value` DECIMAL(15,2) NOT NULL DEFAULT 0 AFTER discount_type",
                'start_date' => "ALTER TABLE vouchers ADD COLUMN `start_date` DATE NULL DEFAULT NULL AFTER discount_value",
                'end_date' => "ALTER TABLE vouchers ADD COLUMN `end_date` DATE NULL DEFAULT NULL AFTER start_date",
                'usage_limit' => "ALTER TABLE vouchers ADD COLUMN `usage_limit` INT(11) NULL DEFAULT NULL AFTER end_date",
                'used_count' => "ALTER TABLE vouchers ADD COLUMN `used_count` INT(11) NOT NULL DEFAULT 0 AFTER usage_limit",
                'min_order_amount' => "ALTER TABLE vouchers ADD COLUMN `min_order_amount` DECIMAL(15,2) NULL DEFAULT NULL AFTER used_count",
                'is_active' => "ALTER TABLE vouchers ADD COLUMN `is_active` TINYINT(1) NOT NULL DEFAULT 1 AFTER min_order_amount",
                'created_at' => "ALTER TABLE vouchers ADD COLUMN `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER is_active",
                'updated_at' => "ALTER TABLE vouchers ADD COLUMN `updated_at` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP AFTER created_at",
            ];
            foreach ($need as $col => $ddl) {
                if (!in_array($col, $columns)) {
                    $this->conn->exec($ddl);
                }
            }
        }
    }

    public function getAll($filters = [])
    {
        $sql = "SELECT * FROM vouchers WHERE 1=1";
        $params = [];
        
        // Xử lý filter trạng thái
        if (isset($filters['status']) && $filters['status'] !== '') {
            $status = $filters['status'];
            $now = date('Y-m-d');
            
            if ($status === '0' || $status === '1') {
                // Trạng thái đơn giản: hoạt động hoặc không hoạt động
                $sql .= " AND is_active = :status";
                $params[':status'] = (int)$status;
            } elseif ($status === 'expired') {
                // Hết hạn: có end_date và đã qua ngày hiện tại
                $sql .= " AND is_active = 1 AND end_date IS NOT NULL AND end_date < :now";
                $params[':now'] = $now;
            } elseif ($status === 'not_started') {
                // Chưa bắt đầu: có start_date và chưa đến ngày hiện tại
                $sql .= " AND is_active = 1 AND start_date IS NOT NULL AND start_date > :now";
                $params[':now'] = $now;
            } elseif ($status === 'out_of_uses') {
                // Hết lượt dùng: đã đạt giới hạn sử dụng
                $sql .= " AND is_active = 1 AND usage_limit IS NOT NULL AND used_count >= usage_limit";
            }
        }
        
        if (!empty($filters['q'])) {
            $sql .= " AND code LIKE :q";
            $params[':q'] = '%' . $filters['q'] . '%';
        }
        $sql .= " ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM vouchers WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findActiveByCode($code)
    {
        $stmt = $this->conn->prepare("SELECT * FROM vouchers WHERE code = :code AND is_active = 1 LIMIT 1");
        $stmt->execute([':code' => $code]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $sql = "INSERT INTO vouchers (code, discount_type, discount_value, start_date, end_date, usage_limit, min_order_amount, is_active)
                VALUES (:code, :discount_type, :discount_value, :start_date, :end_date, :usage_limit, :min_order_amount, :is_active)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':code' => strtoupper(trim($data['code'])),
            ':discount_type' => $data['discount_type'],
            ':discount_value' => !empty($data['discount_value']) ? (float)$data['discount_value'] : 0,
            ':start_date' => !empty($data['start_date']) ? $data['start_date'] : null,
            ':end_date' => !empty($data['end_date']) ? $data['end_date'] : null,
            ':usage_limit' => (empty($data['usage_limit']) || $data['usage_limit'] === '') ? null : (int)$data['usage_limit'],
            ':min_order_amount' => (isset($data['min_order_amount']) && $data['min_order_amount'] !== '') ? (float)$data['min_order_amount'] : null,
            ':is_active' => isset($data['is_active']) ? (int)$data['is_active'] : 0
        ]);
    }

    public function update($id, $data)
    {
        $sql = "UPDATE vouchers SET
                    code = :code,
                    discount_type = :discount_type,
                    discount_value = :discount_value,
                    start_date = :start_date,
                    end_date = :end_date,
                    usage_limit = :usage_limit,
                    min_order_amount = :min_order_amount,
                    is_active = :is_active
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':code' => strtoupper(trim($data['code'])),
            ':discount_type' => $data['discount_type'],
            ':discount_value' => !empty($data['discount_value']) ? (float)$data['discount_value'] : 0,
            ':start_date' => !empty($data['start_date']) ? $data['start_date'] : null,
            ':end_date' => !empty($data['end_date']) ? $data['end_date'] : null,
            ':usage_limit' => (empty($data['usage_limit']) || $data['usage_limit'] === '') ? null : (int)$data['usage_limit'],
            ':min_order_amount' => (isset($data['min_order_amount']) && $data['min_order_amount'] !== '') ? (float)$data['min_order_amount'] : null,
            ':is_active' => isset($data['is_active']) ? (int)$data['is_active'] : 0,
            ':id' => $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM vouchers WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function toggleStatus($id)
    {
        $stmt = $this->conn->prepare("UPDATE vouchers SET is_active = IF(is_active=1,0,1) WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function increaseUsage($id)
    {
        $stmt = $this->conn->prepare("UPDATE vouchers SET used_count = used_count + 1 WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}


