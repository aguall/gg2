<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ShopXMLRequest extends Request
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
			'shop_id' 			=> (!empty(Request::segment(6)) ? 'unique:shops_xml,shop_id,' . (int)Request::segment(6) : 'unique:shops_xml'),
			'xml_url' 			=> 'required|url',
			'xml_tag_wrapper'	=> 'required',
			'xml_tag_name'		=> 'required',
			'xml_tag_price'		=> 'required'
        ];
    }
}
