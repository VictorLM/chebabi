<?php

namespace Intranet;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Carbon\Carbon;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'ativo', 'tipo', 'ramal', 'telefone', 
        'nascimento', 'foto', 'uaus', 'last_login'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function is_bday(){
        if(Carbon::parse($this->nascimento)->format('d/m') == Carbon::parse(Carbon::today())->format('d/m')){
            return true;
        }else{
            return false;
        }
    }

    public function is_congratulable(){
        $user_bday = Carbon::parse($this->nascimento);
        $today = Carbon::today();
        if(Carbon::parse($user_bday)->format('d/m') == Carbon::parse($today)->format('d/m')){
            return true;
        }else if($today->isMonday() && Carbon::parse($today->subDay())->format('d/m') == Carbon::parse($user_bday)->format('d/m')){
            //IF BDAY = SÁBADO
            return true;
        }else if($today->isMonday() && Carbon::parse($today->subDays(2))->format('d/m') == Carbon::parse($user_bday)->format('d/m')){
            //IF BDAY = DOMINGO
            return true;
        }else{
            return false;
        }
    }
}
