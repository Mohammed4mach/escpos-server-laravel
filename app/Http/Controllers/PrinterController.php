<?php

namespace App\Http\Controllers;

use App\Models\Printer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PrinterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $receiptPrinter = Printer::getReceiptPrinterName();
        $barcodePrinter = Printer::getBarcodePrinterName();

        return response()->json([
            'receipt' => $receiptPrinter,
            'barcode' => $barcodePrinter,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $type)
    {
        $printer = '';

        switch ($type)
        {
            case 'receipt':
                $printer = Printer::getReceiptPrinterName();
                break;
            case 'barcode':
                $printer = Printer::getBarcodePrinterName();
                break;
        }

        if($printer == '')
            return response('', Response::HTTP_NOT_FOUND);

        return response()->json([
            'type' => $type,
            'name' => $printer
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(string $type, Request $request)
    {
        $printer = $request->get('name');

        $valid = Printer::validName($printer);

        if(!$valid)
            return response()->json(
                ['message' => 'Invalid printer name'],
                Response::HTTP_BAD_REQUEST
            );

        switch ($type)
        {
            case 'receipt':
                $success = Printer::setReceiptPrinterName($printer);
                break;
            case 'barcode':
                $success = Printer::setBarcodePrinterName($printer);
                break;
        }


        return response()->json([
            'type' => $type,
            'name' => $printer
        ]);
    }
}
