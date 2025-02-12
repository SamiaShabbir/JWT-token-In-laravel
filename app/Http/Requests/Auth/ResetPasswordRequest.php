<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\BaseAPIRequest;

class ResetPasswordRequest extends BaseAPIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
                   'email'=>['required','email','exists:users'],
                   'otp'=>['required','max:6'],
                   'password'=>['required','string','min:6']
        ];
    }
}
