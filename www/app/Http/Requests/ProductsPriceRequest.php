<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ProductsPriceRequest extends Request
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
            'shop_id'				=> 'required|not_in:0',
			'shop_product_name'		=> 'required',
			'shop_product_link'		=> 'required|active_url',
			'shop_product_price'	=> 'required|numeric'
        ];
    }
}
