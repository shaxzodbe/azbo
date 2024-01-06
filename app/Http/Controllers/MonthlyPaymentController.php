<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Application; 
use App\User; 


class MonthlyPaymentController extends Controller
{
    

    public function index() {
        $applications = Application::orderBy('created_at', 'desc')->get();
        
        return view('backend.monthly_payment.index', compact('applications'));
    }

    public function show($id) {
        $application = Application::findOrFail(decrypt($id));
        return view('backend.monthly_payment.show', compact('application'));
    }

    public function edit($user_id) {
        $user = User::findOrFail($user_id);
        return view('backend.monthly_payment.edit', compact('user'));
    }

    
    public function update_status(Request $request) {
        $application = Application::findOrFail($request->application_id);

        $application->status = $request->status; 
        $application->save();
    }

    public function update(Request $request)
    {

        $user = User::findOrFail(decrypt($request->user_id));

        
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

        return back(); 
    }
}
