<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
	public $product;

    public function __construct($order)
    {
        $this->id                       = $order['id'];
        $this->barcode                  = $order['barcode'];
        $this->totalPrice               = $order['total_price'];
        $this->totalPriceBeforeDiscount = $order['total_price_before_discount'];
        $this->discount                 = $order['discount'];
        $this->paidPrice                = $order['paid_price'];
        $this->remainPrice              = $order['remain_price'];
        $this->buyerName                = $order['buyer_name'];
        $this->shipperName              = $order['shipper_name'];
        $this->orderDate                = $order['order_date'];
        $this->orderTime                = $order['order_time'];
        $this->totalCount               = $order['total_count'];
        $this->totalModelCount          = $order['total_model_count'];
        $this->products                 = $order['products'];
    }

    public function print()
    {
        try {
            $connector = new WindowsPrintConnector("Receipt Printer");

            $printer = new Printer($connector);
            $printer->text("Hello World!\n");
            $printer->cut();

            $printer->close();
        } catch (\Exception $e) {
            throw new \Exception($e);
        }
    }
}

