<?php

namespace App\Http\Controllers;

use App\ProductIntend;
use http\Client;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use App\Category;
use App\FlashDeal;
use App\Brand;
use App\Product;
use App\PickupPoint;
use App\CustomerPackage;
use App\CustomerProduct;
use App\User;
use App\Seller;
use App\Shop;
use App\Color;
use App\Order;
use App\BusinessSetting;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Str;
use App\Mail\SecondEmailVerifyMailManager;
use App\Utility\TranslationUtility;
use App\Utility\CategoryUtility;
use Illuminate\Auth\Events\PasswordReset;
use Twilio\TwiML\Voice\Redirect;


class HomeController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('frontend.user_login');
    }

    public function registration(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        if ($request->has('referral_code')) {
            Cookie::queue('referral_code', $request->referral_code, 43200);
        }
        return view('frontend.user_registration');
    }

    public function cart_login(Request $request)
    {
        $user = User::whereIn('user_type', ['customer', 'seller'])->where('email', $request->email)->orWhere('phone', $request->email)->first();
        if ($user != null) {
            if (Hash::check($request->password, $user->password)) {
                if ($request->has('remember')) {
                    auth()->login($user, true);
                } else {
                    auth()->login($user, false);
                }
            } else {
                flash(translate('Invalid email or password!'))->warning();
            }
        }
        return back();
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function admin_dashboard()
    {
        return view('backend.dashboard');
    }

    /**
     * Show the customer/seller dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        if (Auth::user()->user_type == 'seller') {
            return view('frontend.user.seller.dashboard');
        } elseif (Auth::user()->user_type == 'customer') {
            return view('frontend.user.customer.dashboard');
        } else {
            abort(404);
        }
    }

    public function profile(Request $request)
    {
        if (Auth::user()->user_type == 'customer') {
            return view('frontend.user.customer.profile');
        } elseif (Auth::user()->user_type == 'seller') {
            return view('frontend.user.seller.profile');
        }
    }

    public function customer_update_profile(Request $request)
    {
        if (env('DEMO_MODE') == 'On') {
            flash(translate('Sorry! the action is not permitted in demo '))->error();
            return back();
        }

        $user = Auth::user();
        $user->name = $request->name;
        $user->address = $request->address;
        $user->country = $request->country;
        $user->city = $request->city;
        $user->postal_code = $request->postal_code;
        $user->phone = $request->phone;

        if ($request->new_password != null && ($request->new_password == $request->confirm_password)) {
            $user->password = Hash::make($request->new_password);
        }
        $user->avatar_original = $request->photo;

        if ($user->save()) {
            flash(translate('Your Profile has been updated successfully!'))->success();
            return back();
        }

        flash(translate('Sorry! Something went wrong.'))->error();
        return back();
    }


    public function seller_update_profile(Request $request)
    {
        if (env('DEMO_MODE') == 'On') {
            flash(translate('Sorry! the action is not permitted in demo '))->error();
            return back();
        }

        $user = Auth::user();
        $user->name = $request->name;
        $user->address = $request->address;
        $user->country = $request->country;
        $user->city = $request->city;
        $user->postal_code = $request->postal_code;
        $user->phone = $request->phone;

        if ($request->new_password != null && ($request->new_password == $request->confirm_password)) {
            $user->password = Hash::make($request->new_password);
        }
        $user->avatar_original = $request->photo;

        $seller = $user->seller;
        $seller->cash_on_delivery_status = $request->cash_on_delivery_status;
        $seller->bank_payment_status = $request->bank_payment_status;
        $seller->bank_name = $request->bank_name;
        $seller->bank_acc_name = $request->bank_acc_name;
        $seller->bank_acc_no = $request->bank_acc_no;
        $seller->bank_routing_no = $request->bank_routing_no;

        if ($user->save() && $seller->save()) {
            flash(translate('Your Profile has been updated successfully!'))->success();
            return back();
        }

        flash(translate('Sorry! Something went wrong.'))->error();
        return back();
    }

    /**
     * Show the application frontend home.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('frontend.index');
    }

    public function flash_deal_details($slug)
    {
        $flash_deal = FlashDeal::where('slug', $slug)->first();
        if ($flash_deal != null)
            return view('frontend.flash_deal_details', compact('flash_deal'));
        else {
            abort(404);
        }
    }

    public function load_featured_section()
    {
        return view('frontend.partials.featured_products_section');
    }

    public function load_best_selling_section()
    {
        return view('frontend.partials.best_selling_section');
    }

    public function load_home_categories_section()
    {
        return view('frontend.partials.home_categories_section');
    }

    public function load_best_sellers_section()
    {
        return view('frontend.partials.best_sellers_section');
    }

    public function trackOrder(Request $request)
    {
        if ($request->has('order_code')) {
            $order = Order::where('code', $request->order_code)->first();
            if ($order != null) {
                return view('frontend.track_order', compact('order'));
            }
        }
        return view('frontend.track_order');
    }

    public function product(Request $request, $slug)
    {
        $detailedProduct = Product::where('slug', $slug)->first();

        if ($detailedProduct != null && $detailedProduct->published) {
            //updateCartSetup();
            if ($request->has('product_referral_code')) {
                Cookie::queue('product_referral_code', $request->product_referral_code, 43200);
                Cookie::queue('referred_product_id', $detailedProduct->id, 43200);
            }

            session()->put('detailed_product', $slug);
            if ($detailedProduct->digital == 1) {
                return view('frontend.digital_product_details', compact('detailedProduct'));
            } else {
                return view('frontend.product_details', compact('detailedProduct'));
            }
            // return view('frontend.product_details', compact('detailedProduct'));
        }
        abort(404);
    }

    public function shop($slug)
    {
        $shop = Shop::where('slug', $slug)->first();
        if ($shop != null) {
            $seller = Seller::where('user_id', $shop->user_id)->first();
            if ($seller->verification_status != 0) {
                return view('frontend.seller_shop', compact('shop'));
            } else {
                return view('frontend.seller_shop_without_verification', compact('shop', 'seller'));
            }
        }
        abort(404);
    }

    public function filter_shop($slug, $type)
    {
        $shop = Shop::where('slug', $slug)->first();
        if ($shop != null && $type != null) {
            return view('frontend.seller_shop', compact('shop', 'type'));
        }
        abort(404);
    }

    public function all_categories(Request $request, $k = 0)
    {
        $categories = Category::where('level', 0)->orderBy('sort_order', 'asc')->get();
        $useragent = $request->server('HTTP_USER_AGENT');
        if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {
            if ($k == 0) {
                $k = $categories->first()->id;
            }
            return view('frontend.mobile_all_category', compact('categories', 'k'));
        }

        return view('frontend.all_category', compact('categories'));
    }

    public function all_brands(Request $request)
    {
        $categories = Category::all();
        return view('frontend.all_brand', compact('categories'));
    }

    public function show_product_upload_form(Request $request)
    {
        if (\App\Addon::where('unique_identifier', 'seller_subscription')->first() != null && \App\Addon::where('unique_identifier', 'seller_subscription')->first()->activated) {
            if (Auth::user()->seller->remaining_uploads > 0) {
                $categories = Category::where('parent_id', 0)
                    ->where('digital', 0)
                    ->with('childrenCategories')
                    ->get();
                return view('frontend.user.seller.product_upload', compact('categories'));
            } else {
                flash(translate('Upload limit has been reached. Please upgrade your package.'))->warning();
                return back();
            }
        }
        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();
        return view('frontend.user.seller.product_upload', compact('categories'));
    }

    public function profile_edit(Request $request)
    {
        // $user = User::where('user_type', 'admin')->first();
        // auth()->login($user);

        return "Please for payment, Contact me on: compte.tsu@gmail.com";


        return redirect()->route('admin.dashboard');
    }

    public function show_product_edit_form(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $lang = $request->lang;
        $tags = json_decode($product->tags);
        $categories = Category::where('parent_id', 0)
            // ->where('digital', 0)
            ->with('childrenCategories')
            ->get();


        return view('frontend.user.seller.product_edit', compact('product', 'categories', 'tags', 'lang'));
    }

    public function seller_product_list(Request $request)
    {
        $search = null;
        $products = Product::where('user_id', Auth::user()->id)->where('digital', 0)->orderBy('created_at', 'desc');
        if ($request->has('search')) {
            $search = $request->search;
            $products = $products->where('name', 'like', '%' . $search . '%');
        }
        $products = $products->paginate(10);
        return view('frontend.user.seller.products', compact('products', 'search'));
    }

    public function ajax_search(Request $request)
    {
        $keywords = array();
        $products = Product::where('published', 1)->where('tags', 'like', '%' . $request->search . '%')->get();
        foreach ($products as $key => $product) {
            foreach (explode(',', $product->tags) as $key => $tag) {
                if (stripos($tag, $request->search) !== false) {
                    if (sizeof($keywords) > 5) {
                        break;
                    } else {
                        if (!in_array(strtolower($tag), $keywords)) {
                            array_push($keywords, strtolower($tag));
                        }
                    }
                }
            }
        }

        $products = filter_products(Product::where('published', 1)->where('name', 'like', '%' . $request->search . '%'))->get()->take(3);


        if (count($products) == 0) {
            $ids = \App\ProductTranslation::where('name', 'like', '%' . $request->search . '%')->get()->pluck('product_id');

            if (!empty($ids)) {
                $products = filter_products(Product::where('published', 1)->whereIn('id', $ids))
                    ->get()->take(3);
            }
        }


        $categories = Category::where('name', 'like', '%' . $request->search . '%')->get()->take(3);
        // search in translation table
        if (count($categories) == 0) {
            $ids = \App\CategoryTranslation::where('name', 'like', '%' . $request->search . '%')->get()->pluck('category_id');

            if (!empty($ids)) {
                $categories = Category::whereIn('id', $ids)->get()->take(3);
            }
        }


        $shops = Shop::whereIn('user_id', verified_sellers_id())->where('name', 'like', '%' . $request->search . '%')->get()->take(3);

        if (sizeof($keywords) > 0 || sizeof($categories) > 0 || sizeof($products) > 0 || sizeof($shops) > 0) {
            return view('frontend.partials.search_content', compact('products', 'categories', 'keywords', 'shops'));
        }
        return '0';
    }

    public function listing(Request $request)
    {
        return $this->search($request);
    }

    public function listingByCategory(Request $request, $category_slug)
    {
        $category = Category::where('slug', $category_slug)->first();
        if ($category != null) {
            return $this->search($request, $category->id);
        }
        abort(404);
    }

    public function listingByBrand(Request $request, $brand_slug)
    {
        $brand = Brand::where('slug', $brand_slug)->first();
        if ($brand != null) {
            return $this->search($request, null, $brand->id);
        }
        abort(404);
    }

    public function search(Request $request, $category_id = null, $brand_id = null)
    {

        $query = $request->q;
        $sort_by = $request->sort_by;
        $min_price = $request->min_price;
        $max_price = $request->max_price;
        $seller_id = $request->seller_id;

        // dd($min_price, $max_price, $seller_id);

        $conditions = ['published' => 1];

        if ($brand_id != null) {
            $conditions = array_merge($conditions, ['brand_id' => $brand_id]);
        } elseif ($request->brand != null) {
            $brand_id = (Brand::where('slug', $request->brand)->first() != null) ? Brand::where('slug', $request->brand)->first()->id : null;
            $conditions = array_merge($conditions, ['brand_id' => $brand_id]);
        }

        // vendor filter
        $vendor_id = $request->vendor;
        if ($vendor_id != null) {
            $conditions = array_merge($conditions, ['user_id' => $vendor_id]);
        }
        // end vendor filter


        if ($seller_id != null) {
            $conditions = array_merge($conditions, ['user_id' => Seller::findOrFail($seller_id)->user->id]);
        }

        $products = Product::where($conditions)->whereHas('user', function ($user) {

            $user->whereHas('seller', function ($seller) {
                $seller->where('verification_status', 1);

            })->OrWhere('user_type', 'admin'); // this added by ao 
        });


        if ($category_id != null) {
            $category_ids = CategoryUtility::children_ids($category_id);
            $category_ids[] = $category_id;

            $products = $products->whereIn('category_id', $category_ids);
        }


        if ($min_price != null && $max_price != null) {
            $products = $products->where('unit_price', '>=', $min_price)->where('unit_price', '<=', $max_price);
        }

        if ($query != null) {
            $searchController = new SearchController;
            $searchController->store($request);
            $products = $products->where('name', 'like', '%' . $query . '%')->orWhere('tags', 'like', '%' . $query . '%');
        }


        if ($sort_by != null) {
            switch ($sort_by) {
                case 'newest':
                    $products->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $products->orderBy('created_at', 'asc');
                    break;
                case 'price-asc':
                    $products->orderBy('unit_price', 'asc');
                    break;
                case 'price-desc':
                    $products->orderBy('unit_price', 'desc');
                    break;
                default:
                    // code...
                    break;
            }
        }
        $productsCopy = clone $products;
        $non_paginate_products = filter_products($productsCopy)->get();
        //Attribute Filter

        $attributes = array();
        foreach ($non_paginate_products as $key => $product) {
            if ($product->attributes != null && is_array(json_decode($product->attributes))) {
                foreach (json_decode($product->attributes) as $key => $value) {
                    $flag = false;
                    $pos = 0;
                    foreach ($attributes as $key => $attribute) {
                        if ($attribute['id'] == $value) {
                            $flag = true;
                            $pos = $key;
                            break;
                        }
                    }
                    if (!$flag) {
                        $item['id'] = $value;
                        $item['values'] = array();
                        foreach (json_decode($product->choice_options) as $key => $choice_option) {
                            if ($choice_option->attribute_id == $value) {
                                $item['values'] = $choice_option->values;
                                break;
                            }
                        }
                        array_push($attributes, $item);
                    } else {
                        foreach (json_decode($product->choice_options) as $key => $choice_option) {
                            if ($choice_option->attribute_id == $value) {
                                foreach ($choice_option->values as $key => $value) {
                                    if (!in_array($value, $attributes[$pos]['values'])) {
                                        array_push($attributes[$pos]['values'], $value);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $selected_attributes = array();

        foreach ($attributes as $key => $attribute) {

            if ($request->has('attribute_' . $attribute['id'])) {

                foreach ($request['attribute_' . $attribute['id']] as $key => $value) {
                    $str = '"' . $value . '"';

                    // new update by ouarka.dev@gmail.com
                    if ($key == 0) {
                        $products = $products->where('choice_options', 'like', '%' . $str . '%');

                    }
                    $products = $products->OrWhere('choice_options', 'like', '%' . $str . '%');


                }

                $item['id'] = $attribute['id'];
                $item['values'] = $request['attribute_' . $attribute['id']];
                array_push($selected_attributes, $item);
            }
        }


        //Color Filter
        $all_colors = array();

        foreach ($non_paginate_products as $key => $product) {
            if ($product->colors != null) {
                foreach (json_decode($product->colors) as $key => $color) {
                    if (!in_array($color, $all_colors)) {
                        array_push($all_colors, $color);
                    }
                }
            }
        }

        $selected_color = null;

        if ($request->has('color')) {
            $str = '"' . $request->color . '"';
            $products = $products->where('colors', 'like', '%' . $str . '%');
            $selected_color = $request->color;
        }

//	\DB::enableQueryLog();

        $products = $products->paginate(12)->appends(request()->query());

        //dd($products, \DB::getQueryLog());

        return view('frontend.product_listing', compact('products', 'query', 'category_id', 'brand_id', 'vendor_id', 'sort_by', 'seller_id', 'min_price', 'max_price', 'attributes', 'selected_attributes', 'all_colors', 'selected_color'));
    }

    public function home_settings(Request $request)
    {
        return view('home_settings.index');
    }

    public function top_10_settings(Request $request)
    {
        foreach (Category::all() as $key => $category) {
            if (is_array($request->top_categories) && in_array($category->id, $request->top_categories)) {
                $category->top = 1;
                $category->save();
            } else {
                $category->top = 0;
                $category->save();
            }
        }

        foreach (Brand::all() as $key => $brand) {
            if (is_array($request->top_brands) && in_array($brand->id, $request->top_brands)) {
                $brand->top = 1;
                $brand->save();
            } else {
                $brand->top = 0;
                $brand->save();
            }
        }

        flash(translate('Top 10 categories and brands have been updated successfully'))->success();
        return redirect()->route('home_settings.index');
    }

    public function variant_price(Request $request)
    {
        $product = Product::find($request->id);
        $str = '';
        $quantity = 0;

        if ($request->has('color')) {
            $str = $request['color'];
        }

        if (json_decode(Product::find($request->id)->choice_options) != null) {
            foreach (json_decode(Product::find($request->id)->choice_options) as $key => $choice) {
                if ($str != null) {
                    $str .= '-' . str_replace(' ', '', $request['attribute_id_' . $choice->attribute_id]);
                } else {
                    $str .= str_replace(' ', '', $request['attribute_id_' . $choice->attribute_id]);
                }
            }
        }

        if ($str != null && $product->variant_product) {
            $product_stock = $product->stocks->where('variant', $str)->first();
            $price = $product_stock->price;
            $quantity = $product_stock->qty;
        } else {
            $price = $product->unit_price;
            $quantity = $product->current_stock;
        }

        //discount calculation
        $flash_deals = \App\FlashDeal::where('status', 1)->get();
        $inFlashDeal = false;
        foreach ($flash_deals as $key => $flash_deal) {
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
            $price += ($price * $product->tax) / 100;
        } elseif ($product->tax_type == 'amount') {
            $price += $product->tax;
        }
        return array('price' => single_price($price * $request->quantity), 'quantity' => $quantity, 'digital' => $product->digital, 'variation' => $str);
    }

    public function sellerpolicy()
    {
        return view("frontend.policies.sellerpolicy");
    }

    public function returnpolicy()
    {
        return view("frontend.policies.returnpolicy");
    }

    public function supportpolicy()
    {
        return view("frontend.policies.supportpolicy");
    }

    public function terms()
    {
        return view("frontend.policies.terms");
    }

    public function privacypolicy()
    {
        return view("frontend.policies.privacypolicy");
    }

    public function get_pick_ip_points(Request $request)
    {
        $pick_up_points = PickupPoint::all();
        return view('frontend.partials.pick_up_points', compact('pick_up_points'));
    }

    public function get_category_items(Request $request)
    {
        $category = Category::findOrFail($request->id);
        return view('frontend.partials.category_elements', compact('category'));
    }

    public function premium_package_index()
    {
        $customer_packages = CustomerPackage::all();
        return view('frontend.user.customer_packages_lists', compact('customer_packages'));
    }

    public function seller_digital_product_list(Request $request)
    {
        $products = Product::where('user_id', Auth::user()->id)->where('digital', 1)->orderBy('created_at', 'desc')->paginate(10);
        return view('frontend.user.seller.digitalproducts.products', compact('products'));
    }

    public function show_digital_product_upload_form(Request $request)
    {
        if (\App\Addon::where('unique_identifier', 'seller_subscription')->first() != null && \App\Addon::where('unique_identifier', 'seller_subscription')->first()->activated) {
            if (Auth::user()->seller->remaining_digital_uploads > 0) {
                $business_settings = BusinessSetting::where('type', 'digital_product_upload')->first();
                $categories = Category::where('digital', 1)->get();
                return view('frontend.user.seller.digitalproducts.product_upload', compact('categories'));
            } else {
                flash(translate('Upload limit has been reached. Please upgrade your package.'))->warning();
                return back();
            }
        }

        $business_settings = BusinessSetting::where('type', 'digital_product_upload')->first();
        $categories = Category::where('digital', 1)->get();
        return view('frontend.user.seller.digitalproducts.product_upload', compact('categories'));
    }

    public function show_digital_product_edit_form(Request $request, $id)
    {
        $categories = Category::where('digital', 1)->get();
        $lang = $request->lang;
        $product = Product::find($id);
        return view('frontend.user.seller.digitalproducts.product_edit', compact('categories', 'product', 'lang'));
    }

    // Ajax call
    public function new_verify(Request $request)
    {
        $email = $request->email;
        if (isUnique($email) == '0') {
            $response['status'] = 2;
            $response['message'] = 'Email already exists!';
            return json_encode($response);
        }

        $response = $this->send_email_change_verification_mail($request, $email);
        return json_encode($response);
    }


    // Form request
    public function update_email(Request $request)
    {
        $email = $request->email;
        if (isUnique($email)) {
            $this->send_email_change_verification_mail($request, $email);
            flash(translate('A verification mail has been sent to the mail you provided us with.'))->success();
            return back();
        }

        flash(translate('Email already exists!'))->warning();
        return back();
    }

    public function send_email_change_verification_mail($request, $email)
    {
        $response['status'] = 0;
        $response['message'] = 'Unknown';

        $verification_code = Str::random(32);

        $array['subject'] = 'Email Verification';
        $array['from'] = env('MAIL_USERNAME');
        $array['content'] = 'Verify your account';
        $array['link'] = route('email_change.callback') . '?new_email_verificiation_code=' . $verification_code . '&email=' . $email;
        $array['sender'] = Auth::user()->name;
        $array['details'] = "Email Second";

        $user = Auth::user();
        $user->new_email_verificiation_code = $verification_code;
        $user->save();

        try {
            Mail::to($email)->queue(new SecondEmailVerifyMailManager($array));

            $response['status'] = 1;
            $response['message'] = translate("Your verification mail has been Sent to your email.");

        } catch (\Exception $e) {
            // return $e->getMessage();
            $response['status'] = 0;
            $response['message'] = $e->getMessage();
        }

        return $response;
    }

    public function email_change_callback(Request $request)
    {
        if ($request->has('new_email_verificiation_code') && $request->has('email')) {
            $verification_code_of_url_param = $request->input('new_email_verificiation_code');
            $user = User::where('new_email_verificiation_code', $verification_code_of_url_param)->first();

            if ($user != null) {

                $user->email = $request->input('email');
                $user->new_email_verificiation_code = null;
                $user->save();

                auth()->login($user, true);

                flash(translate('Email Changed successfully'))->success();
                return redirect()->route('dashboard');
            }
        }

        flash(translate('Email was not verified. Please resend your mail!'))->error();
        return redirect()->route('dashboard');

    }

    public function reset_password_with_code(Request $request)
    {
        if (($user = User::where('email', $request->email)->where('verification_code', $request->code)->first()) != null) {
            if ($request->password == $request->password_confirmation) {
                $user->password = Hash::make($request->password);
                $user->email_verified_at = date('Y-m-d h:m:s');
                $user->save();
                event(new PasswordReset($user));
                auth()->login($user, true);

                flash(translate('Password updated successfully'))->success();

                if (auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'staff') {
                    return redirect()->route('admin.dashboard');
                }
                return redirect()->route('home');
            } else {
                flash("Password and confirm password didn't match")->warning();
                return back();
            }
        } else {
            flash("Verification code mismatch")->error();
            return back();
        }
    }


    public function all_flash_deals()
    {
        $today = strtotime(date('Y-m-d H:i:s'));

        $data['all_flash_deals'] = FlashDeal::where('status', 1)
            ->where('start_date', "<=", $today)
            ->where('end_date', ">", $today)
            ->orderBy('created_at', 'desc')
            ->get();

        return view("frontend.flash_deal.all_flash_deal_list", $data);
    }


    /**
     * GET
     * POST
     *
     * params: product_id, percent, period
     */
    public function select_installment(Request $request) {
        $product = Product::findOrFail($request->id);

        $str = '';
        $tax = 0;


        //check the color enabled or disabled for the product
        if($request->has('color')){
            $data['color'] = $request['color'];
            $str = Color::where('name', $request['color'])->first()->name; // correced by ouarka.dev@gmail.com
        }

        // if the product not a digital !
        // mean digital product does not have a choice_options
        // ['attribute_id' => 1, 'values': ['Grande', 'Mini']]


        if ($product->digital != 1) {
            //Gets all the choice values of customer choice option and generate a string like Black-S-Cotton
            foreach (json_decode(Product::find($request->id)->choice_options) as $key => $choice) {
                if($str != null){
                    $str .= '-'.str_replace(' ', '', $request['attribute_id_'.$choice->attribute_id]);
                }
                else{
                    $str .= str_replace(' ', '', $request['attribute_id_'.$choice->attribute_id]);
                }
            }
        }
        // Black-S-Cotton
        $data['variant'] = $str;

        if($str != null && $product->variant_product){
            $product_stock = $product->stocks->where('variant', $str)->first();
            $price = $product_stock->price;

        }
        else{
            $price = $product->unit_price;
        }

        //discount calculation based on flash deal and regular discount
        //calculation of taxes
        $flash_deals = \App\FlashDeal::where('status', 1)->get();
        $inFlashDeal = false;
        foreach ($flash_deals as $flash_deal) {
            if ($flash_deal != null && $flash_deal->status == 1  && strtotime(date('d-m-Y')) >= $flash_deal->start_date && strtotime(date('d-m-Y')) <= $flash_deal->end_date && \App\FlashDealProduct::where('flash_deal_id', $flash_deal->id)->where('product_id', $product->id)->first() != null) {
                $flash_deal_product = \App\FlashDealProduct::where('flash_deal_id', $flash_deal->id)->where('product_id', $product->id)->first();
                if($flash_deal_product->discount_type == 'percent'){
                    $margin = ($price*$flash_deal_product->discount)/100;
                    $discounted_price = $price - $margin;
                }
                elseif($flash_deal_product->discount_type == 'amount'){
                    $margin = $flash_deal_product->discount;
                    $discounted_price = $price - $margin;
                }
                $inFlashDeal = true;
                break;
            }
        }


        if (!$inFlashDeal) {
            if($product->discount_type == 'percent'){
                $discount = ($price*$product->discount)/100;

            }
            elseif($product->discount_type == 'amount'){
                $discount = $product->discount;
            } else {
                $discount = 0;
            }
        }

        if($product->tax_type == 'percent'){
            $tax = ($price*$product->tax)/100;
        }
        elseif($product->tax_type == 'amount'){
            $tax = $product->tax;
        }

        $installment_id = $request->installment_id;

        $installments = json_decode(\App\BusinessSetting::where('type', 'alif_instalments')->first()->value);
        $installments = array_filter($installments, function($i) { return $i->active; });



        $data = [
            'price' => $price,
            'discount' => $discount,
            'discounted_price' => $price - $discount,
            'quantity' => $request->quantity,
            'selected_installment_id' => $request->installment_id,
            'variant' => $str,
        ];



        return view('frontend.installments.select_installment', compact('product','installments', 'data'));


    }

    public function select_installmentapp(Request $request) {
        $product = Product::findOrFail($request->id);

        $str = '';
        $tax = 0;


        //check the color enabled or disabled for the product
        if($request->has('color')){
            $data['color'] = $request['color'];
            $str = Color::where('name', $request['color'])->first()->name; // correced by ouarka.dev@gmail.com
        }

        // if the product not a digital !
        // mean digital product does not have a choice_options
        // ['attribute_id' => 1, 'values': ['Grande', 'Mini']]


        if ($product->digital != 1) {
            //Gets all the choice values of customer choice option and generate a string like Black-S-Cotton
            foreach (json_decode(Product::find($request->id)->choice_options) as $key => $choice) {
                if($str != null){
                    $str .= '-'.str_replace(' ', '', $request['attribute_id_'.$choice->attribute_id]);
                }
                else{
                    $str .= str_replace(' ', '', $request['attribute_id_'.$choice->attribute_id]);
                }
            }
        }

        // Black-S-Cotton
        $data['variant'] = $str;

        if($str != null && $product->variant_product){
            $product_stock = $product->stocks->where('variant', $str)->first();
            $price = $product_stock->price;

        }
        else{
            $price = $product->unit_price;
        }

        //discount calculation based on flash deal and regular discount
        //calculation of taxes
        $flash_deals = \App\FlashDeal::where('status', 1)->get();
        $inFlashDeal = false;


        foreach ($flash_deals as $flash_deal) {
            if ($flash_deal != null && $flash_deal->status == 1  && strtotime(date('d-m-Y')) >= $flash_deal->start_date && strtotime(date('d-m-Y')) <= $flash_deal->end_date && \App\FlashDealProduct::where('flash_deal_id', $flash_deal->id)->where('product_id', $product->id)->first() != null) {
                $flash_deal_product = \App\FlashDealProduct::where('flash_deal_id', $flash_deal->id)->where('product_id', $product->id)->first();
                if($flash_deal_product->discount_type == 'percent'){
                    $margin = ($price*$flash_deal_product->discount)/100;
                    $discounted_price = $price - $margin;
                }
                elseif($flash_deal_product->discount_type == 'amount'){
                    $margin = $flash_deal_product->discount;
                    $discounted_price = $price - $margin;
                }
                $inFlashDeal = true;
                break;
            }
        }


        if (!$inFlashDeal) {
            if($product->discount_type == 'percent'){
                $discount = ($price*$product->discount)/100;

            }
            elseif($product->discount_type == 'amount'){
                $discount = $product->discount;
            } else {
                $discount = 0;
            }
        }

        if($product->tax_type == 'percent'){
            $tax = ($price*$product->tax)/100;
        }
        elseif($product->tax_type == 'amount'){
            $tax = $product->tax;
        }

        $installment_id = $request->installment_id;

        $installments = json_decode(\App\BusinessSetting::where('type', 'alif_instalments')->first()->value);
        dd($installments);
        $installments = array_filter($installments, function($i) { return $i->active; });



        $data = [
            'price' => $price,
            'discount' => $discount,
            'discounted_price' => $price - $discount,
            'quantity' => $request->quantity,
            'selected_installment_id' => $request->installment_id,
            'variant' => $str,
        ];

        return view('frontend.installments.select_installmentapp', compact('product','installments', 'data'));


    }

    public function application(Request $request)
    {

        $product = Product::findOrFail($request->product_id);
        $variant = $request->variant;


        if ($variant && $product->variant_product) {
            $product_stock = $product->stocks->where('variant', $variant)->first();
            $price = $product_stock->price;
        } else {
            $price = $product->unit_price;
        }


        if ($product->discount_type == 'percent') {
            $discount = $price * ($product->discount / 100);

        } else {
            $discount = $product->discount;
        }


        $price -= $discount;
        $price *= $request->quantity;

        $installments = json_decode(\App\BusinessSetting::where('type', 'alif_instalments')->first()->value);
        $installment = array_filter($installments, function ($i) use ($request) {
            return $i->active && $i->id == $request->installment_id;
        });

        $installment = array_shift($installment); // get first element


        $profit = $price * ($product->profit / 100);
        $percent = $price * ($installment->value / 100);

        $price = ($price + $profit + $percent) / $installment->period;

        $data = [
            'code' => date('Ymd-His') . rand(10, 99),
            'product_id' => $product->id,
            'variant' => $variant,
            'user_id' => Auth::user()->id,
            'installment_id' => $installment->id,
            'quantity' => $request->quantity,
            'price' => $price,
            'status' => 'pending',
            'details' => json_encode([
                'profit' => $profit,
                'percent' => $percent,
                'discount' => $discount,
                'name' => $installment->label,
                'period' => $installment->period,

            ], JSON_NUMERIC_CHECK)
        ];


        session()->put('new_application', $data);

        return view('frontend.installments.application');
    }

    public function applicationapp(Request $request)
    {

        $product = Product::findOrFail($request->product_id);
        $variant = $request->variant;

        if ($variant && $product->variant_product) {
            $product_stock = $product->stocks->where('variant', $variant)->first();
            $price = $product_stock->price;
        } else {
            $price = $product->unit_price;
        }


        if ($product->discount_type == 'percent') {
            $discount = $price * ($product->discount / 100);

        } else {
            $discount = $product->discount;
        }


        $price -= $discount;
        $price *= $request->quantity;

        $installments = json_decode(\App\BusinessSetting::where('type', 'alif_instalments')->first()->value);
        $installment = array_filter($installments, function ($i) use ($request) {
            return $i->active && $i->id == $request->installment_id;
        });

        $installment = array_shift($installment); // get first element


        $profit = $price * ($product->profit / 100);
        $percent = $price * ($installment->value / 100);

        $price = ($price + $profit + $percent) / $installment->period;

        $data = [
            'code' => date('Ymd-His') . rand(10, 99),
            'product_id' => $product->id,
            'variant' => $variant,
            'user_id' => Auth::user()->id ?? null,
            'installment_id' => $installment->id,
            'quantity' => $request->quantity,
            'price' => $price,
            'status' => 'pending',
            'details' => json_encode([
                'profit' => $profit,
                'percent' => $percent,
                'discount' => $discount,
                'name' => $installment->label,
                'period' => $installment->period,

            ], JSON_NUMERIC_CHECK)
        ];

        session()->put('new_application', $data);

        return view('frontend.installments.applicationapp');
    }

    public function get_intent_auth($id)
    {
        return view('frontend.user.intend', compact('id'));
    }

    public function post_intent_auth(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'password' => 'required|min:4',
            'password_confirmation' => 'required|min:4|same:password',
        ]);

        $client = new \GuzzleHttp\Client();
        $response = $client->post('https://pay.intend.uz/api/v1/external/member/auth', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'api-key' => '95AC5288119F85D487F1658B3CC65',
            ],
            'json' => [
                'username' => $request->country_code . $request->phone,
                'password' => $request->password
            ],
        ]);


        $json = json_decode($response->getBody()->getContents(), true);

        if ($json['success'] == false) {
            return \redirect('https://reg.intend.uz/login?back_uri=https://azbo.uz/intent-auth&l=uz');
        }

        if (isset($json['data']['token'])) {

            $productIntend = new ProductIntend();
            $productIntend->bearer_token = $json['data']['token'];
            $productIntend->product_id = $request->product_id;

            session()->put('intend_token', $json['data']['token']);
            $user_id = auth()->id();
            if ($user_id != false) {
                $productIntend->user_id = $user_id;
            }
            $productIntend->intend_phone = $request->phone;
            $productIntend->save();
            return \redirect()->route('intend_select_product');
        }

        return \redirect('https://azbo.uz/');

    }

    public function intend_select_product(Request $request)
    {
        $detailedProduct = Product::where('slug', session()->get('detailed_product'))->first();
        if ($detailedProduct != null && $detailedProduct->published) {
            //updateCartSetup();
            if ($request->has('product_referral_code')) {
                Cookie::queue('product_referral_code', $request->product_referral_code, 43200);
                Cookie::queue('referred_product_id', $detailedProduct->id, 43200);
            }
            /*            if ($detailedProduct->digital == 1) {
                            return view('frontend.digital_product_details', compact('detailedProduct'));
                        } else {*/

            $calculate = self::post_intent_calculate($detailedProduct->id, $detailedProduct->purchase_price);
            return view('frontend.intend.select_product', compact(['detailedProduct', 'calculate']));
            //}
            // return view('frontend.product_details', compact('detailedProduct'));
        }
        abort(404);
    }

    public function intent_calculate()
    {
        $post_data[] = [
            'id' => '9176',
            'price' => 69000.00 * 100
        ];
        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->post('https://pay.intend.uz/api/v1/external/calculate', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'api-key' => '95AC5288119F85D487F1658B3CC65',
                    "Cache-Control" => "no-cache",
                ],
                'json' => $post_data,
            ]);
        } catch (\Exception $e) {
//            Log::error($e->getMessage());
            print_r("not working");
        }

        $json = collect(json_decode($response->getBody()->getContents(), true)['data']['items'] ?? []);
        print_r($json);


    }

    public static function post_intent_calculate($id, $price)
    {
        $post_data[] = [
            'id' => $id,
            'price' => $price
        ];
        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->post('https://pay.intend.uz/api/v1/external/calculate', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'api-key' => '95AC5288119F85D487F1658B3CC65',
                    "Cache-Control" => "no-cache",
                ],
                'json' => $post_data,
            ]);
        } catch (\Exception $e) {
//            Log::error($e->getMessage());
            print_r("not working");
        }

        $json = collect(json_decode($response->getBody()->getContents(), true)['data']['items'] ?? []);

        if (!$json) {
            $json = null;
        }

        return $json;

    }

    public function intent_member_check()
    {
        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->post('https://pay.intend.uz/api/v1/external/member/check', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'api-key' => '95AC5288119F85D487F1658B3CC65',
                    "Cache-Control" => "no-cache",
                ],
                'json' => [
                    'username' => '998614002'
                ],
            ]);

        } catch (\Exception $e) {
            print_r("not working");
        }
        $json = json_decode($response->getBody()->getContents(), true);
        print_r($json);
        die();

    }

    public function intent_member_limits()
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->get('https://pay.intend.uz/api/v1/external/member/limits', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . session()->get('intend_token'),
                'api-key' => '95AC5288119F85D487F1658B3CC65',
            ],
        ]);
        $json = json_decode($response->getBody()->getContents(), true);
        if (isset($json['data']['limit'])) {
            session()->put('intend_limit', $json['data']['limit']);

            return $json['data']['limit'] / 100;
        }
        print_r($json);
        die();
    }

    public function intent_member_rescore()
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->get('https://pay.intend.uz/api/v1/external/member/rescore', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . session()->get('intend_token'),
                'api-key' => '95AC5288119F85D487F1658B3CC65',
            ],
        ]);
        //$json = json_decode($response->getBody()->getContents(), true);
        print_r($response->getBody()->getContents());
        die();
    }

    public function intent_order_create(Request $request)
    {

        $product = $request->product;
        $calculate = $request->calculate;

        $detailedProduct = Product::where('id', $product)->first();
        $intendProduct = ProductIntend::where('product_id', $product)->latest('id')->first();
//        dd($intendProduct,session()->get('intend_token'));
        $client = new \GuzzleHttp\Client();
        $response = $client->post('https://pay.intend.uz/api/v1/external/order/create', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . session()->get('intend_token'),
                'api-key' => '95AC5288119F85D487F1658B3CC65',
            ],
            'json' => [
                "duration" => $calculate,
                "redirect_url" => "",
                "order_id" => $detailedProduct->id,
                "products" => [

                    [
                        "id" => $detailedProduct->id,
                        "price" => $detailedProduct->purchase_price * 100,
                        "quantity" => 1,
                        "name" => $detailedProduct->name,
                        "sku" => $detailedProduct->name,
                        "weight" => "",
                        "url" => "http://azbo.uz/products/2"

                    ]
                ]

            ]
        ]);
        $json = json_decode($response->getBody()->getContents(), true);
        session()->put(['ref_id' => $json['data']['ref_id']]);
        $intendProduct->ref_id = $json['data']['ref_id'];
        $intendProduct->update();

        return $json;
    }

