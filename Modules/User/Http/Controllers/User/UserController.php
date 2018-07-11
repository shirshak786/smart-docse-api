<?php

namespace Modules\User\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\User\Contracts\UserRepository;

class UserController extends Controller
{

    protected $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    public function index(Request $request)
    {
        return view('user.home');
    }
}
