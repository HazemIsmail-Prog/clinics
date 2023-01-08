<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */


    // default function ***************************************************************************************

//    protected function redirectTo($request)
//    {
//        if (! $request->expectsJson()) {
//            return route('login');
//        }
//
//    }

//**************************************************************************************************************



// override function to check if auth user is active ***********************************************************
    protected function authenticate($request, array $guards)
    {
        parent::authenticate($request, $guards);

        // Got here? good! it means the user is session authenticated. now we should check if it authorize
        if (!auth()->user()->active) {
            auth()->logout();
            $this->unauthenticated($request, $guards);
        }
    }

    //************************************************************************************************************
}
