<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;


class StoreMarathonsRequest extends FormRequest
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
            'item.name' => 'required|unique:courses,name',
            'item.description' => 'required',                       
            'item.period' => 'required|numeric',
            'item.date_end_selection' => 'required|date|date_format:Y-m-d|after:item.date_start_selection',
            'item.date_start_selection' => 'required|date|date_format:Y-m-d|before:item.date_end_selection',     
            'item.price' => 'required|numeric', 
            'item.icon' => 'image|mimes:png,svg', 
            'item.whats_app_link' => 'required',
            'item.faq' => 'required',  
            'item.notification_day_number' => 'required',
            'item.notification' => 'required',       
        ];
    }

    public function messages() {
        return [
            'item.name.required' => 'Необходимо ввести Название Марафона',
            'item.name.unique' => 'Марафон с таким названием уже есть',
            'item.description.required' => 'Необходимо ввести Содержание Марафона',            
            'item.period.required' => 'Необходимо ввести Продолжительность Марафона',
            'item.date_start_selection.required' => 'Необходимо ввести дату Начала отбора',
            'item.date_start_selection.date' => 'Необходимо ввести дату Начала отбора в формате даты',
            'item.date_start_selection.date_format' => 'Необходимо ввести дату Начала отбора в формате Год-месяц-день',
            'item.date_start_selection.before' => 'Необходимо чтобы дата Начала отбора была до даты Конца отбора',
            'item.date_end_selection.required' => 'Необходимо ввести дату Конца отбора',
            'item.date_end_selection.date' => 'Необходимо ввести дату Конца отбора в формате даты',
            'item.date_end_selection.date_format' => 'Необходимо ввести дату Конца отбора в формате Год-месяц-день',
            'item.date_end_selection.after' => 'Необходимо чтобы дата Конца отбора была после даты Начала отбора',
            'item.price.required' => 'Необходимо ввести цену Марафона',
            'item.icon.image' => 'Иконка курса должна быть в формате изображения',
            'item.icon.mimes' => 'Иконка курса должна быть в формате png, svg',
            'item.whats_app_link.required' => 'Необходимо ввести сылку на WhatsApp Чат',
            'item.faq' => 'Необходимо ввести FAQ',
            'item.notification_day_number.required' => 'Необходимо ввести количество дней до конца Курса',
            'item.notification.required' => 'Необходимо ввести Текст уведомления',
        ];
    }
}
