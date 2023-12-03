<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $orderInfo = $request->all();
        $order     = new Order($orderInfo);

        try
        {
            $order->print();
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
