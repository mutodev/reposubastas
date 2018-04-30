<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasRoles;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function events()
    {
        return $this->belongsToMany('App\Models\Event', 'user_event')->withPivot('original_deposit', 'remaining_deposit', 'number', 'is_active');
    }

    public function addToEvent($eventId, $deposit = null, $number = null)
    {
        $this->events()->detach($eventId);
        $this->events()->attach($eventId, [
            'original_deposit' => $deposit,
            'remaining_deposit' => $deposit,
            'number' => $number,
            'is_active' => true
        ]);
    }

    public function decrementDepositForEvent($eventId, $amount)
    {
        DB::table('user_event')
            ->where('event_id', '=', $eventId)
            ->where('user_id', '=', $this->id)
            ->decrement('remaining_deposit', $amount);
    }

    public static function url($action, $modelId = null, $eventId = null)
    {
        $routeParts = ['backend'];

        if ($eventId) {
            $routeParts[] = 'event';
        }

        $routeParts[] = 'users';
        $routeParts[] = $action;

        return route(implode('.', $routeParts), ['event' => $eventId, 'model' => $modelId]);
    }
}
