<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\Receipt;
use App\Models\Stash;
use App\Models\transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Monolog\Handler\IFTTTHandler;
use Validator;

class Salecontroller extends Controller
{
    public function get_products()
    {
        if (Auth::check()) {
            $products = Products::select('id', 'product_name', 'category', 'qty', 'price', 'user_id')
                ->where('user_id', '=', Auth::user()->id)
                ->get();
            return response()->json(['status' => 200, 'products' => $products]);
        } else {
            echo "Forbidden request";
        }
    }

    public function stash_get()
    {
        if (Auth::check()) {
            $products = Stash::select('id', 'product_id', 'product_name', 'category', 'qty', 'price', 'user_id')
                ->where('user_id', '=', Auth::user()->id)
                ->get();
            $total_amount = Stash::where('user_id', Auth::user()->id)->sum('price');

            return response()->json(['status' => 200, 'products' => $products, 'amount' => $total_amount]);
        } else {
            echo "Forbidden request";
        }
    }

    public function add_to_cart(Request $request, $product_id)
    {
        if (request()->ajax()) {

            $current_stock = Products::select('qty')->where('id', '=', $request->product_id)->where('user_id', '=', Auth::user()->id)->get();

            if ($request->qty <= $current_stock[0]['qty']) {

                $validator = Validator::make($request->all(), [
                    'qty' => 'required|min:1|not_in:0|digits_between:0,9|numeric'
                ]);

                if (!$validator->passes()) {
                    return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
                } else {

                    $isAlready_in_stash = count((Stash::select('product_id', 'user_id')
                        ->where('product_id', '=', $product_id)
                        ->where('user_id', '=', Auth::user()->id)
                        ->get())) > 0 ? true : false;

                    $data = [
                        'product_id' => $request->product_id,
                        'product_name' => $request->product_name,
                        'category' => $request->category,
                        'qty' => $request->qty,
                        'price' => $request->price,
                        'user_id' => Auth::user()->id
                    ];

                    if (!$isAlready_in_stash) {
                        //add product
                        if (Auth::check()) {
                            Stash::create($data);
                            // $total_amount = Stash::where('user_id', Auth::user()->id)->sum('price');
                            return response()->json(['status' => 200, 'msg' => 'Product added!']);
                        } else {
                            echo "Forbidden request";
                        }
                    } else {

                        //update stash
                        $current_values = Stash::select('qty', 'price')->where('product_id', '=', $request->product_id)->where('user_id', '=', Auth::user()->id)->get();
                        $qty_val = ($current_values[0]['qty'] + $request->qty);

                        if ($current_values[0]['qty'] <= $current_stock[0]['qty'] && $qty_val <= $current_stock[0]['qty']) {
                            //get original price
                            if (Auth::check()) {
                                $new_qty = [
                                    'qty' => ($current_values[0]['qty'] + $request->qty),
                                    'price' => ($current_values[0]['price'] + $request->price)
                                ];

                                Stash::where('product_id', $request->product_id)
                                    ->where('user_id', Auth::user()->id)
                                    ->update($new_qty);

                                // $total_amount = Stash::where('user_id', Auth::user()->id)->sum('price');

                                return response()->json(['status' => 200, 'msg' => 'Stash updated!']);
                            } else {
                                echo "Forbidded request";
                            }
                            //end
                        } else {
                            return response()->json(['status' => 123, 'error_request' => 'Requested quantity cannot exceed to current stock. [' . $current_stock[0]['qty'] . ']']);
                        }
                    }
                }
            } else {
                return response()->json(['status' => 123, 'error_request' => 'Requested quantity cannot exceed to current stock. [' . $current_stock[0]['qty'] . ']']);
            }
        }
    }

    public function update_cart(Request $request, $id)
    {
        # code...
        if (request()->ajax()) {

            $original_price = Products::select('price')
                ->where('id', '=', $request->product_id)
                ->where('user_id', '=', Auth::user()->id)
                ->get();

            $validator = Validator::make($request->all(), [
                'qty' => 'required|min:1|not_in:0|digits_between:0,9|numeric'
            ]);
            if (!$validator->passes()) {
                return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
            } else {

                $new_price = ($request->qty * $original_price[0]['price']);

                $current_stock = Products::select('qty')->where('id', '=', $request->product_id)->where('user_id', '=', Auth::user()->id)->get();

                if ($request->qty <= $current_stock[0]['qty']) {
                    //get original price
                    if (Auth::check()) {
                        $new_qty = [
                            'qty' => $request->qty,
                            'price' => $new_price
                        ];

                        Stash::where('product_id', $request->product_id)
                            ->where('user_id', Auth::user()->id)
                            ->update($new_qty);

                        return response()->json(['status' => 200, 'msg' => 'Stash updated!']);
                    } else {
                        echo "Forbidded request";
                    }
                    //end
                } else {
                    return response()->json(['status' => 123, 'error_request' => 'Requested quantity cannot exceed to current stock. [' . $current_stock[0]['qty'] . ']']);
                }
            }
        }
    }

