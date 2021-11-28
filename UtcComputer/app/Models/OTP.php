<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OTP extends Model
{
    use HasFactory;
    protected $table = 'otp';
    protected $fillable = ['email', 'value', 'create_at'];
    public $timestamps = true;

    public static function check($request)
    {
        $otp = OTP::where('email', $request['email'])->orderBy('created_at', 'DESC')->first();

        if (!$otp) return  'Mã OTP chưa được tạo';

        if ($otp['value'] != $request['OTP']) return 'Mã OTP xác thực không chính xác';

        if (!$otp->created_at->gt(now()->subMinutes(5))) return 'Mã OTP đã hết hạn';
        return null;
    }
}
