<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Throwable;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function processLogin(Request $request)
    {

        try {
            $user = User::query()
                ->where('email', $request->get('email'))
                    // ->where('password', $request->get('password'))
                ->firstOrFail();
            if (!Hash::check($request->get('password'), $user->password)) {
                throw new Exception('Invalid password');
            }
            // dd($user);
            session()->put('id', $user->id);
            session()->put('name', $user->name);
            session()->put('level', $user->level);

            return redirect()->route('books.index');

        } catch (Throwable $th) {
            return redirect()->route('login');
        }

    }

    public function logout()
    {
        session()->flush();
        return redirect()->route('login');
    }


    public function register()
    {
        return view('auth.register');
    }
    public function processRegister(Request $request)
    {
        $user = new User();


        $user->fill($request->except([
            '_token',
            'password',
        ]));
        $user->fill([
            'password' => Hash::make($request->get('password'))
        ]);

        $user->save();
        // dd($user);

        // $user = User::query()
        //     ->create([
        //         'name' => $request->get('name'),
        //         'email' => $request->get('email'),
        //         'password' => Hash::make($request->get('password')),
        //         'level' => $request->get('level'),
        //         'phone' => $request->get('phone'),
        //         'address' => $request->get('address'),
        //         'gender' => $request->get('gender'),
        //         'birthdate' => $request->get('birthdate'),
        //     ]);
        // Book::create($request->except('_token'));
        return redirect()->route('users.index');
    }

}
