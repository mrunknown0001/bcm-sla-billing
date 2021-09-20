<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;

use App\User;
use App\PasswordReset;
use App\PasswordHistory;

use App\Http\Controllers\GeneralController as GC;
use App\Http\Controllers\MailController as MC;


class LoginController extends Controller
{
    /**
     * Show login view
     */
    public function login()
    {
    	return view('login');
    }



    public function postLogin(Request $request)
    {

		if(Auth::attempt(['email' => $request->email, 'password' => $request->password, 'active' => 1])) {

            // check password history for password change
            $cp = PasswordHistory::where('user_id', Auth::user()->id)->get();


            // check days of password retention left
            // if 10 days or less, make notification on dashboard, that they need to change password


            // if -1 or less 
            // block expired password



			if(Auth::user()->user_type == 6) {
                if(count($cp) < 1) {
                    return redirect()->route('user.dashboard')->with('notice', 'Please Change You Password. You are using a default password. <br> Click Arrow Down on Upper Right Menu > User Account > Change Password');
                }
				return redirect()->route('user.dashboard');
			}
            else if(Auth::user()->user_type == 4) {
                if(count($cp) < 1) {
                    return redirect()->route('manager.dashboard')->with('notice', 'Please Change You Password. You are using a default password. <br> Click Arrow Down on Upper Right Menu > User Account > Change Password');
                }
                return redirect()->route('manager.dashboard');
            }
            else if(Auth::user()->user_type == 3) {
                if(count($cp) < 1) {
                    return redirect()->route('divhead.dashboard')->with('notice', 'Please Change You Password. You are using a default password. <br> Click Arrow Down on Upper Right Menu > User Account > Change Password');
                }
                return redirect()->route('divhead.dashboard');
            }
            else if(Auth::user()->user_type == 2) {
                if(count($cp) < 1) {
                    return redirect()->route('vp.dashboard')->with('notice', 'Please Change You Password. You are using a default password. \n Click Arrow Down on Upper Right Menu > User Account > Change Password');
                }
                return redirect()->route('vp.dashboard');
            }
            else if(Auth::user()->user_type == 1) {
                if(count($cp) < 1) {
                    return redirect()->route('coo.dashboard')->with('notice', 'Please Change You Password. You are using a default password. <br> Click Arrow Down on Upper Right Menu > User Account > Change Password');
                }
                return redirect()->route('coo.dashboard');
            }
		}

		return redirect()->route('login')->with('error', 'Invalid Credentials');
    }




    public function forgotPassword()
    {
        return view('forgot-pass');
    }




    public function postForgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        // check email on database and active
        $user = User::where('email', $request->email)
                    ->where('active', 1)
                    ->first();

        if(empty($user)) {
            return redirect()->back()->with('error', 'Email not found!');
        }

        // generate code
        $code = GC::generateVerificationCode();
        $link = route('validate.verification.code', ['id' => $user->id]);
        $email = $user->email;
        $name = ucfirst($user->first_name) . ' ' . ucfirst($user->last_name);


        // Save
        $passreset = new PasswordReset();
        $passreset->user_id = $user->id;
        $passreset->link = $link;
        $passreset->code = $code;
        $passreset->valid_until = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $passreset->save();


        // send email with link and verification code
        return MC::sendVerificationCode($code, $email, $name, $link);

        // redirect to verificaton code input
        return redirect()->route('login')->with('success', 'Please check your email for Password Reset Link and Code send to you email!');
    }



    public function validateVerificationCode($id)
    {
        return view('verification-code', ['id' => $id]);
    }


    public function postValidateVerificationCode(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'code' => 'required'
        ]);

        $pr = PasswordReset::where('user_id', $request->id)
                    ->where('code', $request->code)
                    ->first();

        if(empty($pr)) {
            return redirect()->route('login')->with('error', 'Invalid Argument! This action is Logged! If you are trying to break the system you are in danger!');
        }

        if(date('Y-m-d H:i:s', strtotime($pr->valid_until)) > date('Y-m-d H:i:s', strtotime(now())) && $pr->used != 1) {
            // return to password reset page
            return view('password-reset', ['pr' => $pr]);
        }

        return redirect()->route('login')->with('error', 'Verification Code Expired!');
    }



    public function postPasswordReset(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'password' => 'required|confirmed|min:8|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!@$#%]).*$/',
        ]);

        $pr = PasswordReset::findorfail($request->id);

        if($pr->valid_until > date('Y-m-d H:i:s', strtotime(now())) && $pr->used == 0) {
            $user = User::findorfail($pr->user_id);

            $user->password = bcrypt($request->password);
            $user->save();

            $pr->used = 1;
            $pr->save();

            $history = new PasswordHistory();
            $history->user_id = $user->id;
            $history->password = $user->password;
            $history->date_change = date('Y-m-d', strtotime(now()));
            $history->save();

            return redirect()->route('login')->with('success', 'Password Reset Successfully!');

        }

        return redirect()->route('login')->with('error', 'Verification Code Expired!');
    }




    public function logout($param = NULL)
    {
    	Auth::logout();

        if($param == NULL) {
        	return redirect()->route('login')->with('success', 'Logout Success!');
        }

        return redirect()->route('login')->with('success', 'Auto Logout in Idle!');
    }
}
