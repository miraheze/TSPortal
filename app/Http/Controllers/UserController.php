<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Controller for all user related actions outside a login
 */
class UserController extends Controller
{
    /**
     * Shows a list of all users
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('user')->with('users', User::all());
    }

    /**
     * Show a specific user page
     *
     *
     * @return Application|Factory|View
     */
    public function show(User $user)
    {
        return view('user.view')->with('user', $user);
    }

    /**
     * Update a users flags
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $user->updateFlags($request->input('new-access') ?? [], $request->user());

        return back();
    }
}
