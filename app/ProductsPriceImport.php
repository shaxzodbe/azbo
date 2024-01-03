<?php

namespace App;

use Auth;
use App\User;
use App\Upload;
use App\Product;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;



class ProductsPriceImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows) {
       // update the Price of products  Import
        $user_id = \Auth()->user()->id; 


        foreach($rows as $row) {
                $product = Product::find($row['id']);

                if ($product->user_id == $user_id) {
                    $product->update([
                        'unit_price' => $row['unit_price'],
                        'purchase_price' => $row['purchase_price']
                    ]);
                }
        }
    }

    public function rules(): array
    {
        return [
             // Can also use callback validation rules
             'unit_price' => function($attribute, $value, $onFailure) {
                  if (!is_numeric($value)) {
                       $onFailure('Unit price is not numeric');
                  }
              }
        ];
    }

    
}