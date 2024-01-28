<?php

namespace App\Http\Controllers;

use App\Address;
use App\BusinessSetting;
use App\Category;
use App\Coupon;
use App\CouponUsage;
use App\Models\Custom\OrderDetail;
use App\Models\Product;
use App\Order;
use App\OrderSmsCode;
use App\Tg\TgContent;
use App\Utility\PayfastUtility;
use App\Utility\PayhereUtility;
use Auth;
use Illuminate\Http\Request;
use Session;


class CheckoutController extends Controller
{
    public function __construct()
    {
        //
    }

    public function checkout(Request $request)
    {
        if ($request->payment_option != null) {
            $orderController = new OrderController;
            $orderController->store($request);

            $request->session()->put('payment_type', 'cart_payment');

            if ($request->session()->get('order_id') != null) {
                if ($request->payment_option == 'paysys') {
                    $paysys = new PaysysController;
                    return $paysys->getCheckout();
                } elseif ($request->payment_option == 'paycom') {
                    $paycom = new PaycomController;
                    $array = $paycom->getCheckout();
                    return view('frontend.payment.paycom_form', ['array' => $array]);
                } elseif ($request->payment_option == 'paypal') {
                    $paypal = new PaypalController;
                    return $paypal->getCheckout();
                } elseif ($request->payment_option == 'stripe') {
                    $stripe = new StripePaymentController;
                    return $stripe->stripe();
                } elseif ($request->payment_option == 'sslcommerz') {
                    $sslcommerz = new PublicSslCommerzPaymentController;
                    return $sslcommerz->index($request);
                } elseif ($request->payment_option == 'instamojo') {
                    $instamojo = new InstamojoController;
                    return $instamojo->pay($request);
                } elseif ($request->payment_option == 'razorpay') {
                    $razorpay = new RazorpayController;
                    return $razorpay->payWithRazorpay($request);
                } elseif ($request->payment_option == 'paystack') {
                    $paystack = new PaystackController;
                    return $paystack->redirectToGateway($request);
                } elseif ($request->payment_option == 'voguepay') {
                    $voguePay = new VoguePayController;
                    return $voguePay->customer_showForm();
                } elseif ($request->payment_option == 'payhere') {
                    $order = Order::findOrFail($request->session()->get('order_id'));

                    $order_id = $order->id;
                    $amount = $order->grand_total;
                    $first_name = json_decode($order->shipping_address)->name;
                    $last_name = 'X';
                    $phone = json_decode($order->shipping_address)->phone;
                    $email = json_decode($order->shipping_address)->email;
                    $address = json_decode($order->shipping_address)->address;
                    $city = json_decode($order->shipping_address)->city;

                    return PayhereUtility::create_checkout_form(
                      $order_id,
                      $amount,
                      $first_name,
                      $last_name,
                      $phone,
                      $email,
                      $address,
                      $city
                    );
                } elseif ($request->payment_option == 'payfast') {
                    $order = Order::findOrFail($request->session()->get('order_id'));

                    $order_id = $order->id;
                    $amount = $order->grand_total;

                    return PayfastUtility::create_checkout_form($order_id, $amount);
                } else {
                    if ($request->payment_option == 'ngenius') {
                        $ngenius = new NgeniusController();
                        return $ngenius->pay();
                    } else {
                        if ($request->payment_option == 'iyzico') {
                            $iyzico = new IyzicoController();
                            return $iyzico->pay();
                        } else {
                            if ($request->payment_option == 'nagad') {
                                $nagad = new NagadController;
                                return $nagad->getSession();
                            } else {
                                if ($request->payment_option == 'bkash') {
                                    $bkash = new BkashController;
                                    return $bkash->pay();
                                } else {
                                    if ($request->payment_option == 'flutterwave') {
                                        $flutterwave = new FlutterwaveController();
                                        return $flutterwave->pay();
                                    } else {
                                        if ($request->payment_option == 'mpesa') {
                                            $mpesa = new MpesaController();
                                            return $mpesa->pay();
                                        } elseif ($request->payment_option == 'paytm') {
                                            $paytm = new PaytmController;
                                            return $paytm->index();
                                        } elseif ($request->payment_option == 'cash_on_delivery') {
                                            $request->session()->put(
                                              'cart',
                                              Session::get('cart')->where('owner_id', '!=', Session::get('owner_id'))
                                            );
                                            $request->session()->forget('owner_id');
                                            $request->session()->forget('delivery_info');
                                            $request->session()->forget('coupon_id');
                                            $request->session()->forget('coupon_discount');

                                            flash(translate("Your order has been placed successfully"))->success();
                                            return redirect()->route('order_confirmed');
                                        } elseif ($request->payment_option == 'wallet') {
                                            $user = Auth::user();
                                            $order = Order::findOrFail($request->session()->get('order_id'));
                                            if ($user->balance >= $order->grand_total) {
                                                $user->balance -= $order->grand_total;
                                                $user->save();
                                                return $this->checkout_done($request->session()->get('order_id'), null);
                                            }
                                        } else {
                                            $order = Order::findOrFail($request->session()->get('order_id'));
                                            $order->manual_payment = 1;
                                            $order->save();

                                            $request->session()->put(
                                              'cart',
                                              Session::get('cart')->where('owner_id', '!=', Session::get('owner_id'))
                                            );
                                            $request->session()->forget('owner_id');
                                            $request->session()->forget('delivery_info');
                                            $request->session()->forget('coupon_id');
                                            $request->session()->forget('coupon_discount');

                                            flash(
                                              translate(
                                                'Your order has been placed successfully. Please submit payment information from purchase history'
                                              )
                                            )->success();
                                            return redirect()->route('order_confirmed');
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } else {
            flash(translate('Select Payment Option.'))->warning();
            return back();
        }
    }

    public function checkout_done($order_id, $payment)
    {
        $order = Order::findOrFail($order_id);
        $order->payment_status = 'paid';
        $order->payment_details = $payment;
        $order->save();

        if (\App\Addon::where('unique_identifier', 'affiliate_system')->first() != null && \App\Addon::where(
            'unique_identifier',
            'affiliate_system'
          )->first()->activated) {
            $affiliateController = new AffiliateController;
            $affiliateController->processAffiliatePoints($order);
        }

        if (\App\Addon::where('unique_identifier', 'club_point')->first() != null && \App\Addon::where(
            'unique_identifier',
            'club_point'
          )->first()->activated) {
            if (Auth::check()) {
                $clubpointController = new ClubPointController;
                $clubpointController->processClubPoints($order);
            }
        }
        if (\App\Addon::where('unique_identifier', 'seller_subscription')->first() == null || !\App\Addon::where(
            'unique_identifier',
            'seller_subscription'
          )->first()->activated) {
            if (BusinessSetting::where('type', 'category_wise_commission')->first()->value != 1) {
                $commission_percentage = BusinessSetting::where('type', 'vendor_commission')->first()->value;
                foreach ($order->orderDetails as $key => $orderDetail) {
                    $orderDetail->payment_status = 'paid';
                    $orderDetail->save();
                    if ($orderDetail->product->user->user_type == 'seller') {
                        $seller = $orderDetail->product->user->seller;
                        $seller->admin_to_pay = $seller->admin_to_pay + ($orderDetail->price * (100 - $commission_percentage)) / 100 + $orderDetail->tax + $orderDetail->shipping_cost;
                        $seller->save();
                    }
                }
            } else {
                foreach ($order->orderDetails as $key => $orderDetail) {
                    $orderDetail->payment_status = 'paid';
                    $orderDetail->save();
                    if ($orderDetail->product->user->user_type == 'seller') {
                        $commission_percentage = $orderDetail->product->category->commision_rate;
                        $seller = $orderDetail->product->user->seller;
                        $seller->admin_to_pay = $seller->admin_to_pay + ($orderDetail->price * (100 - $commission_percentage)) / 100 + $orderDetail->tax + $orderDetail->shipping_cost;
                        $seller->save();
                    }
                }
            }
        } else {
            foreach ($order->orderDetails as $key => $orderDetail) {
                $orderDetail->payment_status = 'paid';
                $orderDetail->save();
                if ($orderDetail->product->user->user_type == 'seller') {
                    $seller = $orderDetail->product->user->seller;
                    $seller->admin_to_pay = $seller->admin_to_pay + $orderDetail->price + $orderDetail->tax + $orderDetail->shipping_cost;
                    $seller->save();
                }
            }
        }

        $order->commission_calculated = 1;
        $order->save();

        if (Session::has('cart')) {
            Session::put('cart', Session::get('cart')->where('owner_id', '!=', Session::get('owner_id')));
        }
        Session::forget('owner_id');
        Session::forget('payment_type');
        Session::forget('delivery_info');
        Session::forget('coupon_id');
        Session::forget('coupon_discount');


        flash(translate('Payment completed'))->success();
        return view('frontend.order_confirmed', compact('order'));
    }

    public function get_shipping_info(Request $request)
    {
        if (Session::has('cart') && count(Session::get('cart')) > 0) {
            $categories = Category::all();
            return view('frontend.shipping_info', compact('categories'));
        }
        flash(translate('Your cart is empty'))->success();
        return back();
    }

    public function store_shipping_info(Request $request)
    {
        if (Auth::check()) {
            if ($request->address_id == null) {
                flash(translate("Please add shipping address"))->warning();
                return back();
            }
            $address = Address::findOrFail($request->address_id);
            $data['name'] = Auth::user()->name;
            $data['email'] = Auth::user()->email;
            $data['address'] = $address->address;
            $data['country'] = $address->country;
            $data['city'] = $address->city;
            $data['postal_code'] = $address->postal_code;
            $data['phone'] = $address->phone;
            $data['checkout_type'] = $request->checkout_type;
        } else {
            $data['name'] = $request->name;
            $data['email'] = $request->email;
            $data['address'] = $request->address;
            $data['country'] = $request->country;
            $data['city'] = $request->city;
            $data['postal_code'] = $request->postal_code;
            $data['phone'] = $request->phone;
            $data['checkout_type'] = $request->checkout_type;
        }

        $shipping_info = $data;
        $request->session()->put('shipping_info', $shipping_info);

        $subtotal = 0;
        $tax = 0;
        $shipping = 0;
        foreach (Session::get('cart') as $key => $cartItem) {
            $subtotal += $cartItem['price'] * $cartItem['quantity'];
            $tax += $cartItem['tax'] * $cartItem['quantity'];
            $shipping += $cartItem['shipping'] * $cartItem['quantity'];
        }

        $total = $subtotal + $tax + $shipping;

        if (Session::has('coupon_discount')) {
            $total -= Session::get('coupon_discount');
        }


        return view('frontend.delivery_info');
        // return view('frontend.payment_select', compact('total'));
    }

    public function store_delivery_info(Request $request)
    {
        $request->session()->put('owner_id', $request->owner_id);

        if (Session::has('cart') && count(Session::get('cart')) > 0) {
            $cart = $request->session()->get('cart', collect([]));
            $cart = $cart->map(function ($object, $key) use ($request) {
                if (\App\Product::find($object['id'])->user_id == $request->owner_id) {
                    if ($request['shipping_type_' . $request->owner_id] == 'pickup_point') {
                        $object['shipping_type'] = 'pickup_point';
                        $object['pickup_point'] = $request['pickup_point_id_' . $request->owner_id];
                    } else {
                        $object['shipping_type'] = 'home_delivery';
                    }
                }
                return $object;
            });


            $request->session()->put('cart', $cart);

            $cart = $cart->map(function ($object, $key) use ($request) {
                if (\App\Product::find($object['id'])->user_id == $request->owner_id) {
                    if ($object['shipping_type'] == 'home_delivery') {
                        $object['shipping'] = getShippingCost($key);
                    } else {
                        $object['shipping'] = 0;
                    }
                } else {
                    $object['shipping'] = 0;
                }
                return $object;
            });

            $request->session()->put('cart', $cart);

            $subtotal = 0;
            $tax = 0;
            $shipping = 0;
            foreach (Session::get('cart') as $key => $cartItem) {
                $subtotal += $cartItem['price'] * $cartItem['quantity'];
                $tax += $cartItem['tax'] * $cartItem['quantity'];
                $shipping += $cartItem['shipping'] * $cartItem['quantity'];
            }

            $total = $subtotal + $tax + $shipping;

            if (Session::has('coupon_discount')) {
                $total -= Session::get('coupon_discount');
            }

            return view('frontend.payment_select', compact('total'));
        } else {
            flash(translate('Your Cart was empty'))->warning();
            return redirect()->route('home');
        }
    }

    public function get_payment_info(Request $request)
    {
        $subtotal = 0;
        $tax = 0;
        $shipping = 0;
        foreach (Session::get('cart') as $key => $cartItem) {
            $subtotal += $cartItem['price'] * $cartItem['quantity'];
            $tax += $cartItem['tax'] * $cartItem['quantity'];
            $shipping += $cartItem['shipping'] * $cartItem['quantity'];
        }

        $total = $subtotal + $tax + $shipping;

        if (Session::has('coupon_discount')) {
            $total -= Session::get('coupon_discount');
        }


        return view('frontend.payment_select', compact('total'));
    }

    public function apply_coupon_code(Request $request)
    {
        //dd($request->all());
        $coupon = Coupon::where('code', $request->code)->first();

        if ($coupon != null) {
            if (strtotime(date('d-m-Y')) >= $coupon->start_date && strtotime(date('d-m-Y')) <= $coupon->end_date) {
                if (CouponUsage::where('user_id', Auth::user()->id)->where('coupon_id', $coupon->id)->first() == null) {
                    $coupon_details = json_decode($coupon->details);

                    if ($coupon->type == 'cart_base') {
                        $subtotal = 0;
                        $tax = 0;
                        $shipping = 0;
                        foreach (Session::get('cart') as $key => $cartItem) {
                            $subtotal += $cartItem['price'] * $cartItem['quantity'];
                            $tax += $cartItem['tax'] * $cartItem['quantity'];
                            $shipping += $cartItem['shipping'] * $cartItem['quantity'];
                        }
                        $sum = $subtotal + $tax + $shipping;

                        if ($sum >= $coupon_details->min_buy) {
                            if ($coupon->discount_type == 'percent') {
                                $coupon_discount = ($sum * $coupon->discount) / 100;
                                if ($coupon_discount > $coupon_details->max_discount) {
                                    $coupon_discount = $coupon_details->max_discount;
                                }
                            } elseif ($coupon->discount_type == 'amount') {
                                $coupon_discount = $coupon->discount;
                            }
                            $request->session()->put('coupon_id', $coupon->id);
                            $request->session()->put('coupon_discount', $coupon_discount);
                            flash(translate('Coupon has been applied'))->success();
                        }
                    } elseif ($coupon->type == 'product_base') {
                        $coupon_discount = 0;
                        foreach (Session::get('cart') as $key => $cartItem) {
                            foreach ($coupon_details as $key => $coupon_detail) {
                                if ($coupon_detail->product_id == $cartItem['id']) {
                                    if ($coupon->discount_type == 'percent') {
                                        $coupon_discount += $cartItem['price'] * $coupon->discount / 100;
                                    } elseif ($coupon->discount_type == 'amount') {
                                        $coupon_discount += $coupon->discount;
                                    }
                                }
                            }
                        }
                        $request->session()->put('coupon_id', $coupon->id);
                        $request->session()->put('coupon_discount', $coupon_discount);
                        flash(translate('Coupon has been applied'))->success();
                    }
                } else {
                    flash(translate('You already used this coupon!'))->warning();
                }
            } else {
                flash(translate('Coupon expired!'))->warning();
            }
        } else {
            flash(translate('Invalid coupon!'))->warning();
        }
        return back();
    }

    public function remove_coupon_code(Request $request)
    {
        $request->session()->forget('coupon_id');
        $request->session()->forget('coupon_discount');
        return back();
    }

    public function order_confirmed()
    {
        $order = Order::findOrFail(Session::get('order_id'));
        return view('frontend.order_confirmed', compact('order'));
    }

    public function order_preconfirmed()
    {
        $order = Order::findOrFail(Session::get('order_id'));
        return view('frontend.order_preconfirmed', compact('order'));
    }

    public function detail_payment()
    {
        return view('frontend.detail_payment');
    }

    public function detail_send_confirm_sms(Request $request)
    {
        $otpController = new OTPVerificationController;
        $verification_code = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $orderSmsCode = OrderSmsCode::where('phone', $request->phone)->first();
        if ($orderSmsCode) {
            $orderSmsCode->verification_code = $verification_code;
            $orderSmsCode->update();
        } else {
            $orderSmsCode = new OrderSmsCode();
            $orderSmsCode->phone = $request->phone;
            $orderSmsCode->verification_code = $verification_code;
            $orderSmsCode->save();
        }
        $otpController->send_code_order($request->phone, $verification_code);
        return response()->json(
          ['success' => true]
        );
    }

    public function detail_send_verify_sms(Request $request)
    {
        $user = OrderSmsCode::where('phone', $request->phone)->first();
        if ($user) {
            if ($user->verification_code == $request->smsCode) {
                return response()->json(
                  ['success' => 'function successs']
                );
            } else {
                return response()->json(
                  ['error' => 'function error'],
                  404
                );
            }
        }

        return response()->json(
          ['error' => 'function error'],
          404
        );
    }

    public function detail_action(Request $request)
    {
        $request->validate([
          "sublayerName" => 'required',
          "sublayerPhone" => 'required|numeric|min:12',
        ]);

        if (Auth::check()) {
            if ($request->address_id == null) {
                flash(translate("Please add shipping address"))->warning();
                return back();
            }
            $address = Address::findOrFail($request->address_id);
            $data['name'] = Auth::user()->name;
            $data['email'] = Auth::user()->email;
            $data['address'] = $address->address;
            $data['country'] = $address->country;
            $data['city'] = $address->city;
            $data['postal_code'] = $address->postal_code;
            $data['phone'] = $address->phone;
            $data['checkout_type'] = $request->checkout_type;
        } else {
            $data['name'] = $request->name;
            $data['email'] = $request->email;
            $data['address'] = $request->address;
            $data['country'] = $request->country;
            $data['city'] = $request->city;
            $data['postal_code'] = $request->postal_code;
            $data['phone'] = $request->phone;
            $data['checkout_type'] = $request->checkout_type;
        }

        $shipping_info = $data;
        $request->session()->put('shipping_info', $shipping_info);

        $subtotal = 0;
        $tax = 0;
        $shipping = 0;
        foreach (Session::get('cart') as $key => $cartItem) {
            $subtotal += $cartItem['price'] * $cartItem['quantity'];
            $tax += $cartItem['tax'] * $cartItem['quantity'];
            $shipping += $cartItem['shipping'] * $cartItem['quantity'];
        }

        $total = $subtotal + $tax + $shipping;

        if (Session::has('coupon_discount')) {
            $total -= Session::get('coupon_discount');
        }

        $request->session()->put('owner_id', $request->owner_id);

        if (Session::has('cart') && count(Session::get('cart')) > 0) {
            $cart = $request->session()->get('cart', collect([]));
            $cart = $cart->map(function ($object, $key) use ($request) {
                if (\App\Product::find($object['id'])->user_id == $request->owner_id) {
                    if ($request['shipping_type_' . $request->owner_id] == 'pickup_point') {
                        $object['shipping_type'] = 'pickup_point';
                        $object['pickup_point'] = $request['pickup_point_id_' . $request->owner_id];
                    } else {
                        $object['shipping_type'] = 'home_delivery';
                    }
                }
                return $object;
            });


            $request->session()->put('cart', $cart);

            $cart = $cart->map(function ($object, $key) use ($request) {
                if (\App\Product::find($object['id'])->user_id == $request->owner_id) {
                    if ($object['shipping_type'] == 'home_delivery') {
                        $object['shipping'] = getShippingCost($key);
                    } else {
                        $object['shipping'] = 0;
                    }
                } else {
                    $object['shipping'] = 0;
                }
                return $object;
            });

            $request->session()->put('cart', $cart);

            $subtotal = 0;
            $tax = 0;
            $shipping = 0;
            foreach (Session::get('cart') as $key => $cartItem) {
                $subtotal += $cartItem['price'] * $cartItem['quantity'];
                $tax += $cartItem['tax'] * $cartItem['quantity'];
                $shipping += $cartItem['shipping'] * $cartItem['quantity'];
            }

            $total = $subtotal + $tax + $shipping;

            if (Session::has('coupon_discount')) {
                $total -= Session::get('coupon_discount');
            }
            /*begin checkout function*/
            if ($request->payment_option != null) {
                $orderController = new OrderController;
                $orderController->store($request);

                $request->session()->put('payment_type', 'cart_payment');

                if ($request->session()->get('order_id') != null) {
                    if ($request->payment_option == 'paysys') {
                        $paysys = new PaysysController;
                        return $paysys->getCheckout();
                    } elseif ($request->payment_option == 'paycom') {
                        $paycom = new PaycomController;
                        $array = $paycom->getCheckout();
                        return view('frontend.payment.paycom_form', ['array' => $array]);
                    } elseif ($request->payment_option == 'paypal') {
                        $paypal = new PaypalController;
                        return $paypal->getCheckout();
                    } elseif ($request->payment_option == 'stripe') {
                        $stripe = new StripePaymentController;
                        return $stripe->stripe();
                    } elseif ($request->payment_option == 'sslcommerz') {
                        $sslcommerz = new PublicSslCommerzPaymentController;
                        return $sslcommerz->index($request);
                    } elseif ($request->payment_option == 'instamojo') {
                        $instamojo = new InstamojoController;
                        return $instamojo->pay($request);
                    } elseif ($request->payment_option == 'razorpay') {
                        $razorpay = new RazorpayController;
                        return $razorpay->payWithRazorpay($request);
                    } elseif ($request->payment_option == 'paystack') {
                        $paystack = new PaystackController;
                        return $paystack->redirectToGateway($request);
                    } elseif ($request->payment_option == 'voguepay') {
                        $voguePay = new VoguePayController;
                        return $voguePay->customer_showForm();
                    } elseif ($request->payment_option == 'payhere') {
                        $order = Order::findOrFail($request->session()->get('order_id'));

                        $order_id = $order->id;
                        $amount = $order->grand_total;
                        $first_name = json_decode($order->shipping_address)->name;
                        $last_name = 'X';
                        $phone = json_decode($order->shipping_address)->phone;
                        $email = json_decode($order->shipping_address)->email;
                        $address = json_decode($order->shipping_address)->address;
                        $city = json_decode($order->shipping_address)->city;

                        return PayhereUtility::create_checkout_form(
                          $order_id,
                          $amount,
                          $first_name,
                          $last_name,
                          $phone,
                          $email,
                          $address,
                          $city
                        );
                    } elseif ($request->payment_option == 'payfast') {
                        $order = Order::findOrFail($request->session()->get('order_id'));

                        $order_id = $order->id;
                        $amount = $order->grand_total;

                        return PayfastUtility::create_checkout_form($order_id, $amount);
                    } else {
                        if ($request->payment_option == 'ngenius') {
                            $ngenius = new NgeniusController();
                            return $ngenius->pay();
                        } else {
                            if ($request->payment_option == 'iyzico') {
                                $iyzico = new IyzicoController();
                                return $iyzico->pay();
                            } else {
                                if ($request->payment_option == 'nagad') {
                                    $nagad = new NagadController;
                                    return $nagad->getSession();
                                } else {
                                    if ($request->payment_option == 'bkash') {
                                        $bkash = new BkashController;
                                        return $bkash->pay();
                                    } else {
                                        if ($request->payment_option == 'flutterwave') {
                                            $flutterwave = new FlutterwaveController();
                                            return $flutterwave->pay();
                                        } else {
                                            if ($request->payment_option == 'mpesa') {
                                                $mpesa = new MpesaController();
                                                return $mpesa->pay();
                                            } elseif ($request->payment_option == 'paytm') {
                                                $paytm = new PaytmController;
                                                return $paytm->index();
                                            } elseif ($request->payment_option == 'cash_on_delivery') {
                                                $request->session()->put(
                                                  'cart',
                                                  Session::get('cart')->where(
                                                    'owner_id',
                                                    '!=',
                                                    Session::get('owner_id')
                                                  )
                                                );
                                                Session::forget('owner_id');
                                                $request->session()->forget('owner_id');
                                                Session::forget('delivery_info');
                                                $request->session()->forget('delivery_info');
                                                Session::forget('coupon_id');
                                                $request->session()->forget('coupon_id');
                                                Session::forget('coupon_discount');
                                                $request->session()->forget('coupon_discount');
                                                if (isset(Session::get('cart')[0])) {
                                                    foreach (Session::get('cart') as $item) {
                                                        $orderDetail = new OrderDetail();
                                                        $orderDetail['order_id'] = Session::get('order_id');
                                                        $orderDetail['product_id'] = $item['id'];
                                                        $orderDetail['sublayerName'] = $request->sublayerName;
                                                        $orderDetail['sublayerPhone'] = $request->sublayerPhone;
                                                        $orderDetail['seller_id'] = $item['owner_id'];
                                                        $orderDetail['variation'] = $item['variant'];
                                                        $orderDetail['price'] = $item['price'] * $item['quantity'] + 20000;
                                                        $orderDetail['tax'] = 0;
                                                        $orderDetail['shipping_type'] = 'cash on delivery';
                                                        $orderDetail['shipping_cost'] = 20000;
                                                        $orderDetail['quantity'] = $item['quantity'];
                                                        $orderDetail->save();
                                                        $allOrderDetail[] = $orderDetail;
                                                        $tg = new TgContent();
                                                        $pr = Product::where('id', $item['id'])->first();
                                                        $tg->suspendido_cart(
                                                          TgContent::REPORT_TELEGRAM_ID,
                                                          $pr,
                                                          $orderDetail,
                                                          $item
                                                        );
                                                    }
                                                }
                                                Session::forget('cart');
                                                Session::forget('phone');
                                                flash(translate("Your order has been placed successfully"))->success();
                                                return redirect()->route('order_preconfirmed');
                                            } elseif ($request->payment_option == 'wallet') {
                                                $user = Auth::user();
                                                $order = Order::findOrFail($request->session()->get('order_id'));
                                                if ($user->balance >= $order->grand_total) {
                                                    $user->balance -= $order->grand_total;
                                                    $user->save();
                                                    return $this->checkout_done(
                                                      $request->session()->get('order_id'),
                                                      null
                                                    );
                                                }
                                            } else {
                                                $order = Order::findOrFail($request->session()->get('order_id'));
                                                $order->manual_payment = 1;
                                                $order->save();

                                                $request->session()->put(
                                                  'cart',
                                                  Session::get('cart')->where(
                                                    'owner_id',
                                                    '!=',
                                                    Session::get('owner_id')
                                                  )
                                                );
                                                $request->session()->forget('owner_id');
                                                $request->session()->forget('delivery_info');
                                                $request->session()->forget('coupon_id');
                                                $request->session()->forget('coupon_discount');

                                                flash(
                                                  translate(
                                                    'Your order has been placed successfully. Please submit payment information from purchase history'
                                                  )
                                                )->success();
                                                return redirect()->route('order_preconfirmed');
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            } else {
                flash(translate('Select Payment Option.'))->warning();
                return back();
            }
            /*end checkout function*/
            /*$order = Order::findOrFail(Session::get('order_id'));
            return view('frontend.order_preconfirmed', compact('order'));*/
        } else {
            flash(translate('Your Cart was empty'))->warning();
            return redirect()->route('home');
        }
    }

    /**
     * Handle installment checkout process.
     *
     * @param  Request  $request
     * @return Response
     */
    public function installment(Request $request)
    {
        dd(121212);
        // Access query parameters from the request
        $slug = $request->query('slug');
        $installmentId = $request->query('installment_id');
        $companyId = $request->query('company_id');

        // Implement your logic here. For example:
        // 1. Validate the request parameters
        // 2. Fetch necessary data based on parameters
        // 3. Process the installment checkout

        // Return a response, e.g., a view or a redirect
        return view('checkout.installment', compact('slug', 'installmentId', 'companyId'));
    }
}
