<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Redirect;
use Response;
use File;
//use Image;
//use Intervention\Image\Exception\NotReadableException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

use App\Models\ComtopInventory;

class MediaController extends Controller
{
    public function AjaxUploadComtop(Request $request)
    {
        $save_path = public_path('/uploads/');
        $messages = [
            'file.required' => '請選擇上傳的檔案',
            'file.mimes' => '請選擇正確檔案格式',
        ];
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xls,xlsx|max:20480',
        ], $messages);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return Response()->json(array('error'=>1, 'message'=>$errors->first('file')));
        }

        if ($files = $request->file('file')) {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($files);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
            if (isset($sheetData) && count($sheetData) > 1) {
                DB::table('comtopinventory_stock_item')->truncate();

                for ($i=2; $i <= count($sheetData); $i++) {
                    if (!empty($sheetData[$i]['A']) && !empty($sheetData[$i]['B'])) {
                        $ct_sku = trim($sheetData[$i]['A']);
                        $ct_qty = trim($sheetData[$i]['B']);

                        //查詢是否有對應的Cableleader produdct ID
                        $entity_id = DB::table('catalog_product_entity_varchar')
                                ->where([
                                    ['attribute_id', '=', 965],
                                    ['value', '=', $ct_sku]
                                ])->value('entity_id');
                        $cl_productid = ($entity_id)? $entity_id:null;
                        $data['sku'] = $ct_sku;
                        $data['cl_productid'] = $cl_productid;
                        $data['source_qty'] = $ct_qty;
                        $data['qty'] = ($ct_qty==0.0)? 0.00:max($ct_qty, 0.00);
                        $InventoryData = ComtopInventory::create($data);
                    }
                }


                /*
                DB::beginTransaction();
                try {
                    //有資料先清除
                    DB::table('comtopinventory_stock_item')->truncate();
                    $count = 0;
                    for ($i=2; $i <= count($sheetData); $i++) {
                        if (!empty($sheetData[$i]['A']) && !empty($sheetData[$i]['B'])) {
                            $ct_sku = trim($sheetData[$i]['A']);
                            $ct_qty = trim($sheetData[$i]['B']);

                            //查詢是否有對應的Cableleader produdct ID
                            $entity_id = DB::table('catalog_product_entity_varchar')
                                ->where([
                                    ['attribute_id', '=', 965],
                                    ['value', '=', $ct_sku]
                                ])->value('entity_id');
                            $cl_productid = ($entity_id)? $entity_id:null;
                            $data['sku'] = $ct_sku;
                            $data['cl_productid'] = $ct_sku;
                            $data['source_qty'] = $ct_qty;
                            $data['qty'] = ($ct_qty==0.0)? 0.00:max($ct_qty, 0.00);
                            $InventoryData = ComtopInventory::create($data);
                        }
                    }
                    DB::commit();
                    return Response()->json(array('error'=>0));
                } catch (\Exception $e) {
                    DB::rollBack();
                    return Response()->json(array('error'=>1, 'message'=>'資料匯入失敗.' . __LINE__));
                }*/
            }
        }
        return Response()->json(array('error'=>1, 'message'=>'資料匯入失敗.' . __LINE__));
    }
    public function AjaxMedia(Request $request)
    {
        //文件保存目录路径
        $save_path = public_path('/uploads/');
        //文件保存目录URL
        $save_url = '/uploads/';

        $messages = [
            'imgFile.required' => '請選擇上傳的檔案',
            'imgFile.image' => '請選擇圖片檔案格式',
            'imgFile.mimes' => '請選擇圖片檔案格式',
        ];
        $validator = Validator::make($request->all(), [
            'imgFile' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:20480',
        ], $messages);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return Response()->json(array('error'=>1, 'message'=>$errors->first('imgFile')));
        }

        if ($files = $request->file('imgFile')) {
            // Get filename with extension
            $filenameWithExt = $files->getClientOriginalName();
            // Get file path
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Remove unwanted characters
            $filename = preg_replace("/[^A-Za-z0-9 ]/", '', $filename);
            $filename = preg_replace("/\s+/", '-', $filename);

            // Get the original image extension
            $extension = $files->getClientOriginalExtension();

            // Create unique file name
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            //创建文件夹
            $ymd = date("Ymd");
            $save_path .= $ymd . "/";
            $save_url .= $ymd . "/";
            if (!file_exists($save_path)) {
                mkdir($save_path);
            }

            // for save original image
            $ImageUpload = Image::make($files);
            $originalPath = public_path('/uploads/');
            $ImageUpload->save($save_path.$fileNameToStore);

            if ($request->width) {
                $fileNamThumbToStore = $filename.'_'.time().'_thumb.'.$extension;
                // Resize image
                $resize = Image::make($files)->resize($request->width, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->encode($extension);
                $resize->save($save_path.$fileNamThumbToStore);
            }

            return Response()->json(array('error'=>0, 'url'=>$save_url . $fileNameToStore));
        }
    }
}
