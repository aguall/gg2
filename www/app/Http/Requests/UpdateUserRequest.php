<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UpdateUserRequest extends Request
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
			'name'		=> 'required',
			'email'		=> 'required|email|unique:users,email,' . (int)Request::segment(4),
			'login'		=> 'required|min:3|unique:users,login,' . (int)Request::segment(4),
			'password'	=> 'min:6',
			'rating'	=> 'integer'
        ];
    }
}
