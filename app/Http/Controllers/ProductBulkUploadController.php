<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Category;
use App\SubCategory;
use App\SubSubCategory;
use App\Brand;
use App\User;
use Auth;
use App\ProductsImport;
use App\ProductsPriceImport;
use App\ProductsExport;
use App\ProductsPriceExport;
use PDF;
use Excel;
use Illuminate\Support\Str;
use DB;
class ProductBulkUploadController extends Controller
{
    public function index()
    {
        if (Auth::user()->user_type == 'seller') {
            return view('frontend.user.seller.product_bulk_upload.index');
        }
        elseif (Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff') {
            return view('backend.product.bulk_upload.index');
        }
    }
    public function import_price()
    {
        if (Auth::user()->user_type == 'seller') {
            return view('frontend.user.seller.product_bulk_upload.price_index');
        }
        elseif (Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff') {
            return view('backend.product.bulk_upload.Bulk_price');
        }
    }

    public function price_index() {
        
        if (Auth::user()->user_type == 'seller') {
           
            return view('frontend.user.seller.product_bulk_upload.price_index');
            
        }
        elseif (Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff') {
            return view('backend.product.bulk_upload.price_index');
        }
    }
    /***
     * This is the origine one. Don't change this, it will brokes other functions ! 
     * 
     */
    public function export(Request $request ){
        return Excel::download(new ProductsExport($request), 'products.xlsx');
    }



    /**
     * 
     * 
     * Created by ouarka 
     */
    public function export_price(Request $request) {
       
        $num_product = $request->get('num_product');
        return Excel::download(new ProductsPriceExport($num_product), 'products_price.xlsx');
    }


    public function pdf_download_category()
    {
        $categories = Category::all();

        return PDF::loadView('backend.downloads.category',[
            'categories' => $categories,
        ], [], [])->download('category.pdf');
    }

    public function pdf_download_brand()
    {
        $brands = Brand::all();

        return PDF::loadView('backend.downloads.brand',[
            'brands' => $brands,
        ], [], [])->download('brands.pdf');
    }

    public function pdf_download_seller()
    {
        $users = User::where('user_type','seller')->get();

        return PDF::loadView('backend.downloads.user',[
            'users' => $users,
        ], [], [])->download('user.pdf');

    }
    public function bulk_upload(Request $request)
    {
        if($request->hasFile('bulk_file')){
            Excel::import(new ProductsImport, request()->file('bulk_file'));
        }
        flash(translate('Products imported successfully'))->success();
        return back();
    }


/**
 * 
 * Created by Mahmoud 
 */
    public function Update_Bulk(Request $request){
        if($request->hasFile('bulk_file')){
            Excel::import(new ProductsPriceImport, request()->file('bulk_file'));
        }
        flash(translate('Products Price updated successfully'))->success();
        return back();
        
                
        
    }


    /**
     * 
     * Update product price via excel file ! 
     * Only  update unit_price, purchase_price 
     * 
     */
    public function bulk_price_upload(Request $request) {

        if($request->hasFile('bulk_file')){
            Excel::import(new ProductsPriceImport, request()->file('bulk_file'));
        }
        flash(translate('Products price imported successfully'))->success();
        return back();
    }




}
