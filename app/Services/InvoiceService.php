<?php

namespace App\Services;

use App\Models\Archive;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\Result;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class InvoiceService
{
    public function generateInvoiceNumber(): string
    {
        $year   = now()->format('Y');
        $last   = Invoice::whereYear('created_at', $year)->count() + 1;
        return 'INV-' . $year . '-' . str_pad($last, 4, '0', STR_PAD_LEFT);
    }

    public function createInvoice(Patient $patient, array $testIds, int $receptionistId): Invoice
    {
        // حساب المجموع
        $tests = \App\Models\Test::whereIn('id', $testIds)->get();
        $total = $tests->sum('price');

        // إنشاء الفاتورة
        $invoice = Invoice::create([
            'invoice_number'  => $this->generateInvoiceNumber(),
            'patient_id'      => $patient->id,
            'receptionist_id' => $receptionistId,
            'total_amount'    => $total,
            'status'          => 'pending',
        ]);

        // ربط التحاليل
        foreach ($tests as $test) {
            $invoice->tests()->attach($test->id, [
                'price_at_time' => $test->price,
            ]);
        }

        // إنشاء نتائج فارغة لكل تحليل
        foreach ($tests as $test) {
            Result::create([
                'invoice_id'   => $invoice->id,
                'test_id'      => $test->id,
                'biologist_id' => 1,
                'value'        => null,
                'status'       => 'pending',
            ]);
        }

        // QR Code
        $qrPath = $this->generateQrCode($invoice);
        $invoice->update(['qr_code_path' => $qrPath]);

        // Archive
        Archive::create([
            'model_type'    => 'Invoice',
            'model_id'      => $invoice->id,
            'action'        => 'created',
            'performed_by'  => $receptionistId,
            'data_snapshot' => $invoice->load('tests', 'patient')->toArray(),
        ]);

        return $invoice;
    }

    private function generateQrCode(Invoice $invoice): string
{
    $url      = route('patient.login') . '?ref=' . $invoice->invoice_number;
    $filename = 'qrcodes/' . $invoice->invoice_number . '.svg';
    $fullPath = storage_path('app/public/' . $filename);

    if (!file_exists(dirname($fullPath))) {
        mkdir(dirname($fullPath), 0755, true);
    }

    // SVG format — لا يحتاج imagick
    QrCode::format('svg')
          ->size(200)
          ->errorCorrection('H')
          ->generate($url, $fullPath);

    return $filename;
}
}