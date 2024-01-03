<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use App\User;
use App\Mail\SecondEmailVerifyMailManager;
use Mail;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            $user = User::where('email', $request->email)->first();
            if ($user != null) {
                $user->verification_code = rand(100000,999999);
                $user->save();

                $array['view'] = 'emails.verification';
                $array['from'] = env('MAIL_USERNAME');
                $array['subject'] = translate('Password Reset');
                $array['content'] = 'Verification Code is '.$user->verification_code;

                Mail::to($user->email)->queue(new SecondEmailVerifyMailManager($array));

                return view('auth.passwords.reset');
            }
            else {
                flash(translate('No account exists with this email'))->error();
                return back();
            }
        }
        else{
            $phone = '+998' . substr($request->email, -9); 

            // check if number start with +998 
            $phone = $request->email; 

            if (str_starts_with($phone, '+998')) {
                // don't do anythink 

            } else if(str_starts_with($phone, '998')) {
                $phone = '+' . $phone; 
            } else {
                $phone = '+998' . $phone; 
            }

            if (!preg_match('/^\+998[0-9]{9}$/', $phone)) {
                flash(translate('Invalid phone number !'))->error();
                return back();
            }
            

            $user = User::where('phone', $phone)->first();
            

            if ($user != null) {
                $user->verification_code = rand(100000,999999);
                $user->save();

                $to = $user->phone; 
                $from = env('APP_NAME');
                $text = 'Azbo.uz: '. translate('Your verification code is') .': ' . $user->verification_code ;

                $response = sendSMS($to, $from, $text);

                if ($response != 'Request is received') {
                    flash(translate('Internal Error: Failed to send SMS, try using email.'))->error();
                    return back();
                } 
            
                return redirect('/password/phone/reset'); // added by ao 

                // return view('otp_systems.frontend.auth.passwords.reset_with_phone');
            }
            else {
                flash(translate('No account exists with this phone number'))->error();
                return back();
            }
        }
    }
}
