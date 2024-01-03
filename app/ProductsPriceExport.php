<?php

namespace App;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Http\Request;


class ProductsPriceExport implements FromCollection, WithMapping, WithHeadings
{   

    private $num_product; 


    public function __construct($num_product){
        $this->num_product = $num_product;
    }


    public function collection()
    { 
        $user = \Auth()->user(); 

        // admin can download all products 
        if ($user->user_type == 'admin') {
            $products = Product::select(['id', 'name', 'unit_price', 'purchase_price'])
            ->orderBy('created_at','DESC')
            ->limit($this->num_product)
            ->get();

        // seller will only donwload his products 
        } else {
            $products = Product::where(['user_id' => $user->id])
            ->select(['id', 'name', 'unit_price', 'purchase_price'])
            ->orderBy('created_at','DESC')
            ->limit($this->num_product)
            ->get();
        }
        
        return $products; 
        // return collect(Product::getProduct($this->request));
    }
  

    public function headings(): array
    {
        return [
           'id', 'name', 'unit_price', 'purchase_price'
        ];
    }

    /**
    * @var Product $product
    */
    public function map($product): array
    {
        return [
            $product->id,
            $product->name,
            $product->unit_price,
            $product->purchase_price
        ];
    }
}
