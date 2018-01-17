<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ProductsCategoryRequest extends Request
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
            'slug'		=> (!empty(Request::segment(5)) ? 'unique:products_categories,slug,' . (int)Request::segment(5) : 'unique:products_categories'),
			'image'		=> 'image',
			'name_ru'	=> 'required'
        ];
    }
}
