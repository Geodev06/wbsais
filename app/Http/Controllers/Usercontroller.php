<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Products;
use App\Models\Receipt;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use App\Models\Suppliers;
use App\Models\transactions;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Usercontroller extends Controller
{
    //

    public function register()
    {
        return view('main.register');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|regex:/^[a-zA-Z ]+$/u',
            'store_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        if (!$validator->passes()) {
            return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $data = [
                'name' => $request->input('name'),
                'store_name' => $request->input('store_name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
            ];
            User::create($data);

            return response()->json(['status' => 1, 'msg' => 'In order to continue please login and verify your email']);
        }
    }

    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];
        $remember = $request->input('remember');
        if (Auth::attempt($credentials, $remember)) {

            $user = Auth::user();
            $email_verified_at = $user->email_verified_at;

            if ($email_verified_at == null) {

                $user = User::where('email', '=', $credentials['email'])->first();
                $user->sendEmailVerificationNotification();

                return response()->json(['status' => -1, 'msg' => 'You have entered unverified email. please verify your email first to continue']);
            }
            return response()->json(['status' => 1, 'msg' => 'login sucess']);
        }
        return response()->json(['status' => 0, 'msg' => 'You have entered incorrect credentials please check your email and password!']);
    }

    public function logout()
    {
        if (Auth::check()) {
            Auth::logout();
            return response()->json(['status' => 1, 'msg' => 'user out']);
        }
    }

    public function load_product_fragment()
    {
        if (!Auth::check()) {
            return "NOT FOUND!";
        }

        $suppliers = Suppliers::select('supplier')
            ->where('user_id', '=', Auth::user()->id)
            ->get();
        $categories = Categories::select('category')
            ->where('user_id', '=', Auth::user()->id)
            ->get();
        $fragment = view('fragments.product_fragment', compact('suppliers', 'categories'))->render();
        return response()->json(['status' => 200, 'content' => $fragment]);
    }

    public function load_supplier_fragment()
    {
        if (!Auth::check()) {
            return "NOT FOUND!";
        }
        $fragment = view('fragments.supplier_fragment')->render();
        return response()->json(['status' => 200, 'content' => $fragment]);
    }

    public function dashboard_fragment()
    {
        if (!Auth::check()) {
            return "NOT FOUND!";
        }
        $inventory_count = Products::where('user_id', '=', Auth::user()->id)->count();
        $critical_items = Products::where('qty', '<', '10')->where('user_id', '=', Auth::user()->id)->count();
        $supplier_count = Suppliers::where('user_id', '=', Auth::user()->id)->count();
        $daily_revenue = transactions::where('user_id', '=', Auth::user()->id)
            ->whereDate('created_at', Carbon::today())
            ->sum('amount');

        $data = [
            'inventory' => $inventory_count,
            'supplier' => $supplier_count,
            'daily_rev' => $daily_revenue,
            'critical_items' => $critical_items,
        ];
        $recent_transactions = transactions::where('user_id', '=', Auth::user()->id)->latest()->take(10)->get();

        $fragment = view('fragments.dashboard_fragment', compact('data', 'recent_transactions'))->render();
        return response()->json(['status' => 200, 'content' => $fragment]);
    }

    public function load_category_fragment()
    {
        if (!Auth::check()) {
            return "NOT FOUND!";
        }
        $fragment = view('fragments.category_fragment')->render();
        return response()->json(['status' => 200, 'content' => $fragment]);
    }

    public function load_sale_fragment()
    {
        if (!Auth::check()) {
            return "NOT FOUND!";
        }

        $fragment = view('fragments.sale_fragment')->render();
        return response()->json(['status' => 200, 'content' => $fragment]);
    }

    public function load_setting_fragment()
    {
        if (!Auth::check()) {
            return "NOT FOUND!";
        }

        $credentials = [
            'name' => Auth::user()->name,
            'store_name' => Auth::user()->store_name,
            'email' => Auth::user()->email,
            'contact' => Auth::user()->contact,
            'address' => Auth::user()->address,
            'lat' => Auth::user()->lat,
            'long' => Auth::user()->long,
        ];
        $fragment = view('fragments.setting_fragment', compact('credentials'))->render();
        return response()->json(['status' => 200, 'content' => $fragment]);
    }

    public function load_report_fragment()
    {
        if (!Auth::check()) {
            return "NOT FOUND!";
        }

        $fragment = view('fragments.report_fragment')->render();
        return response()->json(['status' => 200, 'content' => $fragment]);
    }

    //reports
    public function report(Request $request)
    {
        if (Auth::check()) {
            $transactions = transactions::select(DB::raw('sum(amount) as amount'), DB::raw('date(created_at) as date'))
                ->whereDate('created_at', '>=', $request->from)
                ->whereDate('created_at', '<=', $request->to)
                ->where('user_id', '=', Auth::user()->id)
                ->groupBy('date')
                ->orderBy('date', 'desc')
                ->get();
            return response()->json(['status' => 200, 'transactions' => $transactions]);
        } else {
            echo "Forbidden request";
        }
    }

    public function get_top_products()
    {
        # code...
        if (Auth::check()) {
            $top_products = DB::select('select product_id, product_name, SUM(qty) as total from receipts where user_id =' . Auth::user()->id . ' group by product_id, product_name LIMIT 10');
            return response()->json(['status' => 200, 'details' => $top_products]);
        } else {
            echo "Forbidden request";
        }
    }

    public function get_revenue(Request $request)
    {
        # code...
        if (Auth::check()) {
            if (intval($request->days) > 0) {
                $revenue = DB::select('select sum(amount) as daily_revenue, date(created_at) as date from transactions where user_id =' . Auth::user()->id . ' and date(created_at) >= (date(now()) - interval ' . intval($request->days) . ' day) group by date');
                return response()->json(['status' => 200, 'details' => $revenue]);
            } else {
                $revenue = DB::select('select sum(amount) as daily_revenue, date(created_at) as date from transactions where user_id =' . Auth::user()->id . ' group by date');
                return response()->json(['status' => 200, 'details' => $revenue]);
            }
        } else {
            echo "Forbidden request";
        }
    }

    public function info_update(Request $request)
    {
        if (Auth::check()) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|regex:/^[a-zA-Z ]+$/u',
                'contact' => 'min:11|max:11',
            ]);

            if (!$validator->passes()) {
                return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
            } else {
                $data = [
                    'name' => $request->input('name'),
                    'contact' => $request->input('contact'),
                ];
                User::where('id', '=', Auth::user()->id)->update($data);
                return response()->json(['status' => 200, 'msg' => 'Changes has been saved!']);
            }
        } else {
            echo "Forbidden request";
        }
    }

    public function password_update(Request $request)
    {
        if (Auth::check()) {
            $validator = Validator::make($request->all(), [
                'old_password' => 'required',
                'password' => 'required|min:8|confirmed|different:old_password',
            ]);
            if (!$validator->passes()) {
                return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
            } else {

                if (!Hash::check($request->input('old_password'), Auth::user()->password)) {

                    return response()->json(['status' => 2, 'error' => 'Incorrect old password']);
                } else {
                    $data = [
                        'password' => Hash::make($request->input('password')),
                    ];
                    User::where('id', '=', Auth::user()->id)->update($data);
                    return response()->json(['status' => 200, 'msg' => 'Changes has been saved!']);
                }
            }
        } else {
            echo "Forbidden request";
        }
    }

    public function storeinfo_update(Request $request)
    {
        if (Auth::check()) {
            $validator = Validator::make($request->all(), [
                'storename' => 'required',
            ]);
            if (!$validator->passes()) {
                return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
            } else {
                $data = [
                    'store_name' => $request->input('storename'),
                    'address' => $request->input('address'),
                    'lat' => $request->input('lat'),
                    'long' => $request->input('long'),
                ];

                User::where('id', '=', Auth::user()->id)->update($data);
                return response()->json(['status' => 200, 'msg' => 'Changes has been saved!']);
            }
        } else {
            echo "Forbidden request";
        }
    }

    public function destroy_user()
    {
        if (Auth::check()) {
            Products::where('user_id', '=', Auth::user()->id)->delete();
            Suppliers::where('user_id', '=', Auth::user()->id)->delete();
            Receipt::where('user_id', '=', Auth::user()->id)->delete();
            transactions::where('user_id', '=', Auth::user()->id)->delete();
            Categories::where('user_id', '=', Auth::user()->id)->delete();
            User::where('id', '=', Auth::user()->id)->delete();
            return response()->json(['status' => 200, 'msg' => 'Account has been deleted', 'link' => route('wbsais.login')]);
        } else {
            echo "Forbidden request";
        }
    }
}
