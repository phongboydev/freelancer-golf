<?php
namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomerExport implements FromQuery, WithHeadings, WithMapping
{
	use Exportable;

    public function __construct(string $from, string $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function headings(): array {
        return [
            'Họ và tên',
            'Ngày sinh',
            'Email',    
            "Số điện thoại",
            "Tỉnh/Thành phố",
            "Quận/Huyện",
            "Phường/Xã",
            "Địa chỉ",
            "Ngày đăng ký"
        ];
    }

    public function map($row): array {
        return [
            $row->name,
            $row->birthday,
            $row->email,
            $row->phone,
            $row->province,
            $row->district,
            $row->ward,
            $row->address,
            $row->created_at
        ];
    }

    public function query()
    {	
        return User::query()->whereBetween('created_at', [$this->from, $this->to])->orderBy('created_at', 'DESC')->select('name', 'birthday', 'email', 'phone', 'province', 'district', 'ward', 'address', 'created_at');
    }
}
