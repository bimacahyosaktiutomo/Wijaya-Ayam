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
    public function dashboard()
    {
        $users = User::latest()->get();

        return Inertia::render('Admin/UserDashboard', [
            'users' => $users,
        ]);
    }

    public function edit($id)
    {
        $users = User::findOrFail($id);
        return Inertia::render('Admin/UserForm', [
            'users' => $users,
        ]);
    }

    public function update(Request $request, $id)
    {
        $users = User::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id . ',id_pengguna', //Biar gak ngecek email dan no hp nya sendiri pake yg 
            'no_telepon' => 'required|string|max:20|unique:users,no_telepon,' . $id . ',id_pengguna', // --> habis titik titik
        ]);
        $users->update($validatedData);

        return Redirect::route('dashboard.user');
    }

    public function destroy($id)
    {

        $user = User::findOrFail($id);
        if ($id != 1 && $user) {
            $user->delete();
        }

        return Redirect::route('dashboard.user');
    }
}
