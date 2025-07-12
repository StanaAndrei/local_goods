<?php

namespace App\Http\Controllers;

use App\Models\Acquisition;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AcquisitionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:0.01',
        ]);

        try {
            DB::beginTransaction();

            $product = Product::with('seller')->findOrFail($request->product_id);
            $buyer = Auth::user();

            // Check if user is a buyer
            if (! $buyer->isBuyer()) {
                return back()->with('error', 'Only buyers can purchase products.');
            }

            // Check if buyer is trying to buy their own product
            if ($buyer->id === $product->seller_id) {
                return back()->with('error', 'You cannot buy your own product.');
            }

            // Check if requested quantity is available
            if ($request->quantity > $product->quantity) {
                return back()->with('error', 'Requested quantity exceeds available stock.');
            }

            // Calculate total cost using rule of 3
            $totalCost = round(($product->price * $request->quantity), 2);

            // Check if buyer has enough funds
            if (! $buyer->hasEnoughBalance($totalCost)) {
                return back()->with('error', 'Insufficient funds. Your balance: €'.number_format($buyer->balance, 2).', Required: €'.number_format($totalCost, 2));
            }

            // Create the acquisition
            $acquisition = Acquisition::create([
                'buyer_id' => $buyer->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'cost' => $totalCost,
            ]);

            // Update product quantity
            $product->decrement('quantity', $request->quantity);

            // Transfer money: deduct from buyer, add to seller
            $buyer->deductBalance($totalCost);
            $product->seller->addBalance($totalCost);

            DB::commit();

            return back()->with('success', 'Product purchased successfully! Total cost: €'.number_format($totalCost, 2).'. Your remaining balance: €'.number_format($buyer->fresh()->balance, 2));

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'An error occurred while processing your purchase. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Acquisition $acquisition)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Acquisition $acquisition)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Acquisition $acquisition)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Acquisition $acquisition)
    {
        //
    }
}
