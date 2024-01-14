<?php

namespace App\Http\Controllers;

use App\BusinessSetting;
use App\Category;
use App\Color;
use App\Models\Custom\OneClickBuy;
use App\Product;
use App\Tg\TgContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class CartController extends Controller
{
    public function index(Request $request)
    {
        dd(121212);
        $categories = Category::all();
        return view('frontend.view_cart', compact('categories'));
    }

    public function my_card()
    {
        return view('frontend.my_cart');
    }


    public function showCartModal(Request $request)
    {
        $product = Product::find($request->id);
        return view('frontend.partials.addToCart', compact('product'));
    }

    public function updateNavCart(Request $request)
    {
        return view('frontend.partials.cart');
    }

    public function addToCart(Request $request)
    {
        $product = Product::find($request->id);

        $data = array();
        $data['id'] = $product->id;
        $data['owner_id'] = $product->user_id;
        $str = '';
        $tax = 0;

        if ($product->digital != 1 && $request->quantity < $product->min_qty) {
            return array('status' => 0, 'view' => view('frontend.partials.minQtyNotSatisfied', [
                'min_qty' => $product->min_qty,
            ])->render());
        }

        //check the color enabled or disabled for the product
        if ($request->has('color')) {
            $data['color'] = $request['color'];
            // $str = Color::where('code', $request['color'])->first()->name; original line

            $str = Color::where('name', $request['color'])->first()->name; // correced by ouarka.dev@gmail.com
        }

        if ($product->digital != 1) {
            //Gets all the choice values of customer choice option and generate a string like Black-S-Cotton
            foreach (json_decode(Product::find($request->id)->choice_options) as $key => $choice) {
                if ($str != null) {
                    $str .= '-' . str_replace(' ', '', $request['attribute_id_' . $choice->attribute_id]);
                } else {
                    $str .= str_replace(' ', '', $request['attribute_id_' . $choice->attribute_id]);
                }
            }
        }

        $data['variant'] = $str;

        if ($str != null && $product->variant_product) {
            $product_stock = $product->stocks->where('variant', $str)->first();
            $price = $product_stock->price;
            $quantity = $product_stock->qty;

            if ($quantity < $request['quantity']) {
                return array('status' => 0, 'view' => view('frontend.partials.outOfStockCart')->render());
            }
        } else {
            $price = $product->unit_price;
        }

        //discount calculation based on flash deal and regular discount
        //calculation of taxes
        $flash_deals = \App\FlashDeal::where('status', 1)->get();
        $inFlashDeal = false;
        foreach ($flash_deals as $flash_deal) {
            if ($flash_deal != null && $flash_deal->status == 1 && strtotime(date('d-m-Y')) >= $flash_deal->start_date && strtotime(date('d-m-Y')) <= $flash_deal->end_date && \App\FlashDealProduct::where('flash_deal_id', $flash_deal->id)->where('product_id', $product->id)->first() != null) {
                $flash_deal_product = \App\FlashDealProduct::where('flash_deal_id', $flash_deal->id)->where('product_id', $product->id)->first();
                if ($flash_deal_product->discount_type == 'percent') {
                    $price -= ($price * $flash_deal_product->discount) / 100;
                } elseif ($flash_deal_product->discount_type == 'amount') {
                    $price -= $flash_deal_product->discount;
                }
                $inFlashDeal = true;
                break;
            }
        }
        if (!$inFlashDeal) {
            if ($product->discount_type == 'percent') {
                $price -= ($price * $product->discount) / 100;
            } elseif ($product->discount_type == 'amount') {
                $price -= $product->discount;
            }
        }

        if ($product->tax_type == 'percent') {
            $tax = ($price * $product->tax) / 100;
        } elseif ($product->tax_type == 'amount') {
            $tax = $product->tax;
        }

        $data['quantity'] = $request['quantity'];
        $data['price'] = $price;
        $data['tax'] = $tax;
        $data['shipping'] = 0;
        $data['product_referral_code'] = null;
        $data['digital'] = $product->digital;

        $data['bonus'] = false;
        $data['bonus_id'] = $product->bonus_product_id;
        $data['parent_id'] = null;

        if ($request['quantity'] == null) {
            $data['quantity'] = 1;
        }

        if (Cookie::has('referred_product_id') && Cookie::get('referred_product_id') == $product->id) {
            $data['product_referral_code'] = Cookie::get('product_referral_code');
        }

        if ($request->session()->has('cart')) {
            $foundInCart = false;
            $cart = collect();

            $bonus_found = false;

            foreach ($request->session()->get('cart') as $key => $cartItem) {

                if ($cartItem['bonus']) {
                    $bonus_found = true;
                }
                if ($cartItem['id'] == $request->id) {
                    if ($cartItem['variant'] == $str && $str != null) {
                        $product_stock = $product->stocks->where('variant', $str)->first();
                        $quantity = $product_stock->qty;

                        if ($quantity < $cartItem['quantity'] + $request['quantity']) {
                            return array('status' => 0, 'view' => view('frontend.partials.outOfStockCart')->render());
                        } else {
                            $foundInCart = true;
                            $cartItem['quantity'] += $request['quantity'];

                        }
                    }
                }
                $cart->push($cartItem);

            } // end foreach

            if (!$foundInCart) {
                $cart->push($data);
            }

            // bonus not found & the product has bonus product
            // if (!$bonus_found && $data['bonus_id'] != 0) {
            //     if ($product->bonus_any_quantity) {
            //         $bonus_product = Product::find($data['bonus_id']);
            //         if ($bonus_product) {
            //             $bonus = [
            //                 'id' => $bonus_product->id,
            //                 'owner_id' => $product->user_id,
            //                 'variant' => '',
            //                 'quantity' => 1,
            //                 'price' => 0,
            //                 'tax' => 0,
            //                 'shipping' => 0,
            //                 'product_referral_code' => null,
            //                 'digital' => 0,
            //                 'bonus' => true,
            //                 'bonus_id' => 0,
            //             ];
            //             $cart->push($bonus);
            //         }
            //     } elseif ($request->quantity >= $product->bonus_condition) {
            //         $bonus_product = Product::find($data['bonus_id']);

            //         if ($bonus_product) {
            //             $bonus = [
            //                 'id' => $bonus_product->id,
            //                 'owner_id' => $product->user_id,
            //                 'variant' => '',
            //                 'quantity' => intval($data['quantity'] / $product->bonus_condition),
            //                 'price' => 0,
            //                 'tax' => 0,
            //                 'shipping' => 0,
            //                 'product_referral_code' => null,
            //                 'digital' => 0,
            //                 'bonus' => true,
            //                 'bonus_id' => 0,
            //             ];
            //             $cart->push($bonus);
            //         }
            //     }
            // }

            // $request->session()->put('cart', $cart);
        } else {
            $cart = collect([$data]);

            // if ($data['bonus_id'] != 0) {

            //     if ($product->bonus_any_quantity) {
            //         $bonus_product = Product::find($product->bonus_product_id);
            //         if ($bonus_product) {
            //             $bonus = [
            //                 'id' => $bonus_product->id,
            //                 'owner_id' => $product->user_id,
            //                 'variant' => '',
            //                 'quantity' => 1,
            //                 'price' => 0,
            //                 'tax' => 0,
            //                 'shipping' => 0,
            //                 'product_referral_code' => null,
            //                 'digital' => 0,
            //                 'bonus' => true,
            //                 'bonus_id' => 0,
            //             ];

            //             $cart->push($bonus);
            //         }
            //     } elseif ($request->quantity >= $product->bonus_condition) {
            //         $bonus_product = Product::find($product->bonus_product_id);
            //         if ($bonus_product) {
            //             $bonus = [
            //                 'id' => $bonus_product->id,
            //                 'owner_id' => $product->user_id,
            //                 'variant' => '',
            //                 'quantity' => intval($data['quantity'] / $product->bonus_condition),
            //                 'price' => 0,
            //                 'tax' => 0,
            //                 'shipping' => 0,
            //                 'product_referral_code' => null,
            //                 'digital' => 0,
            //                 'bonus' => true,
            //                 'bonus_id' => 0,
            //             ];

            //             $cart->push($bonus);
            //         }
            //     }

            // }

            // $request->session()->put('cart', $cart);
        }

        $d = [
            [ // normal product has a bonus product
                'id' => 1467,
                'name' => 'demo',
                'bonus_id' => 1479,
                'parent_id' => null,

            ],
            [ // bonus product
                'id' => 1479,
                'name' => 'toto',
                'bonus_id' => null,
                'parent_id' => 1467,

            ],
            [ // bonus product
                'id' => 1481,
                'name' => 'toto',
                'bonus_id' => 1479,
                'parend_id' => null,

            ],
            [ // bonus product
                'id' => 1479,
                'name' => 'toto',
                'bonus_id' => null,
                'parent_id' => 1481,
            ],
        ];

        $d = collect($d);

        // dd($d->where('id',1479)->where('parent_id', 1481)->count());

        /**
         * check if product has a bonus product
         * No => add bonus product to cart
         * Yes
         *      => check if that bonus is related to same product
         *      Yes => break
         *      No  => add bonus to cart !
         */

        if (BusinessSetting::where('type', 'bonus_product_activation')->first()->value) {

            $tmp_cart = $cart;
            foreach ($cart as $c) {
                if ($c['bonus_id']) {
                    $bonus = $cart->where('id', $c['bonus_id'])
                        ->where('parent_id', $c['id'])
                        ->count();

                    if ($bonus == 1) {
                        continue;
                    } else {
                        $product = Product::find($c['id']);
                        $bonus_product = $product->bonus()->first();
                        if ($product->bonus_any_quantity) {
                            $tmp_cart->push([
                                'id' => $bonus_product->id,
                                'owner_id' => $product->user_id,
                                'variant' => '',
                                'quantity' => 1,
                                'price' => 0,
                                'tax' => 0,
                                'shipping' => 0,
                                'product_referral_code' => null,
                                'digital' => 0,
                                'bonus' => true,
                                'bonus_id' => null,
                                'parent_id' => $product->id,
                            ]);
                        } elseif ($request->quantity >= $product->bonus_condition) {
                            $tmp_cart->push([
                                'id' => $bonus_product->id,
                                'owner_id' => $product->user_id,
                                'variant' => '',
                                'quantity' => intval($data['quantity'] / $product->bonus_condition),
                                'price' => 0,
                                'tax' => 0,
                                'shipping' => 0,
                                'product_referral_code' => null,
                                'digital' => 0,
                                'bonus' => true,
                                'bonus_id' => 0,
                                'parent_id' => $product->id,
                            ]);
                        }
                    }
                }
            }
            $cart = $tmp_cart;
        }

        $request->session()->put('cart', $cart);
        return array('status' => 1, 'view' => view('frontend.partials.addedToCart', compact('product', 'data'))->render());
    }

    //removes from Cart
    public function removeFromCart(Request $request)
    {
        if ($request->session()->has('cart')) {
            $cart = $request->session()->get('cart', collect([]));
            // $is_bonus = false;
            // if ($cart[$request->key]['bonus_id']) {
            //     $is_bonus = true;
            // }

            // if ($cart[$request->key]['bonus_id'] != 0) {
            //     foreach ($cart as $key => $c) {
            //         if ($c['id'] == $cart[$request->key]['bonus_id']) {
            //             $cart->forget($key);
            //         }
            //     }
            // }
            if (BusinessSetting::where('type', 'bonus_product_activation')->first()->value) {
                $c = $cart[$request->key];
                $tmp_cart = $cart;
                if ($c['bonus_id']) {
                    $tmp_cart = $tmp_cart->reject(function ($item, $key) use ($c) {
                        if ($item['id'] == $c['bonus_id'] && $item['parent_id'] == $c['id']) {
                            return $item;
                        }
                    });
                }

                $cart = $tmp_cart;
            }
            $cart->forget($request->key);
            $request->session()->put('cart', $cart);
        }

        //return view('frontend.partials.cart_details');
        return view('frontend.partials.my_cart_details');
    }

    //updated the quantity for a cart item
    public function updateQuantity(Request $request)
    {
        $cart = $request->session()->get('cart', collect([]));;
        $cart = $cart->map(function ($object, $key) use ($request) {
            if ($key == $request->key) {
                $product = \App\Product::find($object['id']);
                if ($object['variant'] != null && $product->variant_product) {
                    $product_stock = $product->stocks->where('variant', $object['variant'])->first();
                    $quantity = $product_stock->qty;
                    if ($quantity >= $request->quantity) {
                        if ($request->quantity >= $product->min_qty) {
                            $object['quantity'] = $request->quantity;
                        }
                    }
                } elseif ($product->current_stock >= $request->quantity) {
                    if ($request->quantity >= $product->min_qty) {
                        $object['quantity'] = $request->quantity;
                    }
                }
            }

            return $object;
        });

        if (BusinessSetting::where('type', 'bonus_product_activation')->first()->value) {
            $tmp_cart = $cart;
            foreach ($cart as $key => $c) {
                if ($key == $request->key) {

                    if ($c['bonus_id']) { // product has a bonus
                        $bonus = $cart->where('id', $c['bonus_id'])
                            ->where('parent_id', $c['id'])
                            ->count();

                        if ($bonus == 1) { // bonus already in cart
                            $product = Product::find($c['id']);
                            $bonus_product = $product->bonus()->first();
                            if ($product->bonus_any_quantity) {

                                if ($request->quantity < $product->bonus_condition) {
                                    // $tmp_cart = $tmp_cart->filter( function ($item, $key) use ($bonus_product, $product) {
                                    //     return  !($item['id'] == $bonus_product->id && $item['parent_id'] == $product->id);
                                    // });
                                    // dd($tmp_cart);
                                }
                            } else {
                                if ($request->quantity < $product->bonus_condition) {
                                    $tmp_cart = $tmp_cart->filter(function ($item, $key) use ($bonus_product, $product) {
                                        return !($item['id'] == $bonus_product->id && $item['parent_id'] == $product->id);
                                    });

                                } elseif ($request->quantity >= $product->bonus_condition) {
                                    $tmp_cart = $tmp_cart->map(function ($item, $key) use ($c, $request, $product) {
                                        if ($item['id'] == $c['bonus_id'] && $item['parent_id'] == $c['id']) {
                                            $item['quantity'] = intval($request->quantity / $product->bonus_condition);
                                        }

                                        return $item;
                                    });

                                }
                            }

                        } else { // check condition and add bonus to cart !

                            $product = Product::find($c['id']);
                            $bonus_product = $product->bonus()->first();
                            if ($product->bonus_any_quantity) {
                                $tmp_cart->push([
                                    'id' => $bonus_product->id,
                                    'owner_id' => $product->user_id,
                                    'variant' => '',
                                    'quantity' => 1,
                                    'price' => 0,
                                    'tax' => 0,
                                    'shipping' => 0,
                                    'product_referral_code' => null,
                                    'digital' => 0,
                                    'bonus' => true,
                                    'bonus_id' => 0,
                                    'parent_id' => $product->id,
                                ]);
                            } elseif ($request->quantity >= $product->bonus_condition) {
                                $tmp_cart->push([
                                    'id' => $bonus_product->id,
                                    'owner_id' => $product->user_id,
                                    'variant' => '',
                                    'quantity' => intval($request->quantity / $product->bonus_condition),
                                    'price' => 0,
                                    'tax' => 0,
                                    'shipping' => 0,
                                    'product_referral_code' => null,
                                    'digital' => 0,
                                    'bonus' => true,
                                    'bonus_id' => 0,
                                    'parent_id' => $product->id,
                                ]);
                            }

                        }
                    } else {

                    }
                }
            }

            $cart = $tmp_cart;
        }
        // $bonus_key = -1;
        // foreach ($cart as $key => $c) {
        //     if ($c['bonus']) {
        //         $bonus_key = $key;
        //         break;
        //     }
        // }

        // if ($bonus_key == -1) { // bonus product does not exist in cart
        //     foreach ($cart as $key => $c) {
        //         if ($c['bonus_id'] != 0) {

        //             $product = Product::find($c['id']);

        //             if($product && $product->bonus_product_id && $product->bonus_any_quantity) {
        //                 $bonus_product = Product::find($c['bonus_id']);

        //                 if ($bonus_product) {
        //                     $bonus = [
        //                         'id' => $bonus_product->id,
        //                         'owner_id' => $product->user_id,
        //                         'variant' => '',
        //                         'quantity' => 1,
        //                         'price' => 0,
        //                         'tax' => 0,
        //                         'shipping' => 0,
        //                         'product_referral_code' => null,
        //                         'digital' => 0,
        //                         'bonus' => true,
        //                         'bonus_id' => 0,
        //                     ];
        //                     $cart->push($bonus);
        //                     break;
        //                 }
        //             }elseif ($product && $product->bonus_product_id && $request->quantity >= $product->bonus_condition) {
        //                 $bonus_product = Product::find($c['bonus_id']);

        //                 if ($bonus_product) {
        //                     $bonus = [
        //                         'id' => $bonus_product->id,
        //                         'owner_id' => $product->user_id,
        //                         'variant' => '',
        //                         'quantity' => intval($request->quantity / $product->bonus_condition),
        //                         'price' => 0,
        //                         'tax' => 0,
        //                         'shipping' => 0,
        //                         'product_referral_code' => null,
        //                         'digital' => 0,
        //                         'bonus' => true,
        //                         'bonus_id' => 0,
        //                     ];
        //                     $cart->push($bonus);
        //                     break;
        //                 }
        //             }

        //         }

        //     }
        // } else { // bonus product exist in cart: check quantity condition
        //     $bonus_product = Product::find($cart[$bonus_key]['id']);
        //     $product = Product::where('bonus_product_id', $bonus_product->id)->first();

        //     if($product->bonus_any_quantity) {

        //     } else {
        //         $cart = $cart->map(function ($object, $key) use ($bonus_key, $request, $product) {
        //             if ($key == $bonus_key) {

        //                 $object['quantity'] = intval($request->quantity / $product->bonus_condition);
        //             }
        //             return $object;
        //         });

        //         if ($product && $request->quantity < $product->bonus_condition) {
        //             unset($cart[$bonus_key]);
        //         }
        //     }

        // }

        $request->session()->put('cart', $cart);
        //return view('frontend.partials.cart_details');
        return view('frontend.partials.my_cart_details');
    }

    public function one_click($id)
    {
        dd($id);
        $product = Product::find($id);
        return view('frontend.oneClick', compact('product'));
    }

    public function one_click_payment(Request $request)
    {

        $request->validate([
            "phone" => 'required|numeric|min:12',
            "name" => 'required',
            "address" => 'required',
            "agree_to_buy" => 'required',
        ]);
        $data = OneClickBuy::create($request->toArray());
        /*event(new OrderStore($data));*/
        $tg = new TgContent();
        $tg->suspendido(TgContent::REPORT_TELEGRAM_ID, $data);
        return redirect()->route('oneClick_order_preconfirmed', ['id' => $data->id]);
    }

    public function oneClick_order_preconfirmed($id)
    {
        $data = OneClickBuy::where('id', '=', $id)->get();
        return view('frontend.oneClick_order_preconfirmed', compact('data'));
    }


}
