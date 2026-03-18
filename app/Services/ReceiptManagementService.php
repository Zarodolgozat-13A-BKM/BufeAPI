<?php

namespace App\Services;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class ReceiptManagementService
{
    public static function generateReceiptFromOrder(Order $order)
    {
        $pdf = Pdf::loadView('receipt', [
            'order' => $order
        ]);
        return $pdf->output();
    }

    public static function saveReceiptToFile(Order $order)
    {
        $pdfContent = self::generateReceiptFromOrder($order);
        $filePath = Storage::put('receipts/receipt_' . $order->id . '.pdf', $pdfContent);
        return $filePath;
    }

    public static function getReceiptFilePath(Order $order)
    {
        return Storage::path('receipts/receipt_' . $order->id . '.pdf');
    }
}
