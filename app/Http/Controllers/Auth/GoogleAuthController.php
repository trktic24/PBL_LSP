<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Support\Str;


class GoogleAuthController extends Controller
{
    public function redirect(Request $request)
{
    if ($request->has('role')) {
        session(['role_register' => $request->get('role')]);
    }

    return Socialite::driver('google')->redirect();
}


    public function callback()
{
    $googleUser = Socialite::driver('google')->stateless()->user();

    $roleName = session('role_register', 'asesi');
    if (!$roleName) abort(400, 'Role missing.');

    $role = Role::where('nama_role', $roleName)->firstOrFail();

    $user = User::firstOrCreate(
        ['google_id' => $googleUser->getId()],
        [
            'email' => $googleUser->getEmail(),
            'role_id' => $role->id,
            'password' => bcrypt(Str::random(16)),
        ]
    );

    Auth::login($user);

    return redirect()->route('register', [
        'step' => 2,
        'role' => $roleName,
    ])->with('registered_with_google', true);
}


}
