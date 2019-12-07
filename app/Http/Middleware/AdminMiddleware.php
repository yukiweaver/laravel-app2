<?php

namespace App\Http\Middleware;

use Closure;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      $user = auth()->user();
      $adminFlg = $user->admin_flg;
      $superiorFlg = $user->superior_flg;
      $flgData = [
        'admin_flg' => $adminFlg,
        'superior_flg' => $superiorFlg,
      ];
      $request->merge(['flg_data' => $flgData]);
      return $next($request);
    }
}
