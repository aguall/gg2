<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class BlogPostRequest extends Request
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
            'slug'			=> (!empty(Request::segment(5)) ? 'unique:blog_posts,slug,' . (int)Request::segment(5) : 'unique:blog_posts'),
			'image'			=> 'image',
			'name_ru'		=> 'required',
			'category_id'	=> 'not_in:0'
        ];
    }
}
