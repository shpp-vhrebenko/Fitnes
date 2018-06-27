<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrainingSchedule extends Model
{
    protected $table="training_schedule";

    protected $fillable = [
        'course_id', 
        'item_id',
        'week',
        'day'
    ]; 

    public static function get_training_schedule_course( $course_id ){

        $TrainingScheduleCourse = self::where('course_id', $course_id)->get('day');        

        return $TrainingScheduleCourse;
    }

   
}
