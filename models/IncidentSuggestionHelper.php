<?php
/**
 * IncidentSuggestionHelper - Hệ thống gợi ý xử lý sự cố tự động
 * Dựa trên loại sự cố và mức độ nghiêm trọng
 */
class IncidentSuggestionHelper
{
    /**
     * Lấy gợi ý xử lý dựa trên loại sự cố và mức độ
     */
    public static function getSuggestion($loaiSuCo, $mucDo)
    {
        $suggestions = self::getSuggestionTemplates();
        
        if (!isset($suggestions[$loaiSuCo])) {
            return self::getDefaultSuggestion($mucDo);
        }
        
        $typeSuggestions = $suggestions[$loaiSuCo];
        
        // Lấy gợi ý theo mức độ
        if (isset($typeSuggestions[$mucDo])) {
            return $typeSuggestions[$mucDo];
        }
        
        // Fallback về mức độ thấp nếu không có
        return $typeSuggestions['thap'] ?? self::getDefaultSuggestion($mucDo);
    }
    
    /**
     * Tự động đề xuất mức độ dựa trên mô tả (AI đơn giản)
     */
    public static function suggestSeverity($loaiSuCo, $moTa)
    {
        $moTa = mb_strtolower($moTa);
        
        // Các từ khóa chỉ mức độ cao/nghiêm trọng
        $highSeverityKeywords = [
            'tai nạn', 'ngất', 'chảy máu', 'gãy', 'bất tỉnh', 'cấp cứu',
            'hộ chiếu', 'mất hộ chiếu', 'mất cmnd', 'mất cccd',
            'trộm', 'cướp', 'tấn công', 'bạo lực',
            'hỏng xe', 'tai nạn xe', 'lật xe'
        ];
        
        // Các từ khóa chỉ mức độ trung bình
        $mediumSeverityKeywords = [
            'say xe', 'buồn nôn', 'chóng mặt', 'mệt mỏi',
            'trễ', 'lạc', 'thất lạc',
            'kẹt xe', 'tắc đường', 'đổi lộ trình'
        ];
        
        // Kiểm tra từ khóa
        foreach ($highSeverityKeywords as $keyword) {
            if (mb_strpos($moTa, $keyword) !== false) {
                // Mất hộ chiếu luôn là cao
                if (mb_strpos($moTa, 'hộ chiếu') !== false || mb_strpos($moTa, 'passport') !== false) {
                    return 'cao';
                }
                return 'nghiem_trong';
            }
        }
        
        foreach ($mediumSeverityKeywords as $keyword) {
            if (mb_strpos($moTa, $keyword) !== false) {
                return 'trung_binh';
            }
        }
        
        // Mặc định theo loại sự cố
        $defaultSeverity = [
            'mat_do' => 'trung_binh',
            'say_xe' => 'thap',
            'tre_gio' => 'thap',
            'thoi_tiet_xau' => 'thap',
            'tai_nan' => 'nghiem_trong',
            'benh_tat' => 'trung_binh',
            'khach_dac_biet' => 'cao'
        ];
        
        return $defaultSeverity[$loaiSuCo] ?? 'thap';
    }
    
