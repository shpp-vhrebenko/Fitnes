<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSettingsRequest extends FormRequest
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
            'settings.logo' => 'image',
            'settings.favicon' => 'image',
            'settings.title' => 'required',                        
            'settings.email' => 'required',            
            'social.instagram' => 'required', 
        ];
    }

    public function messages() {
        return [
            'settings.logo.image' => 'Необходимо загрузить логотип сайта в формате .png, .jpg',
            'settings.favicon.image' => 'Необходимо загрузить favicon icon в формате .png',
             'settings.logo.required' => 'Необходимо загрузить логотип сайта',
            'settings.favicon.required' => 'Необходимо загрузить favicon icon',
            'settings.title.required' => 'Необходимо ввести Мета-тег Title',            
            'settings.email.required' => 'Необходимо ввести Email',             
            'social.instagram.required' => 'Необходимо ввести Ссылку на instagram',        
        ];
    }
}
