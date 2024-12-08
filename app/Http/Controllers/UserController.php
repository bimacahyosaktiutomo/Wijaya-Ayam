<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    //
    public function edit($id): Response
    {
        $users = User::findOrFail($id);
        return Inertia::render('Profile/Edit', [
            'user' => $users,
        ]);
    }

    public function update(ProfileUpdateRequest $request, $id)
    {
        $users = User::findOrFail($id);
        $request->$users->fill($request->validated());

        $request->user()->save();

        return Redirect::route('dashboard');
    }
}
