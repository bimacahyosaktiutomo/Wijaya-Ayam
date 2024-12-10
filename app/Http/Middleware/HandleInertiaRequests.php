<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;
use Illuminate\Support\Facades\Route;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
                'role' => fn () => $request->user() 
                ? $request->user()->getRoleNames()->first() // Gets the user's primary role
                : null,
            ],
            'ziggy' => fn () => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],

            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'cartInfo' => Auth::id() ? [
                'cartItems' => Cart::session(Auth::id())->getContent(),
                // 'totalCartItems' => Cart::session(Auth::id())->getTotalQuantity(),
                'totalCartItems' => Cart::session(Auth::id())->getContent()->count(), // Gak termasuk quantity per item
                'totalPrice' => Cart::session(Auth::id())->getSubTotal(),
            ] : null,
            // 'role' => User::findOrFail(Auth::id())->hasRole('admin'),
        ];
    }
}
