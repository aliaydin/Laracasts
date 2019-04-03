<?php

namespace App\Http\Controllers;

use App\Notifications\SubscriptionRenewalFailed;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Core Concepts: Middleware
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function singup()
    {
        return "If user is already login, dont show this page";
    }

    public function notify()
    {
        $user = auth()->user();
        $user->notify(new SubscriptionRenewalFailed);
        return 'done';
    }
}
