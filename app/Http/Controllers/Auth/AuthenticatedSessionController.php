<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;
use Darryldecode\Cart\Facades\CartFacade as Cart;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        
        if (session()->get(Auth::id() . '_cart')) {
            $cart = session()->get(Auth::id() . '_cart');
            Cart::session(Auth::id())->add($cart->toArray());
        }
        
        return back();
        // return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        if (!Cart::session(Auth::id())->getContent()->isEmpty()) {
            $cart = Cart::session(Auth::id())->getContent();
            $auth_id = Auth::id();
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if (isset($cart, $auth_id)) {
            session()->put($auth_id . '_cart', $cart);
        }

        return to_route('home');
    }
}
