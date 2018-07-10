<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\User;

class EditClientRequest extends FormRequest
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
            'item.name' => 'required',
            'item.email' => 'required|string|email|max:150',
            'item.phone' => 'required|numeric|min:10',            
            'item.status_id' => 'required',
            'item.course_id' => 'required',                 
        ];
    }

    public function messages() {
        return [
            'item.name.required' => 'Необходимо ввести ФИО Клиента',
            'item.email.required' => 'Необходимо ввести Email Клиента',            
            'item.phone.required' => 'Необходимо ввести Номер Телефона клиента',
            'item.status_id.required' => 'Необходимо указать Статус Клиента',  
            'item.course_id.required' => 'Необходимо выбрать Курс',              
        ];
    }
}
