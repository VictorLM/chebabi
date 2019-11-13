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
        'nascimento', 'foto', 'last_login'
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
        $user_bday = Carbon::parse($this->nascimento)->format('d/m');
        $today = Carbon::today();
        if($user_bday == $today->format('d/m')){
            return true;
        }
        if($today->isMonday()){
            $saturday = $today->subDay()->format('d/m');
            $sunday = $today->subDay()->format('d/m');
            if($saturday == $user_bday){
                return true;
            }else if($sunday == $user_bday){
                return true;
            }
        }
        return false;
    }

    public function is_admin(){
        if($this->tipo == 'admin'){
            return true;
        }else{
            return false;
        }
    }

    public function is_admin_recepcao(){
        if($this->tipo == 'admin' || $this->email == 'recepcao@chebabi.com'){
            return true;
        }else{
            return false;
        }
    }

}
