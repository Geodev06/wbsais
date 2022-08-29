<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Expense;
use App\Models\logs;
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
use Phpml\Association\Apriori;
use Phpml\Math\Product;

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
                'inventory_id' => Hash::make(rand(1000, 9999)),
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
            return Redirect::route('forbidden');
        } else {
            $suppliers = Suppliers::select('supplier')
                ->where('inventory_id', '=', Auth::user()->inventory_id)
                ->get();
            $categories = Categories::select('category')
                ->where('inventory_id', '=', Auth::user()->inventory_id)
                ->get();
            $fragment = view('fragments.product_fragment', compact('suppliers', 'categories'))->render();
            return response()->json(['status' => 200, 'content' => $fragment]);
        }
    }

    public function load_supplier_fragment()
    {
        if (!Auth::check()) {
            return Redirect::route('forbidden');
        } else {
            $fragment = view('fragments.supplier_fragment')->render();
            return response()->json(['status' => 200, 'content' => $fragment]);
        }
    }

    public function dashboard_fragment()
    {
        if (!Auth::check()) {
            return Redirect::route('forbidden');
        }
        $inventory_count = Products::where('inventory_id', '=', Auth::user()->inventory_id)
            ->count();

        $critical_items = Products::where('qty', '<', '10')
            ->where('inventory_id', '=', Auth::user()->inventory_id)
            ->count();
        $critical_items_list = Products::where('qty', '<', '10')
            ->where('inventory_id', '=', Auth::user()->inventory_id)
            ->get();

        $supplier_count = Suppliers::where('inventory_id', '=', Auth::user()->inventory_id)
            ->count();

        $daily_revenue = transactions::where('inventory_id', '=', Auth::user()->inventory_id)
            ->whereDate('created_at', Carbon::today())
            ->sum('amount');

        $daily_revenue_yesterday = transactions::where('inventory_id', '=', Auth::user()->inventory_id)
            ->whereDate('created_at', Carbon::today()->addDays(-1))
            ->sum('amount');

        $expiry_items_no = Products::whereDate('expiry', '<=', Carbon::now()->addDays(5))
            ->where('inventory_id', '=', Auth::user()->inventory_id)
            ->count();

        $expiry_items = Products::whereDate('expiry', '<=', Carbon::now()->addDays(5))
            ->where('inventory_id', '=', Auth::user()->inventory_id)->get();

        $stock_value = DB::select('select sum((qty * price)) as total from products where inventory_id ="' . Auth::user()->inventory_id . '" ');

        $stock_value = array_map(function ($value) {
            return (array)$value;
        }, $stock_value);

        $no_of_transactions_yesterday = transactions::whereDate('created_at', Carbon::today()->addDays(-1))
            ->where('inventory_id', Auth::user()->inventory_id)->count();

        $no_of_transactions_today = transactions::whereDate('created_at', Carbon::today())
            ->where('inventory_id', Auth::user()->inventory_id)->count();

        $change_pct = $no_of_transactions_yesterday <= 0 ? 0.0 : ($no_of_transactions_today - $no_of_transactions_yesterday) / $no_of_transactions_yesterday;


        $data = [
            'inventory' => $inventory_count,
            'supplier' => $supplier_count,
            'daily_rev' => number_format($daily_revenue, 2),
            'yesterday_rev' => $daily_revenue_yesterday,
            'critical_items' => $critical_items,
            'critical_items_list' => $critical_items_list,
            'exp_items' => $expiry_items,
            'exp_items_no' => $expiry_items_no,
            'stock_value' => number_format($stock_value[0]['total'], 2),
            'transaction_y' => $no_of_transactions_yesterday,
            'transaction_t' => $no_of_transactions_today,
            'change_pct' => number_format((float)($change_pct * 100), 2, '.', '')
        ];
        $recent_transactions = transactions::select('transaction_id', 'amount', 'no_of_items', 'created_at')->where('inventory_id', '=', Auth::user()->inventory_id)
            ->latest()
            ->take(10)
            ->get();

        $fragment = view('fragments.dashboard_fragment', compact('data', 'recent_transactions'))->render();
        return response()->json(['status' => 200, 'content' => $fragment]);
    }

    public function load_category_fragment()
    {
        if (!Auth::check()) {
            return Redirect::route('forbidden');
        }
        $fragment = view('fragments.category_fragment')->render();
        return response()->json(['status' => 200, 'content' => $fragment]);
    }

    public function load_sale_fragment()
    {
        if (!Auth::check()) {
            return Redirect::route('forbidden');
        }

        $fragment = view('fragments.sale_fragment')->render();
        return response()->json(['status' => 200, 'content' => $fragment]);
    }

    public function load_expenses_fragment()
    {
        if (!Auth::check()) {
            return Redirect::route('forbidden');
        }

        $fragment = view('fragments.expenses_fragment')->render();
        return response()->json(['status' => 200, 'content' => $fragment]);
    }

    public function load_setting_fragment()
    {
        if (!Auth::check()) {
            return Redirect::route('forbidden');
        }

        $credentials = [
            'name' => Auth::user()->name,
            'store_name' => Auth::user()->store_name,
            'email' => Auth::user()->email,
            'contact' => Auth::user()->contact,
            'address' => Auth::user()->address,
            'lat' => Auth::user()->lat,
            'long' => Auth::user()->long,
            'inventory_id' => Auth::user()->inventory_id
        ];
        $fragment = view('fragments.setting_fragment', compact('credentials'))->render();

        return response()->json(['status' => 200, 'content' => $fragment]);
    }

    public function load_report_fragment()
    {
        if (!Auth::check()) {
            return Redirect::route('forbidden');
        }

        $inventory_count = Products::where('inventory_id', '=', Auth::user()->inventory_id)
            ->count();

        $inventory_count_qty = Products::where('inventory_id', '=', Auth::user()->inventory_id)
            ->sum('qty');

        $critical_items = Products::where('qty', '<', '10')
            ->where('inventory_id', '=', Auth::user()->inventory_id)
            ->count();

        $supplier_count = Suppliers::where('inventory_id', '=', Auth::user()->inventory_id)
            ->count();

        $expiry_items_no = Products::whereDate('expiry', '<=', Carbon::now()->addDays(5))
            ->where('inventory_id', '=', Auth::user()->inventory_id)
            ->count();

        $connected_acc = (User::where('inventory_id', '=', Auth::user()->inventory_id)->count() - 1);

        $data = [
            'store_name' => Auth::user()->store_name,
            'inventory' => $inventory_count,
            'inventory_qty' => $inventory_count_qty,
            'supplier' => $supplier_count,
            'critical_items' => $critical_items,
            'exp_items_no' => $expiry_items_no,
            'connected' => $connected_acc
        ];
        $fragment = view('fragments.report_fragment', compact('data'))->render();
        return response()->json(['status' => 200, 'content' => $fragment]);
    }

    public function load_analysis_fragment()
    {
        if (!Auth::check()) {
            return Redirect::route('forbidden');
        }
        $fragment = view('fragments.analysis_fragment')->render();
        return response()->json(['status' => 200, 'content' => $fragment]);
    }

    public function load_logs_fragment()
    {
        if (!Auth::check()) {
            return Redirect::route('forbidden');
        }

        $data = logs::select('description', 'email', 'created_at', 'action')->where('inventory_id', Auth::user()->inventory_id)
            ->orderBy('created_at', 'desc')
            ->get();

        logs::whereDate('created_at', '>=', Carbon::now()->addDays(14))
            ->where('inventory_id', '=', Auth::user()->inventory_id)->delete();

        $fragment = view('fragments.logs_fragment', compact('data'))->render();
        return response()->json(['status' => 200, 'content' => $fragment]);
    }
    //reports
    public function report(Request $request)
    {
        if (Auth::check()) {
            $transactions = transactions::select(DB::raw('sum(amount) as amount'), DB::raw('date(created_at) as date'), DB::raw('sum(no_of_items) as totalqty'))
                ->whereDate('created_at', '>=', $request->from)
                ->whereDate('created_at', '<=', $request->to)
                ->where('inventory_id', '=', Auth::user()->inventory_id)
                ->groupBy('date')
                ->orderBy('date', 'desc')
                ->get();

            $expenses = Expense::select(DB::raw('sum(amount) as amount'))
                ->whereDate('created_at', '>=', $request->from)
                ->whereDate('created_at', '<=', $request->to)
                ->where('inventory_id', '=', Auth::user()->inventory_id)
                ->get();

            return response()->json(['status' => 200, 'transactions' => $transactions, 'expenses' => $expenses]);
        } else {
            return Redirect::route('forbidden');
        }
    }

    public function get_top_products()
    {
        # code...
        if (Auth::check()) {
            $top_products = DB::select('select product_name, SUM(qty) as total from receipts where inventory_id ="' . Auth::user()->inventory_id . '" group by product_name, product_name LIMIT 10');
            return response()->json(['status' => 200, 'details' => $top_products]);
        } else {
            return Redirect::route('forbidden');
        }
    }

    public function get_revenue(Request $request)
    {
        # code...
        if (Auth::check()) {
            if (intval($request->days) > 0) {
                $revenue = DB::select('select sum(amount) as daily_revenue, date(created_at) as date from transactions where inventory_id ="' . Auth::user()->inventory_id . '" and date(created_at) >= (date(now()) - interval ' . intval($request->days) . ' day) group by date');
                return response()->json(['status' => 200, 'details' => $revenue]);
            } else {
                $revenue = DB::select('select sum(amount) as daily_revenue, date(created_at) as date from transactions where inventory_id ="' . Auth::user()->inventory_id . '" group by date');
                return response()->json(['status' => 200, 'details' => $revenue]);
            }
        } else {
            return Redirect::route('forbidden');
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

                $log_Data = [
                    'description' => 'Information setting has been updated!',
                    'inventory_id' => Auth::user()->inventory_id,
                    'email' => Auth::user()->email,
                    'action' => 'u'
                ];
                logs::create($log_Data);
                return response()->json(['status' => 200, 'msg' => 'Changes has been saved!']);
            }
        } else {
            return Redirect::route('forbidden');
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
                    $log_Data = [
                        'description' => 'Password has been updated!',
                        'inventory_id' => Auth::user()->inventory_id,
                        'email' => Auth::user()->email,
                        'action' => 'u'
                    ];
                    logs::create($log_Data);
                    return response()->json(['status' => 200, 'msg' => 'Changes has been saved!']);
                }
            }
        } else {
            return Redirect::route('forbidden');
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
                $log_Data = [
                    'description' => 'Store information setting has been updated!',
                    'inventory_id' => Auth::user()->inventory_id,
                    'email' => Auth::user()->email,
                    'action' => 'u'
                ];
                logs::create($log_Data);
                return response()->json(['status' => 200, 'msg' => 'Changes has been saved!']);
            }
        } else {
            return Redirect::route('forbidden');
        }
    }

    public function destroy_user()
    {
        if (Auth::check()) {
            User::where('id', '=', Auth::user()->id)->delete();
            return response()->json(['status' => 200, 'msg' => 'Account has been deleted', 'link' => route('wbsais.login')]);
        } else {
            return Redirect::route('forbidden');
        }
    }

    public function bind_inventory(Request $request)
    {
        # code...
        if (Auth::check()) {
            if (request()->ajax()) {

                $validator = Validator::make($request->all(), [
                    'inventory_id' => 'required',
                ]);
                if (!$validator->passes()) {
                    return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
                } else {

                    $iskeyFound = count(User::where('inventory_id', '=', $request->inventory_id)->get()) > 0 ? true : false;

                    if (!$iskeyFound) {
                        return response()->json(['status' => 400, 'msg' => 'Invalid inventory key']);
                    } else {
                        $data = [
                            'inventory_id' => $request->inventory_id,
                        ];
                        User::where('id', '=', Auth::user()->id)->update($data);

                        $log_Data = [
                            'description' => 'Another account has been connected.',
                            'inventory_id' => Auth::user()->inventory_id,
                            'email' => Auth::user()->email,
                            'action' => 'u'
                        ];
                        logs::create($log_Data);
                        return response()->json(['status' => 200, 'msg' => 'Inventory has been binded successfully!']);
                    }
                }
            }
        } else {
            return Redirect::route('forbidden');
        }
    }

    public function generate_key()
    {
        # code...
        if (Auth::check()) {
            if (request()->ajax()) {

                $data = [
                    'inventory_id' => Hash::make(rand(1000, 9999)),
                ];

                User::where('id', '=', Auth::user()->id)->update($data);
                $log_Data = [
                    'description' => 'New Inventory key has been generated!.',
                    'inventory_id' => Auth::user()->inventory_id,
                    'email' => Auth::user()->email,
                    'action' => 'u'
                ];
                logs::create($log_Data);
                return response()->json(['status' => 200, 'msg' => 'Key has been generated successfully!']);
            }
        } else {
            return Redirect::route('forbidden');
        }
    }

    public function analysis()
    {

        if (Auth::check()) {
            $items = transactions::select('items')->where('inventory_id', Auth::user()->inventory_id)
                ->get()
                ->toArray();

            $samples = [];
            for ($i = 0; $i < count($items); $i++) {
                array_push($samples, unserialize($items[$i]['items']));
            }

            $Apriori = new Apriori($support = 0.2, $confidence = 0.75);
            $Apriori->train($samples, []);
            return response()->json(['status' => 200, 'details' => $Apriori->getRules()]);
        } else {
            return Redirect::route('forbidden');
        }
    }

    public function open_recent($transaction_id)
    {
        if (Auth::check()) {
            $data = Receipt::select('product_id', 'product_name', 'category', 'qty', 'price', 'transaction_id', 'customer_amount', 'created_at')
                ->where('inventory_id', '=', Auth::user()->inventory_id)
                ->where('transaction_id', '=', $transaction_id)
                ->get();
            $store_info = [
                'store_name' => Auth::user()->store_name,
                'store_email' => Auth::user()->email,
                'contact' => Auth::user()->contact,
                'address' => Auth::user()->address
            ];
            $item_count = count($data);
            $total_amount = Receipt::where('inventory_id', '=', Auth::user()->inventory_id)
                ->where('transaction_id', '=', $transaction_id)->sum('price');
            return view('fragments.receipt', compact('data', 'store_info', 'total_amount', 'item_count'));
        } else {
            return Redirect::route('forbidden');
        }
    }

    public function store_expense(Request $request)
    {
        # code...
        if (Auth::check()) {
            if (request()->ajax()) {
                $validator = Validator::make($request->all(), [
                    'description' => 'required',
                    'amount' => 'required|regex:/^\d+(\.\d{1,2})?$/|min:1|not_in:0'
                ]);

                if (!$validator->passes()) {
                    return response()->json(['status' => 401, 'error' => $validator->errors()->toArray()]);
                } else {
                    $data = [
                        'type' => $request->input('type'),
                        'description' => $request->input('description'),
                        'amount' => $request->input('amount'),
                        'inventory_id' => Auth::user()->inventory_id,
                        'user_id' => Auth::user()->id,
                    ];
                    Expense::create($data);

                    $log_Data = [
                        'description' => 'Expenses has been added',
                        'inventory_id' => Auth::user()->inventory_id,
                        'email' => Auth::user()->email,
                        'action' => 'c'
                    ];
                    logs::create($log_Data);
                    return response()->json(['status' => 200, 'msg' => 'Expense has been registered!.']);
                }
            }
        } else {
            return Redirect::route('forbidden');
        }
    }

    public function get_expenses()
    {
        if (Auth::check()) {
            if (request()->ajax()) {

                $expenses = Expense::select('id', 'type', 'description', 'amount', DB::raw('DATE_FORMAT(created_at, "%m-%d-%y") as date'))
                    ->where('inventory_id', Auth::user()->inventory_id)
                    ->get()
                    ->toArray();
                return response()->json(['status' => 201, 'data' => $expenses]);
            }
        } else {
            return Redirect::route('forbidden');
        }
    }

    public function update_expenses(Request $request, $id)
    {
        # code...
        if (Auth::check()) {

            if (request()->ajax()) {
                $validator = Validator::make($request->all(), [

                    'description' => 'required',
                    'amount' => 'required|regex:/^\d+(\.\d{1,2})?$/|min:1|not_in:0'
                ]);

                if (!$validator->passes()) {
                    return response()->json(['status' => 401, 'error' => $validator->errors()->toArray()]);
                } else {

                    $data = [
                        'type' => $request->input('type'),
                        'description' => $request->input('description'),
                        'amount' => $request->input('amount')
                    ];

                    Expense::where('id', '=', $id)
                        ->where('inventory_id', Auth::user()->inventory_id)
                        ->update($data);

                    $log_Data = [
                        'description' => 'Expenses has been edited!',
                        'inventory_id' => Auth::user()->inventory_id,
                        'email' => Auth::user()->email,
                        'action' => 'u'
                    ];
                    logs::create($log_Data);
                    return response()->json(['status' => 200, 'msg' => 'A expenses record has been editted!.']);
                }
            }
        } else {
            return Redirect::route('forbidden');
        }
    }
    public function destroy_expenses($id)
    {
        # code...
        if (Auth::check()) {

            if (request()->ajax()) {

                Expense::where('id', '=', $id)->where('inventory_id', Auth::user()->inventory_id)->delete();

                $log_Data = [
                    'description' => 'a expenses record has been deleted',
                    'inventory_id' => Auth::user()->inventory_id,
                    'email' => Auth::user()->email,
                    'action' => 'd'
                ];
                logs::create($log_Data);
                return response()->json(['status' => 201, 'msg' => 'Record has been deleted!']);
            }
        } else {
            return Redirect::route('forbidden');
        }
    }

    public function get_expenses_summary(Request $request)
    {
        # code...
        if (Auth::check()) {
            if (request()->ajax()) {
                $expenses_points = Expense::select(DB::raw('sum(amount) as amount'), DB::raw('date(created_at) as date'))
                    ->whereDate('created_at', '>=', $request->from)
                    ->whereDate('created_at', '<=', $request->to)
                    ->where('inventory_id', '=', Auth::user()->inventory_id)
                    ->groupBy('date')
                    ->orderBy('date', 'desc')
                    ->get();
                $revenue_points = transactions::select(DB::raw('sum(amount) as amount'), DB::raw('date(created_at) as date'))
                    ->whereDate('created_at', '>=', $request->from)
                    ->whereDate('created_at', '<=', $request->to)
                    ->where('inventory_id', '=', Auth::user()->inventory_id)
                    ->groupBy('date')
                    ->orderBy('date', 'desc')
                    ->get();
                return response()->json(['status' => 200, 'expenses_datapoints' => $expenses_points, 'revenue_datapoints' => $revenue_points]);
            }
        } else {
            return Redirect::route('forbidden');
        }
    }

    public function inventory_composition()
    {
        # code...
        if (Auth::check()) {
            if (request()->ajax()) {
                $products = DB::select('select category, SUM(qty) as qty from products where inventory_id ="' . Auth::user()->inventory_id . '" group by category');
                return response()->json(['status' => 200, 'details' => $products]);
            }
        } else {
            return Redirect::route('forbidden');
        }
    }

    public function products_qty_data()
    {
        if (Auth::check()) {
            if (request()->ajax()) {
                $top_10_sold_qty = DB::table('products')
                    ->join('receipts', 'receipts.product_id', '=', 'products.id')
                    ->select('products.product_name', DB::raw('products.qty as rem_qty'), DB::raw('sum(receipts.qty) as total'))
                    ->whereDate('receipts.created_at', '>=', Carbon::now()->addDays(-30))
                    ->where('products.inventory_id', Auth::user()->inventory_id)
                    ->groupBy('products.product_name', 'products.qty')
                    ->take(10)
                    ->get();

                return response()->json(['status' => 200, 'details' => $top_10_sold_qty]);
            }
        } else {
            return Redirect::route('forbidden');
        }
    }

    public function getsales_overview($category)
    {
        if (Auth::check()) {
            if (request()->ajax()) {
                switch ($category) {
                    case 7:
                        //weekly
                        $response_data = DB::select('select sum(amount) as total, date_format(created_at, "%M-%d-%Y") as date from transactions where inventory_id ="' . Auth::user()->inventory_id . '" and date(created_at) >= (date(now()) - interval 8 day) group by date order by created_at asc');
                        return response()->json(['status' => 200, 'data' => $response_data]);
                        break;
                    case 30:
                        //monthly
                        $response_data = DB::select('select sum(amount) as total, date_format(created_at, "%M-%Y") as date from transactions where inventory_id ="' . Auth::user()->inventory_id . '" and date(created_at) >= (date(now()) - interval 365 day) group by date order by created_at asc');
                        return response()->json(['status' => 200, 'data' => $response_data]);
                        break;
                    case 365:
                        //weekly
                        $response_data = DB::select('select sum(amount) as total, date_format(created_at, "%Y") as date from transactions where inventory_id ="' . Auth::user()->inventory_id . '" and date(created_at) >= (date(now()) - interval 10000 day) group by date');
                        return response()->json(['status' => 200, 'data' => $response_data]);
                        break;
                    default:
                        # code...
                        return response()->json(['status' => 200, 'data' => []]);
                        break;
                }
            }
        } else {
            return Redirect::route('forbidden');
        }
    }

    public function transactions_chart_data()
    {
        # code...
        if (Auth::check()) {
            if (request()->ajax()) {

                $data  = transactions::select('transaction_id', Db::raw('sum(amount) as total'))
                    ->where('inventory_id', '=', Auth::user()->inventory_id)
                    ->whereDate('created_at', '=', Carbon::today())
                    ->groupBy('created_at', 'transaction_id')
                    ->get();
                return response()->json(['status' => 200, 'details' => $data]);
            }
        } else {
            return Redirect::route('forbidden');
        }
    }
}
