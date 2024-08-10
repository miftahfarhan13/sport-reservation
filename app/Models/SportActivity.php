<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SportActivity extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sport_activities';


    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    public function participants()
    {
        return $this->participants_relation()->with('user');
    }

    public function participants_relation()
    {
        return $this->hasMany(SportActivityParticipant::class, 'sport_activity_id', 'id');
    }

    public function sport_category()
    {
        return $this->hasOne(SportCategory::class, 'id', 'sport_category_id');
    }

    public function organizer()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function city()
    {
        return $this->city_activity()->with('province');
    }

    public function city_activity()
    {
        return $this->hasOne(City::class, 'city_id', 'city_id');
    }
}
