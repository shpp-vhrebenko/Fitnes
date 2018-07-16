<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Role;
use Result;
use App\Courses;

class User extends Authenticatable
{
    use Notifiable;

    public static $userStatuses = [        
        'Выключен',
        'Активен',        
    ];

    public static function getUserStatus()
    {
        return self::$userStatuses;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'email', 
        'password', 
        'phone', 
        'status_id', 
        'is_subscribe',
        'course_id',
        'data_start_course',
        'id_last_result',
        'check_notification'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function getClientStatus($status_id)
    {
        return self::$userStatuses[$status_id];
    }


    public function roles() {
        return $this->belongsToMany('App\Role');
    }

    public function results() {
        return $this->hasMany('App\Result');
    }   

     public function course()
    {
        return $this->belongsTo(Courses::class, 'course_id', 'id');
    } 

    public function hasRole($id_role)
    {
        return null !== $this->roles()->where('role_id', $id_role)->first();
    }

     public function updateRole($role_id)
    {
        \DB::table('role_user')->where('user_id', $this->id)->update(['role_id' => $role_id]);
        return $this;
    }

     /**
     * Sends the password reset notification.
     *
     * @param  string $token
     *
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomPassword($token));
    }


}


class CustomPassword extends ResetPassword
{
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('Вы получили это письмо, потому что мы получили запрос на изменение пароля для Вашей учетной записи.')
            ->action('Сброс пароля', url(config('app.url') . route('password.reset', $this->token, false)))
            ->line('Если вы не запрашивали сброс пароля, не требуется никаких дополнительных действий.');
    }
}