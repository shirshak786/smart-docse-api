<?php

namespace Modules\Blog\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Arcanedev\NoCaptcha\Rules\CaptchaRule;
use Modules\Core\Http\Controllers\User\UserController;

class PagesController extends UserController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function about()
    {
        return view('frontend.pages.about')->withFlashMessage('Hey ! I\'m a flash message !');
    }

    public function contact(Request $request)
    {

    }

    public function contactSent()
    {
        return view('frontend.pages.contact-sent', compact('message'));
    }

    public function legalMentions()
    {
        return view('frontend.pages.legal-mentions');
    }
}
