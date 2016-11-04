<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Redirect;

/**
 * Class PasswordController
 * @package App\Http\Controllers\Auth
 */
class PasswordController extends Controller {
	/*
		    |--------------------------------------------------------------------------
		    | Password Reset Controller
		    |--------------------------------------------------------------------------
		    |
		    | This controller is responsible for handling password reset requests
		    | and uses a simple trait to include this behavior. You're free to
		    | explore this trait and override any methods you wish to tweak.
		    |
	*/
	use ResetsPasswords;
	/**
	 * @var string
	 */
	protected $redirectPath = '/';
	/**
	 * @var \App\Repositories\UserRepository
	 */
	protected $user;
	/**
	 * Create a new password controller instance.
	 *
	 * @return void
	 */
	public function __construct(UserRepository $userRepository) {
		$this->subject = config('mail')['subject_reset'];
		$this->middleware('guest');
		$this->user = $userRepository;
	}

	/**
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function sendResetLinkEmail(Request $request) {
		$user = User::where('email', Input::get('email'))->first();
		if (!empty($user->id) && ($user->is_active == config('administrator.user.inActiveStatus'))) {
			return Redirect::to('password/reset')
				->with('errors_messages', trans('messages.account_deactivated'));
		}

		$this->validate($request, ['email' => 'required|email']);

		$broker = $this->getBroker();
		$response = Password::broker($broker)->sendResetLink(
			$this->getSendResetLinkEmailCredentials($request),
			$this->resetEmailBuilder()
		);

		switch ($response) {
		case Password::RESET_LINK_SENT:
			return $this->getSendResetLinkEmailSuccessResponse($response);
		case Password::INVALID_USER:
		default:
			return $this->getSendResetLinkEmailFailureResponse($response);
		}
	}

	/**
	 * Override method base
	 * @param \Illuminate\Http\Request $request
	 * @param null                     $token
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function showResetForm(Request $request, $token = null) {
		if (is_null($token)) {
			return $this->getEmail();
		}
		$email = $request->input('email');
		$user = $this->user->getTokenResetLink($token, $email);
		if (!$user) {
			return Redirect::to('/login')->with('errors_messages', trans('messages.link_reset_password_not_valid'));
		}
		$tokenExpired = $this->user->checkTokenExpired($token, $email);
		if (!$tokenExpired) {
			return Redirect::to('/login')->with('errors_messages', trans('messages.account_link_expired'));
		}

		if (property_exists($this, 'resetView')) {
			return view($this->resetView)->with(compact('token', 'email'));
		}

		if (view()->exists('auth.passwords.reset')) {
			return view('auth.passwords.reset')->with(compact('token', 'email'));
		}

		return view('auth.reset')->with(compact('token', 'email'));
	}

}
