<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/huesped';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
	 * Validate the user login request.
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return void
	 *
	 * @throws \Illuminate\Validation\ValidationException
	*/
	protected function validateLogin(Request $request)
	{
		$request->validate([
			$this->username()    => 'required|string',
			'USUARI_Clave_____b' => 'required|string',
		]);
    }
    
    /**
	 * @param $credentials
	 *
	 * @return bool
	*/
	private function attempt($credentials, $remember = false)
	{
		$user = User::where('USUARI_Correo___b', $credentials['USUARI_Correo___b'])
			->where('USUARI_Cargo_____b', 'super-administrador')
			->where('USUARI_Clave_____b', $this->encriptaPassword($credentials['USUARI_Clave_____b']))
			->first();

		if ($user)
		{
			Auth::login($user, $remember);
		}

		return $user;
    }
    
    /**
	 * Attempt to log the user into the application.
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return bool
	*/
	protected function attemptLogin(Request $request)
	{
		return $this->attempt(
			$this->credentials($request), $request->filled('remember')
		);
    }
    
    /**
	 * Get the needed authorization credentials from the request.
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return array
	*/
	protected function credentials(Request $request)
	{
		return $request->only($this->username(), 'USUARI_Clave_____b');
    }
    
    /**
	 * Get the login username to be used by the controller.
	 *
	 * @return string
	 */
	public function username()
	{
		return 'USUARI_Correo___b';
	}
}
