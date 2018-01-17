<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ShopRequest extends Request
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
            'slug'		=> (!empty(Request::segment(4)) ? 'unique:shops,slug,' . (int)Request::segment(4) : 'unique:shops'),
			'logo'		=> 'image',
			'name_ru'	=> 'required',
        ];
    }
}
