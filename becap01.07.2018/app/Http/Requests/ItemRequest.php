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
            'item.title' => 'required|max:190',
            /*'item.title' => 'required|max:190|unique:items,title',*/
            'item.text' => 'required',
            'item.image' => 'image|mimes:jpeg,png,jpg,gif,svg',
            'item.category_id' => 'required|not_in:0',
            /*'item.course_id' => 'required|not_in:0',*/
            'courses_array' => 'required',
            /*'item.is_active' => 'required', */           
        ];
    }

    public function messages() {
        return [
            'item.title.required' => 'Необходимо ввести Заголовок Записи',
            'item.title.max' => 'Максимальное количество символов Заголовка 190',
            /*'item.title.unique' => 'Запись с таким названием уже есть',*/
            'item.text.required' => 'Необходимо ввести  Текст Записи',
            'item.image.image' => 'Изображение записи должно быть в формате изображения',
            'item.image.mimes' => 'Изображение записи должно быть в формате jpeg,png,jpg,gif,svg',
            'item.category_id.required' => 'Необходимо выбрать Категорию Записи',
            'item.category_id.not_in' => 'Необходимо выбрать Категорию Записи',
            'courses_array.required' => 'Необходимо выбрать Курс',
            /*'item.course_id.required' => 'Необходимо выбрать Курс',
            'item.course_id.not_in' => 'Необходимо выбрать Курс',*/
            /*'item.is_active.required' => 'Необходимо указать Статус Записи',  */          
        ];
    }
}
