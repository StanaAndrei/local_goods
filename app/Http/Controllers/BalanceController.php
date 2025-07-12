<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Account;
use Stripe\Transfer;
use Stripe\Checkout\Session;



class BalanceController extends Controller
{
    /**
     * Show the deposit form.
     */
    public function showDepositForm()
    {
        return view('pages.balance.deposit');
    }

    /**
     * Process the deposit request by creating a Stripe Checkout session.
     */
    public function processDeposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:5',
        ]);

        $user = Auth::user();
        $amountString = $request->input('amount');
        $amountAsFloat = floatval($amountString);
        $finalAmountInCents = (int)round($amountAsFloat * 100);

        try {
            $user->createOrGetStripeCustomer();
            Stripe::setApiKey(config('services.stripe.secret'));

            $session = Session::create([
                'customer' => $user->stripe_id,
                'mode' => 'payment',
                'success_url' => route('balance.deposit.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('balance.deposit.cancel'),
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Balance Deposit',
                        ],
                        'unit_amount' => $finalAmountInCents,
                    ],
                    'quantity' => 1,
                ]],
                // --- CHANGE 1: ADDING METADATA HERE FOR EASY RETRIEVAL ---
                'metadata' => [
                    'user_id' => $user->id,
                    'deposit_amount' => $amountAsFloat,
                ],
                'payment_intent_data' => [
                    'metadata' => [
                        'user_id' => $user->id,
                        'laravel_user_email' => $user->email,
                        'deposit_amount' => $amountAsFloat,
                    ]
                ],
            ]);

            return redirect($session->url);

        } catch (\Exception $e) {
            return back()->with('error', 'Could not process payment. Stripe error: ' . $e->getMessage());
        }
    }

    /**
     * Handle successful deposit callback from Stripe.
     */
    public function depositSuccess(Request $request)
    {
        $user = Auth::user();
        $sessionId = $request->get('session_id');

        if (!$sessionId) {
            return redirect()->route('balance.deposit')->with('error', 'Checkout session not found.');
        }

        try {
            Stripe::setApiKey(config('services.stripe.secret'));
            $session = Session::retrieve($sessionId);

            if ($session->payment_status == 'paid') {
                if (session()->get('last_processed_session') !== $sessionId) {
                    
                    // --- CHANGE 2: THE FIX IS HERE ---
                    // Metadata from Stripe always comes back as a string.
                    $amountStringFromStripe = $session->metadata->deposit_amount;

                    // We must cast it back to a numeric type (float) before using it.
                    $amount = floatval($amountStringFromStripe);

                    // Now, add the numeric balance. This will work.
                    $user->addBalance($amount);
                    
                    session(['last_processed_session' => $sessionId]);
                }
                return redirect()->route('dashboard')->with('success', 'Your balance has been updated successfully!');
            }
        } catch (\Exception $e) {
            return redirect()->route('balance.deposit')->with('error', 'An error occurred: ' . $e->getMessage());
        }

        return redirect()->route('balance.deposit')->with('error', 'Payment was not successful.');
    }

    /**
     * Handle cancelled deposit.
     */
    public function depositCancel()
    {
        return redirect()->route('balance.deposit')->with('error', 'The payment process was cancelled.');
    }


    /**
     * Show the withdrawal form.
     */
    public function showWithdrawForm()
    {
        $user = Auth::user();
        // Only sellers can withdraw
        if (!$user->isSeller()) {
            abort(403, 'Only sellers can withdraw funds.');
        }
        return view('pages.balance.withdraw');
    }

    /**
     * Redirect user to Stripe to connect their account.
     */
    public function connectStripe()
    {
        $user = Auth::user();

        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            if (!$user->stripe_connect_id) {
                $account = Account::create([
                    'type' => 'express',
                    'email' => $user->email,
                    'country' => 'RO', // Correctly set to your platform's region
                    'capabilities' => [
                        'card_payments' => ['requested' => true],
                        'transfers' => ['requested' => true],
                    ],
                ]);
                $user->stripe_connect_id = $account->id;
                $user->save();
            }

            // --- THE FIX IS HERE ---
            // The createLoginLink method is simpler and expects a 'redirect_url'
            // to send the user back to after they log in or out of Stripe.
            // We will send them back to the withdrawal page.
            $accountLink = Account::createLoginLink(
                $user->stripe_connect_id,
                [
                    'redirect_url' => route('balance.withdraw'),
                ]
            );

            return redirect($accountLink->url);

        } catch (\Exception $e) {
            return back()->with('error', 'Could not connect to Stripe. Error: ' . $e->getMessage());
        }
    }
    /**
     * Process the withdrawal request.
     */
    public function processWithdrawal(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $user = Auth::user();
        $amount = $request->input('amount');

        if (!$user->isSeller() || !$user->stripe_connect_id) {
            return back()->with('error', 'You must connect a Stripe account before you can withdraw.');
        }

        if (!$user->hasEnoughBalance($amount)) {
            return back()->with('error', 'You do not have enough balance to withdraw that amount.');
        }

        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            // Create a transfer from your platform's balance to the seller's Stripe account
            Transfer::create([
                'amount' => $amount * 100, // amount in cents
                'currency' => 'usd',
                'destination' => $user->stripe_connect_id,
                'transfer_group' => 'WITHDRAWAL_' . $user->id,
            ]);

            // If transfer is successful, deduct from internal balance
            $user->deductBalance($amount);

            return redirect()->route('dashboard')->with('success', 'Withdrawal successful! Funds are on their way to your Stripe account.');

        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred during withdrawal: ' . $e->getMessage());
        }
    }
}