<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectRespons;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Products;
use App\Models\Orders;
use App\Models\Inventory;

class OrdersController extends Controller
{
    protected $viewData = [];
    public function __construct()
    {
        $this->viewData['RouteGroup'] = 'orders';
        $this->viewData['RouteName'] = Route::currentRouteName();
    }

    public function index(Request $request)
    {
        $this->viewData['title'] = '訂單';

        //新訂單數
        $Orders = Orders::where('created_at', '>=', '2021-01-01')->get();

        return view('backend.orders_index', compact('Orders'))->with($this->viewData);
    }


    public function list(Request $request)
    {
        $datas['draw'] = $request->draw;
        $datas['data'] = array();


        if ($request->has('_type')) {
            $datas['recordsTotal'] = Orders::where(function ($query) use ($request) {
                return $query->where('created_at', '>=', '2021-01-01 00:00:00')
                            ->where('status', '=', $request->_type);
            })->count();
        } else {
            $datas['recordsTotal'] = Orders::where(function ($query) {
                return $query->where('created_at', '>=', '2021-01-01 00:00:00')
                            ->where('status', '=', 'processing');
            })->count();
        }

        if (sizeof($_GET['columns']) > 0) {
            $order = $_GET['order'][0]['column'];
            $sort = $_GET['order'][0]['dir'];
            switch ($order) {
                case '0':
                default:
                    $orderName = "sales_order.increment_id";
                break;
                case '1':
                    $orderName = "sales_order.created_at";
                break;

            }

            if ($_GET['search']['value'] != '') {
                $txtSearch = @trim($_GET['search']['value']);
                $arySearch[] = " sales_order.increment_id  LIKE '%$txtSearch%' ";
                $arySearch[] = " sales_order.created_at  LIKE '%$txtSearch%' ";
                $arySearch[] = " sales_order.customer_firstname  LIKE '%$txtSearch%' ";
                $arySearch[] = " sales_order.customer_lastname  LIKE '%$txtSearch%' ";
                $sqlSearch = " AND ( " . implode(" OR ", $arySearch) . ") ";

                $orWhere[] = array( 'sales_order.increment_id', 'like', '%'.$txtSearch.'%' );
                $orWhere[] = array( 'sales_order.created_at', 'like', '%'.$txtSearch.'%' );
                $orWhere[] = array( 'sales_order.customer_firstname', 'like', '%'.$txtSearch.'%' );
                $orWhere[] = array( 'sales_order.customer_lastname', 'like', '%'.$txtSearch.'%' );
            }


            if ($request->has('_type') && $request->_type != '') {
                $where[] = array( 'sales_order.status', $request->_type);
            } else {
                $where[] = array( 'sales_order.status', 'processing');
                $where[] = array( 'sales_order.created_at', '>=', '2021-01-01 00:00:00');
            }


            $dataAry = DB::table('sales_order');
            if (isset($where) && sizeof($where) > 0) {
                $dataAry->where($where);
            }
            if (isset($orWhere) && sizeof($orWhere) > 0) {
                $dataAry->where(function ($query) use ($orWhere) {
                    foreach ($orWhere as $w => $o) {
                        $query->orWhere($o[0], $o[1], $o[2]);
                    }
                });
            }


            $dataAry = $dataAry->select(
                'sales_order.entity_id',
                'sales_order.increment_id',
                'sales_order.status',
                'sales_order.customer_id',
                'sales_order.base_grand_total',
                'sales_order.subtotal_incl_tax',
                'sales_order.customer_firstname',
                'sales_order.customer_lastname',
                'sales_order.created_at',
                'sales_order.updated_at'
            );

            $dataAry = $dataAry->orderBy($orderName, $sort)->offset($request->start)->limit($request->length)->get();
            if ($dataAry) {
                foreach ($dataAry as $k => $v) {
                    $aryTemp['increment_id'] = $v->increment_id;
                    $aryTemp['created_at'] = $v->created_at;
                    $aryTemp['customer'] = $v->customer_firstname . ' ' . $v->customer_lastname ;
                    $aryTemp['subtotal_incl_tax'] = '$'.number_format($v->subtotal_incl_tax, 2);
                    $aryTemp['base_grand_total'] = '$'.number_format($v->base_grand_total, 2);
                    $aryTemp['status'] = $v->status;
                    $aryTemp['action'] = '<div class="dropdown">
                                                <a class="text-muted dropdown-toggle font-size-24" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                    <i class="mdi mdi-dots-vertical"></i>
                                                </a>

                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="#">檢視預約</a>
                                                    <a class="dropdown-item" href="#">設定顧問</a>
                                                    <a class="dropdown-item" href="#"></a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item" href="#">取消預約</a>
                                                </div>
                                            </div>';

                    $datas['data'][] = $aryTemp;
                    unset($aryTemp);
                }
            }
            $datas['recordsFiltered'] = ($_GET['search']['value'] != '')? sizeof($datas['data']):$datas['recordsTotal'];
            if (sizeof($datas['data']) <= 0) {
                $datas['data'] = array();
            }
        }


        return response()->json($datas);
    }
}
