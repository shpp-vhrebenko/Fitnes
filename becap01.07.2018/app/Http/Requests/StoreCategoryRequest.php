<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
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
            'item.name' => 'required|max:190|unique:categories,name',            
            'item.description' => 'required',
            'item.is_active' => 'required',
        ];
    }

    public function messages() {
        return [
            'item.name.required' => 'Необходимо ввести Название Категории', 
            'item.name.unique' => 'Категория с таким названием уже есть',           
            'item.description.required' => 'Необходимо ввести Описание Категории',
            'item.is_active.required' => 'Необходимо указать статус Категории',           
        ];
    }
}
