<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

use Auth;

class EditUserInfoRequest extends Request
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
            'name' 		=> 'required',
            'login'		=> 'required|unique:users,login,' . Auth::user()->id,
            'email'		=> 'required|email|unique:users,email,' . Auth::user()->id,
            'password'	=> 'min:6'
        ];
    }
}
