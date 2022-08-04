<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Products;
use App\Models\Suppliers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
                'product_name' => $request->input('product_name'),
                'supplier' => $request->input('supplier'),
                'category' => $request->input('category'),
                'expiry' => $request->input('expiry'),
                'qty' => $request->input('qty'),
                'price' => $request->input('price'),
                'user_id' => Auth::user()->id
            ];

            if (Auth::check()) {
                Products::create($data);
                return response()->json(['status' => 1, 'msg' => 'Product Successfully added!']);
            } else {
                echo "Forbidden request";
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
                'product_name' => $request->input('product_name'),
                'supplier' => $request->input('supplier'),
                'category' => $request->input('category'),
                'expiry' => $request->input('expiry'),
                'qty' => $request->input('qty'),
                'price' => $request->input('price'),
            ];

            if (Auth::check()) {
                Products::where('id', $request->input('id'))
                    ->where('user_id', Auth::user()->id)
                    ->update($data);

                return response()->json(['status' => 1, 'msg' => 'Product has been updated successfully!']);
            } else {
                echo "Forbidden request";
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
                ->where('user_id', '=', Auth::user()->id)
                ->get();
            return response()->json(['status' => 200, 'products' => $products]);
        } else {
            echo "Forbidden request";
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
            Products::where('user_id', '=', Auth::user()->id)
                ->where('id', '=', $id)
                ->delete();
            return response()->json(['status' => 200, 'msg' => 'Product has been deleted successfully!']);
        } else {
            echo "Forbidded request";
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
                'user_id' => Auth::user()->id
            ];
            if (Auth::check()) {
                Suppliers::create($data);
                return response()->json(['status' => 1, 'msg' => 'Supplier has been added!']);
            } else {
                echo "Forbidden request";
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
                    ->where('user_id', Auth::user()->id)
                    ->update($data);
                return response()->json(['status' => 200, 'msg' => 'Supplier has been updated successfully!']);
            } else {
                echo "Forbidden request";
            }
        }
    }

    public function get_supplier()
    {
        if (Auth::check()) {
            $suppliers = Suppliers::select('id', 'supplier', 'address', 'contact')
                ->where('user_id', '=', Auth::user()->id)
                ->get();
            return response()->json(['status' => 200, 'suppliers' => $suppliers]);
        } else {
            echo "Forbidden request";
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
            Suppliers::where('user_id', '=', Auth::user()->id)
                ->where('id', '=', $id)
                ->delete();
            return response()->json(['status' => 200, 'msg' => 'Supplier has been deleted successfully!']);
        } else {
            echo "Forbidded request";
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
                'user_id' => Auth::user()->id
            ];
            if (Auth::check()) {
                Categories::create($data);
                return response()->json(['status' => 1, 'msg' => 'Category has been successfully added!']);
            } else {
                echo "Forbidden request";
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
                ->where('user_id', '=', Auth::user()->id)
                ->get();
            return response()->json(['status' => 200, 'categories' => $categories]);
        } else {
            echo "Forbidden request";
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
                    ->where('user_id', Auth::user()->id)
                    ->update($data);
                return response()->json(['status' => 1, 'msg' => 'Category has been updated successfully!']);
            } else {
                echo "Forbidden request";
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
            Categories::where('user_id', '=', Auth::user()->id)
                ->where('id', '=', $id)
                ->delete();
            return response()->json(['status' => 200, 'msg' => 'Category has been deleted successfully!']);
        } else {
            echo "Forbidded request";
        }
    }
}