    /**
     * Lấy template gợi ý xử lý
     */
    private static function getSuggestionTemplates()
    {
        return [
            'mat_do' => [
                'thap' => [
                    'title' => 'Xử lý mất đồ - Mức độ thấp',
                    'steps' => [
                        '1. Hỏi khách mô tả chi tiết đồ vật bị mất (màu sắc, kích thước, đặc điểm)',
                        '2. Kiểm tra lại các vị trí khách đã đến (phòng khách sạn, xe, điểm tham quan)',
                        '3. Liên hệ với nhân viên khách sạn/điểm tham quan để tìm kiếm',
                        '4. Ghi nhận thông tin và báo cáo cho công ty',
                        '5. Hướng dẫn khách làm đơn trình báo nếu cần'
                    ],
                    'contact' => 'Liên hệ: Nhân viên khách sạn, Bảo vệ điểm tham quan',
                    'note' => 'Theo dõi và cập nhật tình hình cho khách'
                ],
                'trung_binh' => [
                    'title' => 'Xử lý mất đồ - Mức độ trung bình',
                    'steps' => [
                        '1. Ngay lập tức hỏi khách mô tả chi tiết đồ vật bị mất',
                        '2. Kiểm tra camera an ninh tại vị trí (nếu có)',
                        '3. Liên hệ với quản lý khách sạn/điểm tham quan để tìm kiếm',
                        '4. Làm đơn trình báo tại công an địa phương',
                        '5. Báo cáo ngay cho công ty điều hành tour',
                        '6. Hỗ trợ khách làm các thủ tục cần thiết (nếu mất giấy tờ)'
                    ],
                    'contact' => 'Liên hệ: Công an địa phương, Quản lý khách sạn, Công ty điều hành tour',
                    'note' => 'Cần theo dõi sát và cập nhật thường xuyên'
                ],
                'cao' => [
                    'title' => 'Xử lý mất đồ - Mức độ cao (Mất hộ chiếu/CMND)',
                    'steps' => [
                        '1. NGAY LẬP TỨC: Xác nhận thông tin khách và đồ vật bị mất',
                        '2. Đưa khách đến công an địa phương để làm đơn trình báo',
                        '3. Liên hệ Đại sứ quán/Lãnh sự quán Việt Nam tại địa phương',
                        '4. Báo cáo KHẨN CẤP cho công ty điều hành tour và điều phối viên',
                        '5. Hỗ trợ khách làm thủ tục cấp lại giấy tờ tạm thời',
                        '6. Điều chỉnh lịch trình tour để khách có thời gian làm thủ tục',
                        '7. Lưu giữ bản sao giấy tờ và đơn trình báo'
                    ],
                    'contact' => 'KHẨN CẤP: Đại sứ quán/Lãnh sự quán, Công an địa phương, Công ty điều hành tour',
                    'note' => 'Ưu tiên cao nhất - cần xử lý ngay lập tức'
                ],
                'nghiem_trong' => [
                    'title' => 'Xử lý mất đồ - Mức độ nghiêm trọng',
                    'steps' => [
                        '1. NGAY LẬP TỨC: Xác nhận và ghi nhận đầy đủ thông tin',
                        '2. Đưa khách đến công an địa phương làm đơn trình báo',
                        '3. Liên hệ Đại sứ quán/Lãnh sự quán ngay lập tức',
                        '4. Báo cáo KHẨN CẤP cho công ty và điều phối viên trực',
                        '5. Hỗ trợ khách làm mọi thủ tục cần thiết',
                        '6. Điều chỉnh toàn bộ lịch trình nếu cần',
                        '7. Lưu trữ đầy đủ bằng chứng và giấy tờ'
                    ],
                    'contact' => 'KHẨN CẤP: Đại sứ quán, Công an, Công ty điều hành tour, Điều phối viên trực',
                    'note' => 'Cần xử lý ngay và theo dõi 24/7'
                ]
            ],
            'say_xe' => [
                'thap' => [
                    'title' => 'Xử lý say xe - Mức độ thấp',
                    'steps' => [
                        '1. Cho khách nghỉ ngơi, mở cửa xe để thông gió',
                        '2. Phát túi nôn và nước uống cho khách',
                        '3. Đề nghị khách đổi vị trí ghế (ngồi gần cửa sổ, phía trước)',
                        '4. Yêu cầu tài xế điều chỉnh tốc độ, lái xe mượt mà hơn',
                        '5. Theo dõi tình trạng khách'
                    ],
                    'contact' => 'Không cần liên hệ',
                    'note' => 'Xử lý đơn giản tại chỗ'
                ],
                'trung_binh' => [
                    'title' => 'Xử lý say xe - Mức độ trung bình',
                    'steps' => [
                        '1. Dừng xe tại điểm an toàn để khách nghỉ ngơi',
                        '2. Cho khách uống nước, thuốc say xe (nếu có)',
                        '3. Đổi vị trí ghế cho khách',
                        '4. Yêu cầu tài xế điều chỉnh tốc độ và cách lái',
                        '5. Nếu cần, dừng lại lâu hơn hoặc tìm trạm y tế gần nhất',
                        '6. Báo cáo cho công ty nếu ảnh hưởng đến lịch trình'
                    ],
                    'contact' => 'Trạm y tế gần nhất (nếu cần), Công ty điều hành tour',
                    'note' => 'Cần theo dõi và có thể cần hỗ trợ y tế'
                ],
                'cao' => [
                    'title' => 'Xử lý say xe - Mức độ cao',
                    'steps' => [
                        '1. NGAY LẬP TỨC dừng xe tại điểm an toàn',
                        '2. Đưa khách ra ngoài, để nằm nghỉ nếu cần',
                        '3. Liên hệ trạm y tế/trung tâm y tế gần nhất',
                        '4. Nếu nghiêm trọng, gọi cấp cứu 115',
                        '5. Báo cáo ngay cho công ty điều hành tour',
                        '6. Điều chỉnh lịch trình tour nếu cần',
                        '7. Lưu giữ thông tin y tế và báo cáo'
                    ],
                    'contact' => 'Cấp cứu 115, Trạm y tế, Công ty điều hành tour',
                    'note' => 'Cần hỗ trợ y tế ngay lập tức'
                ]
            ],
            'tre_gio' => [
                'thap' => [
                    'title' => 'Xử lý trễ giờ - Mức độ thấp',
                    'steps' => [
                        '1. Thông báo cho toàn đoàn về việc trễ giờ',
                        '2. Liên hệ với khách trễ để xác nhận vị trí',
                        '3. Điều chỉnh nhẹ lịch trình nếu cần',
                        '4. Đảm bảo không bỏ lỡ các điểm tham quan quan trọng'
                    ],
                    'contact' => 'Không cần liên hệ',
                    'note' => 'Xử lý đơn giản, điều chỉnh lịch trình nhẹ'
                ],
                'trung_binh' => [
                    'title' => 'Xử lý trễ giờ - Mức độ trung bình',
                    'steps' => [
                        '1. Thông báo ngay cho toàn đoàn',
                        '2. Liên hệ khách trễ qua điện thoại, nhóm tour',
                        '3. Xác định điểm hẹn rõ ràng',
                        '4. Điều chỉnh lịch trình để không ảnh hưởng nhiều',
                        '5. Báo cáo cho công ty nếu trễ quá lâu',
                        '6. Có phương án dự phòng nếu khách không đến được'
                    ],
                    'contact' => 'Khách trễ, Công ty điều hành tour',
                    'note' => 'Cần theo dõi và có phương án dự phòng'
                ],
                'cao' => [
                    'title' => 'Xử lý trễ giờ - Mức độ cao (Lạc đoàn)',
                    'steps' => [
                        '1. NGAY LẬP TỨC: Liên hệ khách qua mọi kênh (điện thoại, nhóm tour)',
                        '2. Xác định vị trí khách (nếu có GPS/app)',
                        '3. Thông báo cho bảo vệ địa phương/trung tâm thương mại',
                        '4. Đặt điểm hẹn rõ ràng và dễ tìm',
                        '5. Báo cáo ngay cho công ty điều hành tour',
                        '6. Nếu không liên hệ được sau 30 phút, báo công an địa phương',
                        '7. Điều chỉnh lịch trình để đợi khách hoặc có phương án thay thế'
                    ],
                    'contact' => 'Khách lạc, Bảo vệ địa phương, Công an (nếu cần), Công ty điều hành tour',
                    'note' => 'Ưu tiên tìm khách, cần hành động nhanh'
                ]
            ],
            'tai_nan' => [
                'nghiem_trong' => [
                    'title' => 'Xử lý tai nạn - Mức độ nghiêm trọng',
                    'steps' => [
                        '1. NGAY LẬP TỨC: Đảm bảo an toàn cho mọi người',
                        '2. Gọi cấp cứu 115 ngay lập tức',
                        '3. Sơ cứu nạn nhân (nếu có thể)',
                        '4. Báo công an địa phương',
                        '5. Báo cáo KHẨN CẤP cho công ty điều hành tour và điều phối viên',
                        '6. Chụp ảnh hiện trường, lấy thông tin nhân chứng',
                        '7. Đưa nạn nhân đến bệnh viện gần nhất',
                        '8. Thông báo cho gia đình nạn nhân',
                        '9. Lưu trữ đầy đủ giấy tờ, báo cáo, bằng chứng'
                    ],
                    'contact' => 'KHẨN CẤP: Cấp cứu 115, Công an, Bệnh viện, Công ty điều hành tour, Điều phối viên',
                    'note' => 'Ưu tiên cao nhất - an toàn tính mạng'
                ]
            ],
            'khach_dac_biet' => [
                'cao' => [
                    'title' => 'Xử lý sự cố khách đặc biệt - Mức độ cao',
                    'steps' => [
                        '1. NGAY LẬP TỨC: Tách các bên liên quan để tránh xung đột',
                        '2. Lắng nghe và ghi nhận quan điểm của các bên',
                        '3. Tìm giải pháp hòa giải tại chỗ',
                        '4. Nếu không giải quyết được, báo cáo ngay cho công ty',
                        '5. Liên hệ công an địa phương nếu có bạo lực',
                        '6. Lưu trữ đầy đủ thông tin và bằng chứng',
                        '7. Điều chỉnh lịch trình để tránh tiếp xúc giữa các bên'
                    ],
                    'contact' => 'Công ty điều hành tour, Công an (nếu có bạo lực)',
                    'note' => 'Cần xử lý khéo léo và nhanh chóng'
                ]
            ]
        ];
    }
    
