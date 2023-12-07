<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $prodInfo = $request->all();
        $prod     = new Product($prodInfo);

        try
        {
            $prod->barcode();
        }
        catch(\Exception $e)
        {
            $message = "Failed to print. Verify that the printer is shared with the provided printer share name via the agent.";
            $body = [
                'message' => $message
            ];

            return response()
            ->json($body, Response::HTTP_SERVICE_UNAVAILABLE);
        }

        return response('', Response::HTTP_CREATED);
    }

    public function multiIndex(Request $request)
    {
        $prodInfo = $request->all();
        $prod     = new Product($prodInfo);
	$times    = $prodInfo['times'] ?? 1;

        try
        {
            $prod->multiBarcode($times);
        }
        catch(\Exception $e)
        {
            $message = "Failed to print. Verify that the printer is shared with the provided printer share name via the agent.";
            $body = [
                'message' => $message
            ];

            return response()
            ->json($body, Response::HTTP_SERVICE_UNAVAILABLE);
        }

        return response('', Response::HTTP_CREATED);
    }
}
