<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Application;

use Auth;
use http\Client\Curl\User;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{

    public function store(Request $request)
    {
        $user = Auth::user();

        /**
         * validate requesest
         */
        $user->plastic_serial_number = $request->plastic_serial_number;
        $user->expiry_date = $request->expiry_date;
        $user->connected_phone_number = $request->connected_phone_number;

        $user->relative_phone_number1 = $request->relative_phone_number1;
        $user->relative_phone1_name = $request->relative_phone1_name;
        $user->relative_phone1_owner = $request->relative_phone1_owner;

        $user->relative_phone_number2 = $request->relative_phone_number2;
        $user->relative_phone2_name = $request->relative_phone2_name;
        $user->relative_phone2_owner = $request->relative_phone2_owner;


        $user->relative_phone_number3 = $request->relative_phone_number3;
        $user->relative_phone3_name = $request->relative_phone3_name;
        $user->relative_phone3_owner = $request->relative_phone3_owner;

        $user->work_place_name = $request->work_place_name;
        $user->month_salary = $request->month_salary ? $request->month_salary : 0;

        $user->amount_salary = $request->amount_salary;
        $user->is_paying_credit = $request->is_paying_credit ? true : false;
        $user->amount_credit = $request->amount_credit ? $request->amount_credit : 0;
        $user->passport_image = $request->passport_image;
        $user->photo_with_passport = $request->photo_with_passport;

        $user->save();

        $data = session()->get('new_application');

        Application::create($data);

        /**
         * purge session('new_application');
         */

        session()->forget('new_application');
        return view('frontend.installments.application_confirmed', compact('data'));
    }

    public function storeapp(Request $request)
    {

        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user){
            $user->plastic_serial_number = $request->plastic_serial_number;
            $user->expiry_date = $request->expiry_date;
            $user->connected_phone_number = $request->connected_phone_number;

            $user->relative_phone_number1 = $request->relative_phone_number1;
            $user->relative_phone1_name = $request->relative_phone1_name;
            $user->relative_phone1_owner = $request->relative_phone1_owner;

            $user->relative_phone_number2 = $request->relative_phone_number2;
            $user->relative_phone2_name = $request->relative_phone2_name;
            $user->relative_phone2_owner = $request->relative_phone2_owner;


            $user->relative_phone_number3 = $request->relative_phone_number3;
            $user->relative_phone3_name = $request->relative_phone3_name;
            $user->relative_phone3_owner = $request->relative_phone3_owner;

            $user->work_place_name = $request->work_place_name;
            $user->month_salary = $request->month_salary ? $request->month_salary : 0;

            $user->amount_salary = $request->amount_salary;
            $user->is_paying_credit = $request->is_paying_credit ? true : false;
            $user->amount_credit = $request->amount_credit ? $request->amount_credit : 0;
            $user->passport_image = $request->passport_image;
            $user->photo_with_passport = $request->photo_with_passport;

            $user->update();
        }else{
            $user = new \App\User();
            $user->phone = $request->relative_phone_number1;
            $user->plastic_serial_number = $request->plastic_serial_number;
            $user->expiry_date = $request->expiry_date;
            $user->connected_phone_number = $request->connected_phone_number;

            $user->relative_phone_number1 = $request->relative_phone_number1;
            $user->relative_phone1_name = $request->relative_phone1_name;
            $user->relative_phone1_owner = $request->relative_phone1_owner;

            $user->relative_phone_number2 = $request->relative_phone_number2;
            $user->relative_phone2_name = $request->relative_phone2_name;
            $user->relative_phone2_owner = $request->relative_phone2_owner;


            $user->relative_phone_number3 = $request->relative_phone_number3;
            $user->relative_phone3_name = $request->relative_phone3_name;
            $user->relative_phone3_owner = $request->relative_phone3_owner;

            $user->work_place_name = $request->work_place_name;
            $user->month_salary = $request->month_salary ? $request->month_salary : 0;

            $user->amount_salary = $request->amount_salary;
            $user->is_paying_credit = $request->is_paying_credit ? true : false;
            $user->amount_credit = $request->amount_credit ? $request->amount_credit : 0;
            $user->passport_image = $request->passport_image;
            $user->photo_with_passport = $request->photo_with_passport;

            $user->save();
        }

        /**
         * validate requesest
         */

        $request->session()->put('new_application.user_id',$user->id);
        $data = session()->get('new_application');
        Application::create($data);

        /**
         * purge session('new_application');
         */
        dd($data);
        session()->forget('new_application');
        return view('frontend.installments.application_confirmed', compact('data'));
    }
}