    /**
     * Gợi ý mặc định
     */
    private static function getDefaultSuggestion($mucDo)
    {
        $defaults = [
            'thap' => [
                'title' => 'Xử lý sự cố - Mức độ thấp',
                'steps' => [
                    '1. Ghi nhận đầy đủ thông tin sự cố',
                    '2. Xử lý tại chỗ nếu có thể',
                    '3. Thông báo cho khách liên quan',
                    '4. Báo cáo cho công ty'
                ],
                'contact' => 'Công ty điều hành tour',
                'note' => 'Xử lý đơn giản'
            ],
            'trung_binh' => [
                'title' => 'Xử lý sự cố - Mức độ trung bình',
                'steps' => [
                    '1. Ghi nhận đầy đủ thông tin',
                    '2. Xử lý ngay tại chỗ',
                    '3. Thông báo cho khách và công ty',
                    '4. Theo dõi tình hình',
                    '5. Điều chỉnh lịch trình nếu cần'
                ],
                'contact' => 'Công ty điều hành tour',
                'note' => 'Cần theo dõi'
            ],
            'cao' => [
                'title' => 'Xử lý sự cố - Mức độ cao',
                'steps' => [
                    '1. Ghi nhận đầy đủ thông tin',
                    '2. Xử lý ngay lập tức',
                    '3. Báo cáo KHẨN CẤP cho công ty',
                    '4. Liên hệ cơ quan chức năng nếu cần',
                    '5. Điều chỉnh lịch trình',
                    '6. Theo dõi và cập nhật thường xuyên'
                ],
                'contact' => 'Công ty điều hành tour, Cơ quan chức năng',
                'note' => 'Cần xử lý ngay'
            ],
            'nghiem_trong' => [
                'title' => 'Xử lý sự cố - Mức độ nghiêm trọng',
                'steps' => [
                    '1. NGAY LẬP TỨC: Ghi nhận đầy đủ thông tin',
                    '2. Xử lý khẩn cấp',
                    '3. Báo cáo KHẨN CẤP cho công ty và điều phối viên',
                    '4. Liên hệ cơ quan chức năng',
                    '5. Điều chỉnh toàn bộ lịch trình',
                    '6. Theo dõi 24/7'
                ],
                'contact' => 'KHẨN CẤP: Công ty điều hành tour, Điều phối viên, Cơ quan chức năng',
                'note' => 'Ưu tiên cao nhất'
            ]
        ];
        
        return $defaults[$mucDo] ?? $defaults['thap'];
    }
    
    /**
     * Format gợi ý thành HTML/text
     */
    public static function formatSuggestion($suggestion, $format = 'html')
    {
        if ($format === 'html') {
            $html = '<div class="suggestion-box">';
            $html .= '<h4>' . htmlspecialchars($suggestion['title']) . '</h4>';
            $html .= '<ol>';
            foreach ($suggestion['steps'] as $step) {
                $html .= '<li>' . htmlspecialchars($step) . '</li>';
            }
            $html .= '</ol>';
            $html .= '<p><strong>Liên hệ:</strong> ' . htmlspecialchars($suggestion['contact']) . '</p>';
            $html .= '<p><em>' . htmlspecialchars($suggestion['note']) . '</em></p>';
            $html .= '</div>';
            return $html;
        } else {
            $text = $suggestion['title'] . "\n\n";
            foreach ($suggestion['steps'] as $step) {
                $text .= $step . "\n";
            }
            $text .= "\nLiên hệ: " . $suggestion['contact'] . "\n";
            $text .= "Ghi chú: " . $suggestion['note'];
            return $text;
        }
    }
}

