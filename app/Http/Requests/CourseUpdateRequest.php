<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class CourseUpdateRequest extends FormRequest
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
           /* 'item.description' => 'required',*/
            'item.price' => 'required|numeric',            
            'item.period' => 'required|numeric',
            'item.icon' => 'image|mimes:png,svg', 
            'item.notification_day_number' => 'required',
            'item.notification' => 'required', 
            /*'item.whats_app_link' => 'required',
            'item.faq' => 'required', */
        ];
    }

    public function messages() {
        return [
            'item.name.required' => 'Необходимо ввести Название Курса',            
          /*  'item.description.required' => 'Необходимо ввести Содержание Курса',*/
            'item.price.required' => 'Необходимо ввести цену Курса',
            'item.period.required' => 'Необходимо ввести Продолжительность Курса',
            'item.icon.image' => 'Иконка курса должна быть в формате изображения',
            'item.icon.mimes' => 'Иконка курса должна быть в формате png, svg',
            'item.notification_day_number.required' => 'Необходимо ввести количество дней до конца Курса',
            'item.notification.required' => 'Необходимо ввести Текст уведомления',
            /*'item.whats_app_link.required' => 'Необходимо ввести сылку на WhatsApp Чат',
            'item.faq' => 'Необходимо ввести FAQ',*/
        ];
    }
}
