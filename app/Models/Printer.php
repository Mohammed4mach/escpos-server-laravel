<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Printer extends Model
{
    static public function validName($name)
    {
        $matched = preg_match('/[^\w\-_]/', $name);

        $invalid =
            $matched ||
            $matched === false ||
            strval($name) === '';

        if($invalid)
            return false;

        return true;
    }

    static public function setReceiptPrinterName($name)
    {
        setGlobal('PRECEIPT_NAME', $name);
    }

    static public function getReceiptPrinterName() : string
    {
        return getGlobal('PRECEIPT_NAME');
    }

    static public function setBarcodePrinterName($name)
    {
        setGlobal('PBARCODE_NAME', $name);
    }

    static public function getBarcodePrinterName() : string
    {
        return getGlobal('PBARCODE_NAME');
    }
}
