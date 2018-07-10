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
            'item.is_subscribe' => 'required', 
            'item.data_start_course' => 'date|date_format:Y-m-d H:i:s'       
        ];
    }

    public function messages() {
        return [
            'item.name.required' => 'Необходимо ввести ФИО Клиента',
            'item.email.required' => 'Необходимо ввести Email Клиента',            
            'item.phone.required' => 'Необходимо ввести Номер Телефона клиента',
            'item.status_id.required' => 'Необходимо указать Статус Клиента',
            'item.is_subscribe.required' => 'Необходимо указать Подписку на рассылку',
            'item.data_start_course.date' => 'Необходимо указать дату начала Курса в формате даты',
            'item.data_start_course.date_format' => 'Необходимо указать дату начала Курса в формате Год-месяц-день   часы : минуты : секунды', 
        ];
    }
}
