<?php namespace Ghi\Core\Domain\Usuarios;

use Ghi\Core\Domain\Usuarios\UserPresenter;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Laracasts\Presenter\PresentableTrait;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
	use Authenticatable, CanResetPassword, PresentableTrait;

    /**
     * @var string
     */
    protected $connection = 'igh';

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'usuario';

    /**
     * @var string
     */
    protected $primaryKey = 'idusuario';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['nombre', 'correo', 'clave'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['clave', 'remember_token'];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * Presentador de usuario
     *
     * @var
     */
    protected $presenter = UserPresenter::class;

    /**
     * Usuario cadeco relacionado con este usuario de intranet
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function usuarioCadeco()
    {
        return $this->hasOne(UsuarioCadeco::class, 'usuario', 'usuario');
    }

}
