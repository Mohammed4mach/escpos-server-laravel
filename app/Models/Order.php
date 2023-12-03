<?php

namespace App\Models;

use App\Models\Printer as ModelsPrinter;
use App\Classes\ShopInfo;
use App\Classes\RefundPolicy;
use Illuminate\Database\Eloquent\Model;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class Order extends Model
{

    public $id;
	public $barcode;
	public $totalPrice;
	public $totalPriceBeforeDiscount;
	public $discount;
	public $paidPrice;
	public $remainPrice;
	public $buyerName;
	public $shipperName;
	public $orderDate;
	public $orderTime;
	public $totalCount;
	public $totalModelCount;
	public $products;

    public function __construct($order)
    {
        $this->id                       = $order['id'] ?? '';
        $this->barcode                  = $order['barcode'] ?? '5346';
        $this->totalPrice               = $order['total_price'] ?? '';
        $this->totalPriceBeforeDiscount = $order['total_price_before_discount'] ?? '';
        $this->discount                 = $order['discount'] ?? '';
        $this->paidPrice                = $order['paid_price'] ?? '';
        $this->remainPrice              = $order['remain_price'] ?? '';
        $this->buyerName                = $order['buyer_name'] ?? '';
        $this->shipperName              = $order['shipper_name'] ?? '';
        $this->orderDate                = $order['order_date'] ?? '';
        $this->orderTime                = $order['order_time'] ?? '';
        $this->totalCount               = $order['total_count'] ?? '';
        $this->totalModelCount          = $order['total_model_count'] ?? '';
        $this->products                 = $order['products'] ?? [ ];
    }

    public function print()
    {
        $shop = ShopInfo::name;
        $tel  = ShopInfo::tel;


        $printer = ModelsPrinter::getReceiptPrinterName();

        try {
            $connector = new WindowsPrintConnector($printer);
            $printer   = new Printer($connector);
            $logo      = EscposImage::load('logo-icon-black.png', false);

            /* Print top logo */
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->graphics($logo);

            /* Name of shop */
            $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $printer->text("{$shop}\n");
            $printer->selectPrintMode();
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("Tel: {$tel}\n");
            $printer->feed();

            /* Title of receipt */
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setEmphasis(true);
            $printer->text("SALES INVOICE - فاتورة بيع\n");
            $printer->setEmphasis(false);

            $printer->setJustification(Printer::JUSTIFY_RIGHT);
            $printer->text("التاريخ | Date                {$this->orderDate}    الوقت | Time  {$this->orderTime}\n");
            $printer->text("العميل | Client               {$this->buyerName}\n");
            $printer->text("البائع | Seller               {$this->sellerName}\n");
            $printer->text("شركة الشحن | Shipping Company {$this->shipperName}\n");

            /* Items */
            $modelw = 15;
            $qtyw   = 12;
            $pricew = 13;
            $totalw = 15;

            $lblModel  = str_pad('الصنف | Model', $modelw, ' ', STR_PAD_BOTH);
            $lblQty    = str_pad('الكمية | Qty', $qtyw, ' ', STR_PAD_BOTH);
            $lblIPrice = str_pad('السعر | Price ', $pricew, ' ', STR_PAD_BOTH);
            $lblTPrice = str_pad('الإجمالي | Total', $totalw, ' ', STR_PAD_BOTH);

            $printer->text("$lblModel $lblQty $lblIPrice $lblTPrice");

            foreach($this->products as $product)
            {
                $name   = str_pad($product->name ?? '', $modelw, ' ', STR_PAD_BOTH);
                $qty    = str_pad($product->qty ?? '', $qtyw, ' ', STR_PAD_BOTH);
                $iprice = str_pad($product->item_price ?? '', $pricew, ' ', STR_PAD_BOTH);
                $tprice = str_pad($product->totalPrice ?? '', $totalw, ' ', STR_PAD_BOTH);

                $printer->text("$name $qty $iprice $tprice\n");
            }

            $printer->setEmphasis(true);
            $printer->text("الإجمالي | Total     {$this->totalPriceBeforeDiscount}");
            $printer->setEmphasis(false);
            $printer->feed();

            $lblDiscount  = str_pad('الحسم | Discount', 31, ' ', STR_PAD_LEFT);
            $lblNet       = str_pad('الصافي | Net', 31, ' ', STR_PAD_LEFT);
            $lblPaid      = str_pad('المدفوع | Paid', 31, ' ', STR_PAD_LEFT);
            $lblRemaining = str_pad('المتبقي | Remaining', 31, ' ', STR_PAD_LEFT);
            $lblModel     = str_pad('الأصناف المباعة | Model(s) Count', 31, ' ', STR_PAD_LEFT);

            $printer->text("$lblDiscount {$this->discount}");
            $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $printer->text("$lblNet {$this->totalPrice}");
            $printer->selectPrintMode();

            $printer->text("$lblPaid {$this->paidPrice}");
            $printer->text("$lblRemaining {$this->remainPrice}");
            $printer->text("$lblModel {$this->totalModelCount}");

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->barcode($this->barcode, Printer::BARCODE_CODE39);
            $printer->feed();

            /* Refund Policy */
            [ 'ar' => $termsAr, 'en' => $termsEn ] = RefundPolicy::getTerms();
            $printer->setTextSize(1, 1);

            // AR
            $printer->setJustification(Printer::JUSTIFY_RIGHT);
            foreach($termsAr['terms'] as $term)
            {
                $printer->text("- $term\n");
            }

            // EN
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            foreach($termsEn['terms'] as $term)
            {
                $printer->text("- $term\n");
            }

            /* Contact Info */
            $printer->feed(2);
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("Thank you for shopping at One Way\n");
            $printer->text("For trading hours, please visit https://oneway.fashio\n");
            $printer->selectPrintMode(
                Printer::MODE_DOUBLE_WIDTH |
                Printer::MODE_DOUBLE_HEIGHT |
                Printer::MODE_EMPHASIZED
            );
            $printer->qrCode('https://oneway.fashion');
            $printer->selectPrintMode();
            $printer->feed();

            /* Footer */
            $date = new \DateTime;
            $date = $date->format('d-m-Y h:i A');

            $printer->feed(2);
            $printer->text("$date\n");

            /* Cut the receipt and open the cash drawer */
            $printer->cut();
            $printer->pulse();

            $printer->close();
        } catch (\Exception $e) {
            throw new \Exception($e);
        }
    }
}

