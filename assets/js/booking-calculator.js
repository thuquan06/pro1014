/**
 * Booking Calculator - Tự động tính giá tour theo số người
 * Created: 2024-12-03
 */

class BookingCalculator {
    constructor(config) {
        this.giaNguoiLon = parseFloat(config.giaNguoiLon) || 0;
        this.giaTreEm = parseFloat(config.giaTreEm) || 0;
        this.giaTreNho = parseFloat(config.giaTreNho) || 0;
        this.giaEmBe = parseFloat(config.giaEmBe) || 0;

        // Khuyến mãi
        this.coKhuyenMai = config.coKhuyenMai || false;
        this.phanTramGiam = parseFloat(config.phanTramGiam) || 0;

        // Elements
        this.inputNguoiLon = document.getElementById(config.inputNguoiLon || 'nguoilon');
        this.inputTreEm = document.getElementById(config.inputTreEm || 'treem');
        this.inputTreNho = document.getElementById(config.inputTreNho || 'trenho');
        this.inputEmBe = document.getElementById(config.inputEmBe || 'embe');
        this.outputTotal = document.getElementById(config.outputTotal || 'total-price');
        this.outputDetail = document.getElementById(config.outputDetail || 'price-detail');

        this.init();
    }

    init() {
        // Thêm event listeners
        if (this.inputNguoiLon) {
            this.inputNguoiLon.addEventListener('input', () => this.calculate());
            this.inputNguoiLon.addEventListener('change', () => this.calculate());
        }

        if (this.inputTreEm) {
            this.inputTreEm.addEventListener('input', () => this.calculate());
            this.inputTreEm.addEventListener('change', () => this.calculate());
        }

        if (this.inputTreNho) {
            this.inputTreNho.addEventListener('input', () => this.calculate());
            this.inputTreNho.addEventListener('change', () => this.calculate());
        }

        if (this.inputEmBe) {
            this.inputEmBe.addEventListener('input', () => this.calculate());
            this.inputEmBe.addEventListener('change', () => this.calculate());
        }

        // Tính lần đầu
        this.calculate();
    }

    calculate() {
        // Lấy số lượng
        const nguoiLon = parseInt(this.inputNguoiLon?.value || 0);
        const treEm = parseInt(this.inputTreEm?.value || 0);
        const treNho = parseInt(this.inputTreNho?.value || 0);
        const emBe = parseInt(this.inputEmBe?.value || 0);

        // Validate
        if (nguoiLon < 0 || treEm < 0 || treNho < 0 || emBe < 0) {
            return;
        }

        // Tính giá gốc
        const tongNguoiLon = this.giaNguoiLon * nguoiLon;
        const tongTreEm = this.giaTreEm * treEm;
        const tongTreNho = this.giaTreNho * treNho;
        const tongEmBe = this.giaEmBe * emBe;

        let tongTien = tongNguoiLon + tongTreEm + tongTreNho + tongEmBe;

        // Áp dụng khuyến mãi
        let tienGiam = 0;
        if (this.coKhuyenMai && this.phanTramGiam > 0) {
            tienGiam = tongTien * (this.phanTramGiam / 100);
            tongTien = tongTien - tienGiam;
        }

        // Hiển thị
        this.displayTotal(tongTien, tienGiam);
        this.displayDetail({
            nguoiLon,
            treEm,
            treNho,
            emBe,
            tongNguoiLon,
            tongTreEm,
            tongTreNho,
            tongEmBe,
            tienGiam,
            tongTien
        });
    }

    displayTotal(tongTien, tienGiam) {
        if (!this.outputTotal) return;

        let html = '';

        // Hiển thị giá gốc nếu có giảm
        if (tienGiam > 0) {
            const giaGoc = tongTien + tienGiam;
            html += '<div style="text-decoration: line-through; color: #999; font-size: 16px;">';
            html += this.formatPrice(giaGoc);
            html += '</div>';
        }

        // Hiển thị tổng tiền
        html += '<div style="color: #e74c3c; font-size: 28px; font-weight: bold;">';
        html += this.formatPrice(tongTien);
        html += '</div>';

        this.outputTotal.innerHTML = html;
    }

