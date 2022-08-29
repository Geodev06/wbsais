<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\logs;
use App\Models\Products;
use App\Models\Suppliers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Phpml\Math\Product;
use Validator;

class Productcontroller extends Controller
{

    /**
     * --------------------
     * product store
     *---------------------
     */

    public function store_product(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'product_name' => 'required|unique:products',
                'category' => 'required',
                'qty' => 'required|integer|min:0|not_in:0|digits_between:0,9|numeric',
                'price' => 'required|regex:/^\d+(\.\d{1,2})?$/|min:1|not_in:0'
            ]
        );

        if (!$validator->passes()) {
            return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $data = [
                'product_name' => ucwords(strtolower($request->input('product_name'))),
                'supplier' => $request->input('supplier'),
                'category' => $request->input('category'),
                'expiry' => $request->input('expiry'),
                'qty' => $request->input('qty'),
                'price' => $request->input('price'),
                'user_id' => Auth::user()->id,
                'inventory_id' => Auth::user()->inventory_id
            ];

            if (Auth::check()) {
                Products::create($data);
                $log_Data = [
                    'description' => 'New product has been added! [' . $data['product_name'] . ']',
                    'inventory_id' => Auth::user()->inventory_id,
                    'email' => Auth::user()->email,
                    'action' => 'c'
                ];
                logs::create($log_Data);
                return response()->json(['status' => 1, 'msg' => 'Product Successfully added!']);
            } else {
                return Redirect::route('forbidden');
            }
        }
    }


    /**
     * --------------------------
     * product update
     * --------------------------
     */

    public function update_product(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'id' => 'required',
                'product_name' => 'required',
                'category' => 'required',
                'qty' => 'required|integer|min:0|not_in:0|digits_between:0,9|numeric',
                'price' => 'required|regex:/^\d+(\.\d{1,2})?$/|min:1|not_in:0'
            ]
        );

        if (!$validator->passes()) {
            return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $data = [
                'product_name' => ucwords(strtolower($request->input('product_name'))),
                'supplier' => $request->input('supplier'),
                'category' => $request->input('category'),
                'expiry' => $request->input('expiry'),
                'qty' => $request->input('qty'),
                'price' => $request->input('price'),
            ];

            if (Auth::check()) {
                Products::where('id', $request->input('id'))
                    ->where('inventory_id', Auth::user()->inventory_id)
                    ->update($data);

                $log_Data = [
                    'description' => 'product has been updated! [' . $data['product_name'] . ']',
                    'inventory_id' => Auth::user()->inventory_id,
                    'email' => Auth::user()->email,
                    'action' => 'u'
                ];
                logs::create($log_Data);

                return response()->json(['status' => 1, 'msg' => 'Product has been updated successfully!']);
            } else {
                return Redirect::route('forbidden');
            }
        }
    }

    /**
     * --------------------------
     * get product
     * --------------------------
     */
    public function get_product()
    {
        if (Auth::check()) {
            $products = Products::select('id', 'product_name', 'supplier', 'category', 'expiry', 'qty', 'price')
                ->where('inventory_id', '=', Auth::user()->inventory_id)
                ->get();
            return response()->json(['status' => 200, 'products' => $products]);
        } else {
            return Redirect::route('forbidden');
        }
    }

    /**
     * --------------------------
     * destroy product
     * --------------------------
     */
    public function destroy_product($id)
    {
        if (Auth::check()) {
            $item_Deleted = Products::select('product_name')
                ->where('id', $id)
                ->where('inventory_id', Auth::user()->inventory_id)->get()->toArray();

            $log_Data = [
                'description' => 'product has been deleted! [' . $item_Deleted[0]['product_name'] . ']',
                'inventory_id' => Auth::user()->inventory_id,
                'email' => Auth::user()->email,
                'action' => 'd'
            ];
            logs::create($log_Data);

            Products::where('inventory_id', '=', Auth::user()->inventory_id)
                ->where('id', '=', $id)
                ->delete();


            return response()->json(['status' => 200, 'msg' => 'Product has been deleted successfully!']);
        } else {
            return Redirect::route('forbidden');
        }
    }


    public function store_supplier(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'supplier' => 'required',
                'address' => 'required',
                'contact' => 'required|alpha_dash|min:11|max:11',
            ]
        );

        if (!$validator->passes()) {
            return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $data = [
                'supplier' => $request->input('supplier'),
                'address' => $request->input('address'),
                'contact' => $request->input('contact'),
                'user_id' => Auth::user()->id,
                'inventory_id' => Auth::user()->inventory_id
            ];
            if (Auth::check()) {
                Suppliers::create($data);
                $log_Data = [
                    'description' => 'New Supplier has been added! [' . $data['supplier'] . ']',
                    'inventory_id' => Auth::user()->inventory_id,
                    'email' => Auth::user()->email,
                    'action' => 'c'
                ];
                logs::create($log_Data);
                return response()->json(['status' => 1, 'msg' => 'Supplier has been added!']);
            } else {
                return Redirect::route('forbidden');
            }
        }
    }

    /**
     * --------------------------
     * update supplier
     * --------------------------
     */
    public function update_supplier(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'supplier' => 'required',
                'address' => 'required',
                'contact' => 'required|alpha_dash|min:11|max:11',
            ]
        );

        if (!$validator->passes()) {
            return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $data = [
                'supplier' => $request->input('supplier'),
                'address' => $request->input('address'),
                'contact' => $request->input('contact'),
            ];

            if (Auth::check()) {
                Suppliers::where('id', $id)
                    ->where('inventory_id', Auth::user()->inventory_id)
                    ->update($data);

                $log_Data = [
                    'description' => 'Supplier has been updated! [' . $data['supplier'] . ']',
                    'inventory_id' => Auth::user()->inventory_id,
                    'email' => Auth::user()->email,
                    'action' => 'u'
                ];
                logs::create($log_Data);
                return response()->json(['status' => 200, 'msg' => 'Supplier has been updated successfully!']);
            } else {
                return Redirect::route('forbidden');
            }
        }
    }

    public function get_supplier()
    {
        if (Auth::check()) {
            $suppliers = Suppliers::select('id', 'supplier', 'address', 'contact')
                ->where('inventory_id', '=', Auth::user()->inventory_id)
                ->get();
            return response()->json(['status' => 200, 'suppliers' => $suppliers]);
        } else {
            return Redirect::route('forbidden');
        }
    }
    /**
     * --------------------------
     * destroy supplier
     * --------------------------
     */
    public function destroy_supplier($id)
    {
        if (Auth::check()) {

            $item_Deleted = Suppliers::select('supplier')
                ->where('id', $id)
                ->where('inventory_id', Auth::user()->inventory_id)->get()->toArray();

            $log_Data = [
                'description' => 'Supplier has been deleted! [' . $item_Deleted[0]['supplier'] . ']',
                'inventory_id' => Auth::user()->inventory_id,
                'email' => Auth::user()->email,
                'action' => 'd'
            ];
            logs::create($log_Data);
            Suppliers::where('inventory_id', '=', Auth::user()->inventory_id)
                ->where('id', '=', $id)
                ->delete();
            return response()->json(['status' => 200, 'msg' => 'Supplier has been deleted successfully!']);
        } else {
            return Redirect::route('forbidden');
        }
    }

    /**
     * --------------------------
     * store category
     * --------------------------
     */
    public function store_category(Request $request)
    {
        $validator = Validator::make($request->all(), ['category' => 'required']);

        if (!$validator->passes()) {
            return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $data = [
                'category' => $request->input('category'),
                'user_id' => Auth::user()->id,
                'inventory_id' => Auth::user()->inventory_id
            ];
            if (Auth::check()) {
                Categories::create($data);
                $log_Data = [
                    'description' => 'New category  has been added! [' . $data['category'] . ']',
                    'inventory_id' => Auth::user()->inventory_id,
                    'email' => Auth::user()->email,
                    'action' => 'c'
                ];
                logs::create($log_Data);
                return response()->json(['status' => 1, 'msg' => 'Category has been successfully added!']);
            } else {
                return Redirect::route('forbidden');
            }
        }
    }

    /**
     * --------------------------
     * get category
     * --------------------------
     */
    public function get_category()
    {
        if (Auth::check()) {
            $categories = Categories::select('id', 'category', 'user_id')
                ->where('inventory_id', '=', Auth::user()->inventory_id)
                ->get();
            return response()->json(['status' => 200, 'categories' => $categories]);
        } else {
            return Redirect::route('forbidden');
        }
    }
    /**
     * --------------------------
     * update category
     * --------------------------
     */
    public function update_category(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'category' => 'required',
            ]
        );

        if (!$validator->passes()) {
            return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $data = [
                'category' => $request->input('category'),
            ];

            if (Auth::check()) {
                Categories::where('id', $id)
                    ->where('inventory_id', Auth::user()->inventory_id)
                    ->update($data);
                $log_Data = [
                    'description' => 'Category  has been updated! [' . $data['category'] . ']',
                    'inventory_id' => Auth::user()->inventory_id,
                    'email' => Auth::user()->email,
                    'action' => 'u'
                ];
                logs::create($log_Data);
                return response()->json(['status' => 1, 'msg' => 'Category has been updated successfully!']);
            } else {
                return Redirect::route('forbidden');
            }
        }
    }
    /**
     * --------------------------
     * destroy category
     * --------------------------
     */
    public function destroy_category($id)
    {
        if (Auth::check()) {

            $item_Deleted = Categories::select('category')
                ->where('id', $id)
                ->where('inventory_id', Auth::user()->inventory_id)
                ->get()
                ->toArray();

            $log_Data = [
                'description' => 'Category has been deleted! [' . $item_Deleted[0]['category'] . ']',
                'inventory_id' => Auth::user()->inventory_id,
                'email' => Auth::user()->email,
                'action' => 'd'
            ];
            logs::create($log_Data);
            Categories::where('inventory_id', '=', Auth::user()->inventory_id)
                ->where('id', '=', $id)
                ->delete();
            return response()->json(['status' => 200, 'msg' => 'Category has been deleted successfully!']);
        } else {
            return Redirect::route('forbidden');
        }
    }
}
