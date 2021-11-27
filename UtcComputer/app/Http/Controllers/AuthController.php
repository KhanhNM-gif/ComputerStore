<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\OTP;
use Illuminate\Http\Request;
use App\Jobs\SendEmail;
use App\Models\Region;
use App\Rules\PhoneNumber;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string|unique:accounts,email|email|max:255',
            'name' => 'required|string|max:255',
            'password' => 'required|string|confirmed|min:6|max:255',
            'fullname' => 'required|string|max:255',
            'phone_number' => ['required', 'string', new PhoneNumber],
            'address' => 'required|string|max:255',
            'province_id' => 'required|numeric|integer',
            'district_id' => 'required|numeric|integer',
            'ward_id' => 'required|numeric|integer'
        ]);

        $msgError = Region::ValidateAddress($fields['ward_id'], $fields['district_id'], $fields['province_id']);
        if ($msgError) {
            return response()->json([
                'message' => $msgError,
                "errors" => []
            ], 400);
        }

        $user = Account::create([
            'email' => $fields['email'],
            'name' => $fields['name'],
            'password' => password_hash($fields['password'], PASSWORD_DEFAULT),
            'fullname' => $fields['fullname'],
            'phone_number' => $fields['phone_number'],
            'address' => $fields['address'],
            'province_id' => $fields['province_id'],
            'district_id' => $fields['district_id'],
            'ward_id' => $fields['ward_id']
        ]);

        $this->dispatch(new SendEmail($user));

        return Response([
            'user' => $user,
        ], 201);
    }

    public function verify_account(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string|email|max:255',
            'OTP' => 'required|string|max:6|min:6'
        ]);

        $user = Account::where('email', $request['email'])->first();

        if (!$user) return  'Tài khoản chưa được đăng kí';
        if ($user['email_verified_at']) return 'Tài khoản đã được xác thực';

        $msg = OTP::check($fields);
        if ($msg) {
            return response()->json([
                'message' => $msg,
                "errors" => []
            ], 401);
        }

        Account::where('email', $user->email)->update(['email_verified_at' => now()]);
        $token = $user->first()->createToken('myapptoken')->plainTextToken;

        return Response([
            'user' => $user,
            'token' => $token
        ], 200);
    }

    public function send_OTP(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email|max:255',
        ]);

        $user = Account::where('email', $credentials['email'])->first();

        if (!$user) return  'Tài khoản chưa được đăng kí';

        SendEmail::dispatch($user)->delay(now()->addMinute(1));

        return response("", 200);
    }

    public function verify_handle(Request $request)
    {
        $msg_error = $this->verify_handle_validate($request, $output);
        if ($msg_error) {
            return response()->json([
                'message' => $msg_error,
                "errors" => []
            ], 201);
        }

        $user = $output['user'];
        $input = $output['input'];

        Account::where('email', $user->email)->update(['OTP' => $input['OTP'], 'OTP_verified_at' => now()]);

        return Response(['OTP' => password_hash($input['OTP'], PASSWORD_DEFAULT)], 200);
    }

    private function verify_handle_validate(Request $request, &$output)
    {
        $input = $request->validate([
            'email' => 'required|string|email|max:255',
            'OTP' => 'required|string|max:6|min:6'
        ]);

        $user = Account::where('email', $input['email'])->first();
        if (!$user) return 'Tài khoản chưa được đăng kí';

        $msg = OTP::check($input);
        if ($msg) return $msg;

        $output = [
            'input' => $input,
            'user' => $user
        ];
        return null;
    }

    public function forget_password(Request $request)
    {
        $msg_error = $this->forget_password_validate($request, $output);
        if ($msg_error) {
            return response()->json([
                'message' => $msg_error,
                "errors" => []
            ], 201);
        }

        $input = $output['input'];
        $user = $output['user'];

        Account::where('email', $user->email)->update(['password' => password_hash($input['password'], PASSWORD_DEFAULT)]);

        $response = [
            'user' => $user
        ];
        return Response($response, 200);
    }
    private function forget_password_validate(Request $request, &$output)
    {
        $input = $request->validate([
            'email' => 'required|string|email|max:255',
            'OTP_hash' => 'required|string|max:255',
            'password' => 'required|string|confirmed|min:6|max:255'
        ]);

        $user = Account::where('email', $input['email'])->first();

        if (!$user) return 'Tài khoản chưa được đăng kí';

        if (!password_verify($user['OTP'], $input['OTP_hash'])) return "OTP_hash sai";

        $output = [
            'input' => $input,
            'user' => $user
        ];

        return null;
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6|max:255'
        ]);

        $user = Account::where('email', $credentials['email'])->first();

        if (!$user) {
            return response()->json([
                'message' => 'Email chưa được đăng kí trên hệ thống',
                "errors" => []
            ], 401);
        }

        if (password_verify($credentials['password'], $user['password'])) {
            if (!$user->email_verified_at) {
                return response()->json([
                    'message' => 'Tài khoản chưa được kích hoạt',
                    "errors" => []
                ], 401);
            }

            $token = $user->createToken('myapptoken')->plainTextToken;
            $response = [
                'user' => $user,
                'token' => $token
            ];

            return Response($response, 200);
        }

        return response()->json([
            'message' => 'Mật khẩu đăng nhập sai',
            "errors" => []
        ], 201);
    }

    public function UpdateInfomation(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string|max:255',
            'fullname' => 'required|string|max:255',
            'phone_number' => ['required', 'string', new PhoneNumber],
            'address' => 'required|string|max:255',
            'province_id' => 'required|numeric|integer',
            'district_id' => 'required|numeric|integer',
            'ward_id' => 'required|numeric|integer'
        ]);

        $msgError = Region::ValidateAddress($fields['ward_id'], $fields['district_id'], $fields['province_id']);
        if ($msgError) {
            return response()->json([
                'message' => $msgError,
                "errors" => []
            ], 400);
        }

        $user = Auth::user();
        $user->Update([
            'name' => $fields['name'],
            'fullname' => $fields['fullname'],
            'phone_number' => $fields['phone_number'],
            'address' => $fields['address'],
            'province_id' => $fields['province_id'],
            'district_id' => $fields['district_id'],
            'ward_id' => $fields['ward_id']
        ]);

        return Response([
            'user' => $user,
        ], 201);
    }

    public function Logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
    }

    public function GetOne()
    {
        $user = Auth::user();

        return Response([
            'user' => $user,
        ], 200);
    }

    public function ChangePassword(Request $request)
    {

        $msg = $this->ChangePasswordValidate($request, $output);
        if ($msg) {
            return response()->json([
                'message' => $msg,
                "errors" => []
            ], 401);
        }

        $output['user']->Update([
            'password' => password_hash($output['input']['password_new'], PASSWORD_DEFAULT),
        ]);

        return Response([
            'user' => $output['user'],
        ], 200);
    }
    private function ChangePasswordValidate(Request $request, &$output)
    {
        $input = $request->validate([
            'password_old' => 'required|string|min:6|max:255',
            'password_new' => 'required|string|confirmed|min:6|max:255'
        ]);

        $user = Account::find(Auth::user()->id);

        if (!password_verify($input['password_old'], $user['password'])) {
            return 'Mật khẩu đăng nhập sai. Vui lòng nhập lại';
        }

        if ($input['password_old'] == $input['password_new']) {
            return 'Mật khẩu mới không được trùng với mật khẩu cũ';
        }

        $output = [
            'input' => $input,
            'user' => $user
        ];
    }
}