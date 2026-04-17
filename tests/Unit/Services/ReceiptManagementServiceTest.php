<?php

use App\Models\Order;
use App\Services\ReceiptManagementService;
use Barryvdh\DomPDF\PDF as DomPdf;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

uses(TestCase::class);

test('generateReceiptFromOrder returns rendered pdf output', function () {
    $order = new Order();

    $pdf = mock(DomPdf::class);
    $pdf->shouldReceive('output')
        ->once()
        ->andReturn('pdf-binary-content');

    Pdf::shouldReceive('loadView')
        ->once()
        ->with('receipt', ['order' => $order])
        ->andReturn($pdf);

    $result = ReceiptManagementService::generateReceiptFromOrder($order);

    expect($result)->toBe('pdf-binary-content');
});

test('saveReceiptToFile stores generated receipt content in expected path', function () {
    $order = new Order();
    $order->id = 42;

    $pdf = mock(DomPdf::class);
    $pdf->shouldReceive('output')
        ->once()
        ->andReturn('pdf-content');

    Pdf::shouldReceive('loadView')
        ->once()
        ->with('receipt', ['order' => $order])
        ->andReturn($pdf);

    Storage::shouldReceive('put')
        ->once()
        ->with('receipts/receipt_42.pdf', 'pdf-content')
        ->andReturn(true);

    $result = ReceiptManagementService::saveReceiptToFile($order);

    expect($result)->toBeTrue();
});

test('getReceiptFilePath returns resolved storage path for the order receipt', function () {
    $order = new Order();
    $order->id = 42;

    Storage::shouldReceive('path')
        ->once()
        ->with('receipts/receipt_42.pdf')
        ->andReturn('/tmp/receipts/receipt_42.pdf');

    $result = ReceiptManagementService::getReceiptFilePath($order);

    expect($result)->toBe('/tmp/receipts/receipt_42.pdf');
});
