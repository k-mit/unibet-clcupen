<?php namespace App\Http\Middleware;

use Closure;
use Clockwork;
use Auth;
use Facebook\Authentication\AccessToken;
use Facebook\Exceptions\FacebookResponseException;
use Session;

class FacebookAdminMiddleware extends FacebookLoginMiddleware {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        Clockwork::startEvent('facebookMiddleware', 'starting the facebook authentication');

        $token = $this->handleFacebook();
        Clockwork::info($this->facebook_acesstoken instanceof AccessToken);
        if ($this->facebook_acesstoken instanceof AccessToken) {
            if (!$this->isAdmin($token)) {
                $this->applyNewRoute('notadmin');
            }
        }


        Clockwork::endEvent('facebookMiddleware');
        return $next($request);
    }


    private function isAdmin($token) {
        Clockwork::info($this->facebook_user);

        try {
            $request = $this->facebook->post('/' . config('laravel-facebook-sdk.facebook_config.app_id') . '/roles',
                                             array(
                                                 'user' => $this->facebook_user->getId(),
                                                 'role' => 'administrators'),
                                             $token);
        } catch (FacebookResponseException $exception) {
            Clockwork::info([$exception->getMessage(), $exception->getCode()]);
            return false;
        }

        Clockwork::info($request);
        return true;
    }

}
