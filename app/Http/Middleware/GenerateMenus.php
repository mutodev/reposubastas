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
                $menu->add('<span title="icon home" aria-hidden="true" class="oi oi-home"></span> ' . __('Event \ Properties'), ['url' => route('backend.properties.index', ['event' => $eventId]), 'class' => 'nav-item'])->link->attr(['class' => 'nav-link']);
                $menu->add('<span title="icon people" aria-hidden="true" class="oi oi-people"></span> ' . __('Event \ Users'), ['url' => route('backend.event.users.index', ['event' => $eventId]), 'class' => 'nav-item'])->link->attr(['class' => 'nav-link']);
            }

            $menu->add('<span title="icon envelope-open" aria-hidden="true" class="oi oi-envelope-open"></span> '.__('Deposits'), ['url' => route('backend.users.deposits'), 'class' => 'nav-item'])->link->attr(['class' => 'nav-link']);
            $menu->add('<span title="icon envelope-open" aria-hidden="true" class="oi oi-envelope-open"></span> '.__('Offers'), ['url' => route('backend.users.offers'), 'class' => 'nav-item'])->link->attr(['class' => 'nav-link']);
            $menu->add('<span title="icon building" aria-hidden="true" class="oi oi-document"></span> '.__('Tags'), ['url' => route('backend.tags.index'), 'class' => 'nav-item'])->link->attr(['class' => 'nav-link']);
            $menu->add('<span title="icon building" aria-hidden="true" class="oi oi-document"></span> '.__('Investors'), ['url' => route('backend.investors.index'), 'class' => 'nav-item'])->link->attr(['class' => 'nav-link']);
            $menu->add('<span title="icon people" aria-hidden="true" class="oi oi-people"></span> '.__('Administrators'), ['url' => route('backend.users.index'), 'class' => 'nav-item'])->link->attr(['class' => 'nav-link']);
            $menu->add('<span title="icon page" aria-hidden="true" class="oi oi-document"></span> '.__('Pages'), ['url' => route('backend.pages.index'), 'class' => 'nav-item'])->link->attr(['class' => 'nav-link']);
        });

        return $next($request);
    }
}
