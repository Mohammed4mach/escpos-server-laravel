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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
