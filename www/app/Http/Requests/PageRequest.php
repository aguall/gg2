<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class PageRequest extends Request
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
			'slug'	  => (!empty(Request::segment(4)) ? 'unique:pages,slug,' . (int)Request::segment(4) : 'unique:pages'),
			'name_ru' => 'required'
        ];
    }
}
