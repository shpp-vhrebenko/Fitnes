<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Request;

class ItemRequest extends FormRequest
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
    public function rules(Request $request)
    {
        return [
            'item_locales.ru.name' => 'required',
            'item_locales.ua.name' => 'required',
            'item_locales.en.name' => 'required',
            'item.code' => 'required|unique:items,code,' . Request::get('item_id'),
            'item.price' => 'required|numeric',
            'item.whs_price' => 'sometimes|nullable|numeric',
            'item.old_price' => 'sometimes|nullable|numeric',
            'item.qty' => 'required|numeric',
            'item.min_qty' => 'sometimes|nullable|numeric',
            'item.max_qty' => 'sometimes|nullable|numeric',
            'categories' => 'required',
            'item.duration_sale' => 'sometimes|nullable|date_format:"Y-m-d"',
            'item.duration_new' => 'sometimes|nullable|date_format:"Y-m-d"'
        ];
    }
}
