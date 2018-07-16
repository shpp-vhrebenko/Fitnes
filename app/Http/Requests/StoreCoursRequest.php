<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class StoreCoursRequest extends FormRequest
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
            'item.name' => 'required|unique:courses,name',
            /*'item.description' => 'required',*/
            'item.price' => 'required|numeric',            
            'item.period' => 'required|numeric',
            'item.icon' => 'image|mimes:png,svg',  
          /*  'item.whats_app_link' => 'required',
            'item.faq' => 'required',  */
            'item.notification_day_number' => 'required',
            'item.notification' => 'required', 
        ];
    }

    public function messages() {
        return [
            'item.name.required' => 'Необходимо ввести Название Курса',
            'item.name.unique' => 'Курс с таким названием уже есть',
            /*'item.description.required' => 'Необходимо ввести Содержание Курса',*/
            'item.price.required' => 'Необходимо ввести цену Курса',
            'item.period.required' => 'Необходимо ввести Продолжительность Курса',
            'item.icon.image' => 'Иконка курса должна быть в формате изображения',
            'item.icon.mimes' => 'Иконка курса должна быть в формате png, svg',
            /*'item.whats_app_link.required' => 'Необходимо ввести сылку на WhatsApp Чат',
            'item.faq' => 'Необходимо ввести FAQ',*/
            'item.notification_day_number.required' => 'Необходимо ввести количество дней до конца Курса',
            'item.notification.required' => 'Необходимо ввести Текст уведомления',
        ];
    }
}
