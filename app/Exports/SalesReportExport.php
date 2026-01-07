<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize; // Tambahan agar kolom otomatis lebar
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SalesReportExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    use Exportable;

    public function __construct(
        protected string $dateFrom,
        protected string $dateTo
    ) {}

    /**
     * 1. Query Data
     */
    public function query()
    {
        return Order::query()
            ->with(['user', 'items'])
            ->whereDate('created_at', '>=', $this->dateFrom)
            ->whereDate('created_at', '<=', $this->dateTo)
            ->where('payment_status', 'paid')
            ->orderBy('created_at', 'asc');
    }

    /**
     * 2. Header Kolom Excel
     */
    public function headings(): array
    {
        return [
            'No. Order',
            'Tanggal Transaksi',
            'Nama Customer',
            'Email',
            'Jumlah Item',
            'Total Belanja (Rp)',
            'Status'
        ];
    }

    /**
     * 3. Mapping Data per Baris
     */
    public function map($order): array
    {
        return [
            $order->order_number,
            $order->created_at->format('d/m/Y H:i'),
            // Gunakan null coalescing (??) agar tidak error jika user tidak ditemukan
            $order->user->name ?? $order->shipping_name ?? 'Pelanggan Terhapus',
            $order->user->email ?? '-',
            $order->items->sum('quantity'),
            // Total amount ini sudah harga diskon karena kita sudah perbaiki di OrderService tadi
            $order->total_amount, 
            ucfirst($order->status),
        ];
    }

    /**
     * 4. Styling
     */
    public function styles(Worksheet $sheet)
    {
        // Menambahkan border tipis untuk seluruh data (opsional tapi bagus)
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}