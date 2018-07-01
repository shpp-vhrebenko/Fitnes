<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreResultRequest extends FormRequest
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
            'result.image' => 'required|image|mimes:jpeg,png,jpg',
            'result.weight' => 'required|not_in:0',
            'result.height' => 'required|not_in:0',
            'result.grud' => 'required|not_in:0',
            'result.bedra' => 'required|not_in:0',            
            'result.taliya' => 'required|not_in:0',                        
        ];
    }

    public function messages() {
        return [
            'result.image.required' => 'Необходимо загрузить фото',
            'result.image.image' => 'Фото должно быть в формате изображения',
            'result.image.mimes' => 'Фото должно быть в формате jpeg,png,jpg',
            'result.weight.required' => 'Необходимо указать Вес',
            'result.weight.not_in' => 'Необходимо указать Вес',
            'result.height.required' => 'Необходимо указать Рост', 
            'result.height.not_in' => 'Необходимо указать Рост',            
            'result.taliya.required' => 'Необходимо указать Обхват талии',
            'result.taliya.not_in' => 'Необходимо указать Обхват талии',
            'result.grud.required' => 'Необходимо указать Обхват груди',
            'result.grud.not_in' => 'Необходимо указать Обхват груди',
            'result.bedra.required' => 'Необходимо указать Обхват бедер', 
            'result.bedra.not_in' => 'Необходимо указать Обхват бедер',      
        ];
    }
}
