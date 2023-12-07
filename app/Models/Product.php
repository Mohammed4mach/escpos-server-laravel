<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Printer as ModelsPrinter;
use App\Classes\ShopInfo;
use App\Classes\RefundPolicy;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class Product extends Model
{
    public $name;
    public $barcode;

    public function __construct($product)
    {
        $this->name    = $product['name'] ?? '';
        $this->barcode = $product['barcode'] ?? '000000000';
    }

    public function barcode()
    {
        $printer = ModelsPrinter::getBarcodePrinterName();
        $name    = $this->name;
        $barcode = $this->barcode;

        try {
            $connector = new WindowsPrintConnector($printer);
            $printer   = new Printer($connector);

            $text = "
            ^XA
            ^CF0,25
            ^CI28
            ^FO302,23^FD{$name}^FS
            ^BY2,3,67^FT305,140^BCN,,Y,N
            ^FH\^FD{$barcode}^FS
            ^XZ
            ";

            $printer->textRaw($text);
            $printer->feed();

            $printer->cut();
        }
        catch (\Exception $e) {
            throw new \Exception($e);
        }
    }
}
