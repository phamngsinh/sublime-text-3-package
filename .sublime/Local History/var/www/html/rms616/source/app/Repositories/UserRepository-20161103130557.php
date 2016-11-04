<?php
namespace App\Repositories;

use App\Models\User;
use Bosnadev\Repositories\Eloquent\Repository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Class UserRepository
 * @package App\Repositories
 */
class UserRepository extends Repository {

	/**
	 * @return User
	 */
	public function model() {
		return 'App\Models\User';
	}

	/**
	 * Calculate prodata
	 * remaining days / total days of year
	 * @param User $user
	 * @return int
	 *
	 */
	public function getProData(User $user) {
		if (!$user->expiration) {
			return 0;
		}
		$expiration = $user->expiration->diffInDays(Carbon::now());

		$firstDayOfYear = Carbon::create(Carbon::now()->format('Y'), 1, 1);
		$lastDayOfYear = Carbon::create(Carbon::now()->format('Y'), 12, 31);
		$dayOfYear = $firstDayOfYear->diffInDays($lastDayOfYear);
		return (int) ceil($expiration / $dayOfYear);
	}

	/**
	 * @param int $prodata
	 * @param int $price
	 * @return int
	 */
	public function getCreditUnused(User $user, $proData = 0, $oldPrice = 0) {
		if (!$proData) {
			$proData = $this->getProData($user);
		}
		if (!$oldPrice) {
			$oldPrice = !empty($user->subscriptionPackages) ? $user->subscriptionPackages->price : 0;
		}
		return $proData * $oldPrice;
	}

	/**
	 * Amount Due = Price new - CreditUnused
	 */
	public function getAmountDue($priceNew, $creditUnused) {
		return $priceNew - $creditUnused;
	}

	/**
	 * Check token exist or note
	 * @param $token
	 * @param $email
	 */
	public function getTokenResetLink($token, $email) {
		return DB::table('password_resets')->where('token', $token)->where('email', $email)->first();
	}

	public function checkTokenExpired($token, $email) {

		$token = DB::table('password_resets')
			->where('token', '=', $token)
			->where('email', '=', $email)
			->first();
		$expired = new Carbon($token->created_at);
		return Carbon::now() > $expired->addMinutes(config('passwords.users.expire'));
	}
}
