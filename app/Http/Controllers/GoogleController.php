<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function callback()
    {
        $g = Socialite::driver('google')->stateless()->user();
        $user = User::where('google_id', $g->getId())->orWhere('email', $g->getEmail())->first();

        if (!$user) {
            $user = User::create([
                'nombre'    => $g->getName(),
                'email'     => $g->getEmail(),
                'google_id' => $g->getId(),
            ]);
        } elseif (!$user->google_id) {
            $user->update(['google_id' => $g->getId()]);
        }

        Auth::login($user);
        return redirect()->route('listas.index');
    }

    public function logout(Request $r)
    {
        Auth::logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();
        return redirect()->route('login');
    }
}