    displayDetail(data) {
        if (!this.outputDetail) return;

        let html = '<div style="margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 8px;">';
        html += '<h4 style="margin-bottom: 10px; color: #333;">Chi tiết giá:</h4>';

        // Người lớn
        if (data.nguoiLon > 0) {
            html += '<div style="display: flex; justify-content: space-between; margin-bottom: 8px;">';
            html += '<span>Người lớn (' + data.nguoiLon + ' x ' + this.formatPrice(this.giaNguoiLon) + ')</span>';
            html += '<span style="font-weight: 600;">' + this.formatPrice(data.tongNguoiLon) + '</span>';
            html += '</div>';
        }

        // Trẻ em
        if (data.treEm > 0) {
            html += '<div style="display: flex; justify-content: space-between; margin-bottom: 8px;">';
            html += '<span>Trẻ em (' + data.treEm + ' x ' + this.formatPrice(this.giaTreEm) + ')</span>';
            html += '<span style="font-weight: 600;">' + this.formatPrice(data.tongTreEm) + '</span>';
            html += '</div>';
        }

        // Trẻ nhỏ
        if (data.treNho > 0) {
            html += '<div style="display: flex; justify-content: space-between; margin-bottom: 8px;">';
            html += '<span>Trẻ nhỏ (' + data.treNho + ' x ' + this.formatPrice(this.giaTreNho) + ')</span>';
            html += '<span style="font-weight: 600;">' + this.formatPrice(data.tongTreNho) + '</span>';
            html += '</div>';
        }

        // Em bé
        if (data.emBe > 0) {
            html += '<div style="display: flex; justify-content: space-between; margin-bottom: 8px;">';
            html += '<span>Em bé (' + data.emBe + ' x ' + this.formatPrice(this.giaEmBe) + ')</span>';
            html += '<span style="font-weight: 600;">' + this.formatPrice(data.tongEmBe) + '</span>';
            html += '</div>';
        }

        // Giảm giá
        if (data.tienGiam > 0) {
            html += '<div style="display: flex; justify-content: space-between; margin-bottom: 8px; color: #27ae60;">';
            html += '<span>Giảm giá (' + this.phanTramGiam + '%)</span>';
            html += '<span style="font-weight: 600;">- ' + this.formatPrice(data.tienGiam) + '</span>';
            html += '</div>';
        }

        html += '<hr style="margin: 12px 0; border: none; border-top: 2px solid #ddd;">';

        // Tổng cộng
        html += '<div style="display: flex; justify-content: space-between; font-size: 18px;">';
        html += '<span style="font-weight: bold; color: #333;">Tổng cộng:</span>';
        html += '<span style="font-weight: bold; color: #e74c3c;">' + this.formatPrice(data.tongTien) + '</span>';
        html += '</div>';

        html += '</div>';

        this.outputDetail.innerHTML = html;
    }

    formatPrice(price) {
        return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        }).format(price);
    }

    // Cập nhật giá (khi thay đổi tour hoặc khuyến mãi)
    updatePrices(config) {
        this.giaNguoiLon = parseFloat(config.giaNguoiLon) || this.giaNguoiLon;
        this.giaTreEm = parseFloat(config.giaTreEm) || this.giaTreEm;
        this.giaTreNho = parseFloat(config.giaTreNho) || this.giaTreNho;
        this.giaEmBe = parseFloat(config.giaEmBe) || this.giaEmBe;
        this.coKhuyenMai = config.coKhuyenMai !== undefined ? config.coKhuyenMai : this.coKhuyenMai;
        this.phanTramGiam = parseFloat(config.phanTramGiam) || this.phanTramGiam;

        this.calculate();
    }
}

// Export cho sử dụng global
window.BookingCalculator = BookingCalculator;
