<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class IsLead
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $is_guest = $this->auth->guest();
        $is_admin = $this->auth->user()->hasRole('admin');
        $is_department_lead = !$this->auth->user()->hasRole('department-lead');
        if($is_guest || (!$is_admin && !$is_department_lead))
        {
            return response('Unauthorized.', 401);
        }

        return $next($request);
    }
}
