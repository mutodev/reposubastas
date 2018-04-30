<?php

namespace App\Http\Middleware;

use Closure;

class GenerateMenus
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
        \Menu::make('SidebarMenu', function ($menu) use ($request) {
            $eventId = $request->route()->parameter('event');

            $menu->add('<span title="icon calendar" aria-hidden="true" class="oi oi-calendar"></span> '.__('Events'), ['route' => 'backend.events.index', 'class' => 'nav-item'])->link->attr(['class' => 'nav-link']);

            if ($eventId) {
                $menu->add('<span title="icon home" aria-hidden="true" class="oi oi-home"></span> ' . __('Properties'), ['url' => route('backend.properties.index', ['event' => $eventId]), 'class' => 'nav-item'])->link->attr(['class' => 'nav-link']);
            }

            $menu->add('<span title="icon people" aria-hidden="true" class="oi oi-people"></span> '.__('Users'), ['url' => route('backend.users.index'), 'class' => 'nav-item'])->link->attr(['class' => 'nav-link']);
        });

        return $next($request);
    }
}
