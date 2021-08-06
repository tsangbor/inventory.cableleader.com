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

class ProductsController extends Controller
{
    protected $viewData = [];
    public function __construct()
    {
        $this->viewData['RouteGroup'] = 'products';
        $this->viewData['RouteName'] = Route::currentRouteName();
    }

    public function index(Request $request)
    {
        $this->viewData['title'] = '商品清單';

        //新訂單數
        $Products = Products::all();
        /*
        $productAry = Products::find(640);
        $attributeAry = $productAry->attributes;
        $SKU = $attributeAry->where('attribute_id', 982)->first()->value;
        */
        //dd($SKU);

        return view('backend.products_index', compact('Products'))->with($this->viewData);
    }

    public function list(Request $request)
    {
        $datas['draw'] = $request->draw;
        $datas['data'] = array();
        $datas['recordsTotal'] = Products::all()->count();

        if (sizeof($_GET['columns']) > 0) {
            $order = $_GET['order'][0]['column'];
            $sort = $_GET['order'][0]['dir'];
            switch ($order) {
                case '0':
                default:
                    $orderName = "tmp.entity_id";
                break;
                case '1':
                    $orderName = "tmp.name";
                break;

            }

            if ($_GET['search']['value'] != '') {
                $txtSearch = @trim($_GET['search']['value']);
                $arySearch[] = " tmp.entity_id  LIKE '%$txtSearch%' ";
                $arySearch[] = " tmp.sku  LIKE '%$txtSearch%' ";
                $arySearch[] = " tmp.ct_sku  LIKE '%$txtSearch%' ";
                $sqlSearch = " AND ( " . implode(" OR ", $arySearch) . ") ";

                $orWhere[] = array( 'tmp.entity_id', 'like', '%'.$txtSearch.'%' );
                $orWhere[] = array( 'tmp.sku', 'like', '%'.$txtSearch.'%' );
                $orWhere[] = array( 'tmp.ct_sku', 'like', '%'.$txtSearch.'%' );
            }


            $innerTable = DB::table('catalog_product_entity')->select(
                DB::raw('
                        catalog_product_entity.entity_id,
                        catalog_product_entity.sku,
                        catalog_product_entity.created_at,
                        catalog_product_entity.updated_at,
                        ( SELECT catalog_product_entity_varchar.value FROM catalog_product_entity_varchar WHERE catalog_product_entity_varchar.attribute_id=96 AND catalog_product_entity_varchar.entity_id=catalog_product_entity.entity_id ) as name,
                        ( SELECT catalog_product_entity_varchar.value FROM catalog_product_entity_varchar WHERE catalog_product_entity_varchar.attribute_id=982 AND catalog_product_entity_varchar.entity_id=catalog_product_entity.entity_id ) as cl_sku,
                        ( SELECT catalog_product_entity_varchar.value FROM catalog_product_entity_varchar WHERE catalog_product_entity_varchar.attribute_id=965 AND catalog_product_entity_varchar.entity_id=catalog_product_entity.entity_id ) as ct_sku,
                        ( SELECT catalog_product_entity_int.value FROM catalog_product_entity_int WHERE catalog_product_entity_int.attribute_id=273 AND catalog_product_entity_int.entity_id=catalog_product_entity.entity_id ) as status,
                        cataloginventory_stock_item.is_in_stock,
                        cataloginventory_stock_item.min_qty,
                        cataloginventory_stock_item.qty as cl_qty,
                        IFNULL(comtopinventory_stock_item.qty, 0) as ct_qty')
            )->leftJoin('cataloginventory_stock_item', 'catalog_product_entity.entity_id', '=', 'cataloginventory_stock_item.product_id')
            ->leftJoin('comtopinventory_stock_item', 'catalog_product_entity.entity_id', '=', 'comtopinventory_stock_item.cl_productid')
            ->orderBy('catalog_product_entity.entity_id', 'asc');

            $dataAry = DB::table(DB::raw('('.$innerTable->toSql().') as tmp'));
            /*
            if (isset($where) && sizeof($where) > 0) {
                $dataAry->where($where);
            }*/
            if (isset($orWhere) && sizeof($orWhere) > 0) {
                $dataAry->where(function ($query) use ($orWhere) {
                    foreach ($orWhere as $w => $o) {
                        $query->orWhere($o[0], $o[1], $o[2]);
                    }
                });
            }
            $dataAry = $dataAry->orderBy($orderName, $sort)->offset($request->start)->limit($request->length)->get();
            if ($dataAry) {
                foreach ($dataAry as $k => $v) {
                    $aryTemp['entity_id'] = $v->entity_id;
                    $aryTemp['name'] = $v->name;
                    $aryTemp['cl_sku'] = $v->cl_sku;
                    $aryTemp['cl_qty'] = number_format($v->cl_qty, 0);
                    $aryTemp['ct_sku'] = $v->ct_sku;
                    $aryTemp['ct_qty'] = number_format($v->ct_qty, 0);
                    $aryTemp['min_qty'] = number_format($v->min_qty, 0);
                    $aryTemp['recom_qty'] = 0;
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
