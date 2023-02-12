<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductExport implements FromCollection, WithHeadings, WithMapping
{
	use Exportable;

	private $array;

    public function __construct(array $array)
    {
        $this->array = $array;
    }

    public function collection()
    {
        return collect($this->array);
    }

    public function headings(): array {
        return [
            'ID sản phẩm',
            'ID sản phẩm chính',
            'Mã sản phẩm', //không xử lý
            'Ngày đăng', //không xử lý
            'Ngày cập nhật', //không xử lý
            'Title',
            'Subtitle',
            'Slug', //không xử lý
            'List thể loại',
            'Thể loại ưu tiên',
            'Hình ảnh đại diện', //không xử lý
            'Xuất xứ',
            'Link Admin',
            'Public Link',
            'Thương hiệu', //không xử lý
            'Số lượng tồn',
            'Giới hạn mua',
            'Giá gốc',
            'Giá khuyến mãi',
            'Ngày bắt đầu giảm giá',
            'Ngày kết thúc giảm giá',
            'Text của quà tặng',
            'Mã quà tặng sản phẩm',
            'Tên combo', //không xử lý
            'Mã combo',
            'Bộ sưu tập Collagen',
            'Hot Deal',
            'Chống Covid',
            'Black Friday',
            'Đồng giá 1k 9k 99k',
            'Bán chạy',
            'Flash Sale',
            'Flash Sale 22h',
            'Sản phẩm gợi ý',
            'Khoá chỉnh sửa',
            'Trạng thái',
        ];
    }

    public function map($row): array {
        return [
            $row['id'],
            $row['product_id'],
            $row['sku'],
            $row['created_at'],
            $row['updated_at'],
            $row['title'],
            $row['subtitle'],
            $row['slug'],
            $row['list_categories'],
            $row['category_main'],
            $row['thumbnail'],
            $row['origin'],
            $row['admin_link'],
            $row['public_link'],
            $row['brand'],
            $row['stock'],
            $row['buy_limit'],
            $row['price_origin'],
            $row['price_promotion'],
            $row['start_event'],
            $row['end_event'],
            $row['gift_text'],
            $row['product_gift_sku'],
            $row['combo_name'],
            $row['combo_sku'],
            $row['collection_collagen'],
            $row['collection_hot_deal'],
            $row['collection_covid'],
            $row['collection_black_friday'],
            $row['category_1k_9k_99k'],
            $row['category_best_seller'],
            $row['flash_sale'],
            $row['flash_sale_22h'],
            $row['suggest_product'],
            $row['enable_edit'],
            $row['status'],
        ];
    }
}