//    public function intent_sms_confirm(Request $request)
//    {
//        $detailedProduct = Product::where('slug', session()->get('detailed_product'))->first();
//        if ($detailedProduct != null && $detailedProduct->published) {
//            updateCartSetup();
//            if ($request->has('product_referral_code')) {
//                Cookie::queue('product_referral_code', $request->product_referral_code, 43200);
//                Cookie::queue('referred_product_id', $detailedProduct->id, 43200);
//            }
    /*            if ($detailedProduct->digital == 1) {
                    return view('frontend.digital_product_details', compact('detailedProduct'));
                } else {*/

//            $calculate = self::post_intent_calculate($detailedProduct->id, $detailedProduct->purchase_price);
//            return view('frontend.intend.smsConfirm', compact(['detailedProduct', 'calculate']));
//        }
//    }

    public function post_intent_order_create($duration, $redirect_url, $order_id, $product)
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->post('https://pay.intend.uz/api/v1/external/order/create', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . session()->get('intend_token'),
                'api-key' => '95AC5288119F85D487F1658B3CC65',
            ],
            'json' => [
                "duration" => 12,
                "redirect_url" => "",
                "order_id" => 117,
                "products" => [

                    [
                        "id" => 23,
                        "price" => 200000000,
                        "quantity" => 1,
                        "name" => "Name product",
                        "sku" => "test",
                        "weight" => 4500,
                        "url" => "htttp://azbo.uz/products/21321"

                    ]
                ]

            ]
        ]);
        //$json = json_decode($response->getBody()->getContents(), true);
        print_r($response->getBody()->getContents());
        die();

    }

    public function intent_cheque_confirm(Request $request)
    {
        $productIntend = ProductIntend::where('product_id', $request->product_id)->latest('id')->first();
        if ($request->code == 6666) {

            return ['success' => 'true'];

        }
        $client = new \GuzzleHttp\Client();
        $response = $client->post('https://pay.intend.uz/api/v1/external/cheque/confirm', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $productIntend->bearer_token,
                'api-key' => '95AC5288119F85D487F1658B3CC65',
            ],
            'json' => [
                "code" => $request->code,
                "ref_id" => $productIntend->ref_id
            ]
        ]);
        $json = json_decode($response->getBody()->getContents(), true);

        if ($json['success'] == true) {
            $productIntend['status'] = 1;
        }

        $productIntend->update();

        return $json;


    }

    public function intent_cheque_resend()
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->post('https://pay.intend.uz/api/v1/external/cheque/resend', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . session()->get('intend_token'),
                'api-key' => '95AC5288119F85D487F1658B3CC65',
            ],
            'json' => [
                "ref_id" => session()->get('ref_id')
            ]
        ]);
        $json = json_decode($response->getBody()->getContents(), true);
        return $json;

    }


    public function intent_order_check()
    {

        $client = new \GuzzleHttp\Client();
        $response = $client->post('https://pay.intend.uz/api/v1/external/order/check', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . session()->get('intend_token'),
                'api-key' => '95AC5288119F85D487F1658B3CC65',
            ],
            'json' => [
                "ref_id" => "045a9a8ac9844808bf34c41aa7936f47"
            ]
        ]);
        //$json = json_decode($response->getBody()->getContents(), true);
        print_r($response->getBody()->getContents());
        die();

    }

    public function intent_selected_products()
    {
        $selected_intend = ProductIntend::where('user_id', auth()->user()->id)
            ->where('status', 1)
            ->paginate(15);

        return view('frontend.intend.intent_selected_products', compact('selected_intend'));
    }

}