    public function remove_to_cart($id)
    {
        # code...
        if (Auth::check()) {
            Stash::where('user_id', '=', Auth::user()->id)
                ->where('product_id', '=', $id)
                ->delete();
            return response()->json(['status' => 200, 'msg' => 'Product has been removed from stash!']);
        } else {
            echo "Forbidded request";
        }
    }

    function receipt($transaction_id, $user_id)
    {
        $data = Receipt::select('product_id', 'product_name', 'category', 'qty', 'price', 'transaction_id', 'customer_amount', 'created_at')
            ->where('user_id', '=', $user_id)
            ->where('transaction_id', '=', $transaction_id)
            ->get();
        $store_info = [
            'store_name' => Auth::user()->store_name,
            'store_email' => Auth::user()->email,
            'contact' => Auth::user()->contact,
            'address' => Auth::user()->address
        ];
        $item_count = count($data);
        $total_amount = Receipt::where('user_id', '=', $user_id)
            ->where('transaction_id', '=', $transaction_id)->sum('price');
        return view('fragments.receipt', compact('data', 'store_info', 'total_amount', 'item_count'));
    }

    public function store_transaction($customer_amount)
    {
        if (request()->ajax()) {

            if (Auth::check()) {
                $stash = Stash::select('product_id', 'product_name', 'category', 'qty', 'price', 'user_id')
                    ->where('user_id', '=', Auth::user()->id)->get()->toArray();


                if (count($stash) > 0) {

                    $updated_qty = [];

                    for ($i = 0; $i < count($stash); $i++) {

                        $current_stock = Products::select('qty', 'product_name')
                            ->where('id', '=', $stash[$i]['product_id'])
                            ->where('user_id', '=', Auth::user()->id)
                            ->get()
                            ->toArray();
                        $deducted_qty = [
                            'id' => $stash[$i]['product_id'],
                            'qty' => (($current_stock[0]['qty']) - $stash[$i]['qty']),
                            'user_id' => Auth::user()->id
                        ];
                        array_push($updated_qty, $deducted_qty);
                        Products::where('id', '=', $stash[$i]['product_id'])
                            ->where('user_id', '=', Auth::user()->id)
                            ->update($updated_qty[$i]);
                    }

                    $amount = Stash::where('user_id', '=', Auth::user()->id)->sum('price');
                    $items = [];
                    for ($i = 0; $i < count($stash); $i++) {
                        array_push($items, $stash[$i]['product_name']);
                    }

                    $data = [
                        'items' => serialize($items),
                        'amount' => $amount,
                        'customer_amount' => $customer_amount,
                        'user_id' => Auth::user()->id
                    ];
                    $transaction = transactions::create($data);
                    for ($i = 0; $i < count($stash); $i++) {
                        # code...
                        $receipt_entry = [
                            'product_id' => $stash[$i]['product_id'],
                            'product_name' => $stash[$i]['product_name'],
                            'category' => $stash[$i]['category'],
                            'qty' => $stash[$i]['qty'],
                            'price' => $stash[$i]['price'],
                            'user_id' => Auth::user()->id,
                            'transaction_id' => $transaction->id,
                            'customer_amount' => $customer_amount
                        ];
                        Receipt::create($receipt_entry);
                    }
                    $receipt_param = ['transaction_id' => $transaction->id, 'user_id' => Auth::user()->id, 'customer_amount' => $customer_amount];
                    Stash::where('user_id', '=', Auth::user()->id)->delete();
                    return response()->json(['status' => 200, 'msg' => 'Transaction ended!', 'link' => route('receipt.print', $receipt_param)]);
                } else {
                    return response()->json(['status' => 0, 'msg' => 'no items in stash']);
                }
            } else {
                echo "Forbidden request";
            }
        }
    }
}
