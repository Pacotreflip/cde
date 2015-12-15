<?php

namespace  Ghi\Equipamiento\Autenticacion;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Ghi\Core\App\Auth\AuthenticatableIntranetUser;

class User extends \Ghi\Core\Models\User implements AuthenticatableContract, CanResetPasswordContract
{
    use AuthenticatableIntranetUser,  CanResetPassword, EntrustUserTrait;
    protected $table = 'igh.usuario';
}
