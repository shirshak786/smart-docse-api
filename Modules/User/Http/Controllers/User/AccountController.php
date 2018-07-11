<?php

namespace Modules\User\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mcamara\LaravelLocalization\LaravelLocalization;
use Modules\User\Contracts\AccountRepository;
use Modules\User\Http\Requests\UpdateAccountRequest;

class AccountController extends Controller
{
    protected $account;

    protected $localization;

    public function __construct(AccountRepository $account, LaravelLocalization $localization)
    {
        $this->account = $account;
        $this->localization = $localization;
    }

    public function index(Request $request)
    {
        $locales = collect($this->localization->getSupportedLocales())->map(function ($item) {
            return $item['native'];
        });

        return view('user.account')->withLocales($locales)->withTimezones(\DateTimeZone::listIdentifiers());
    }

    public function update(UpdateAccountRequest $request)
    {
        $this->account->update($request->input());

        return redirect()->route('user.account')
            ->withFlashSuccess(__('labels.user.profile_updated'));
    }

    public function sendConfirmation()
    {
        $this->account->sendConfirmation();

        return redirect()->back()
            ->withFlashSuccess(__('labels.user.email_confirmation_sended'));
    }


    public function confirmEmail($token)
    {
        $this->account->confirmEmail($token);

        return redirect()->route('user.account')
            ->withFlashSuccess(__('labels.user.email_confirmed'));
    }


    public function changePassword(Request $request)
    {
        $request->headers->set('referer', route('user.account').'#password');

        $this->validate($request, [
            'password' => 'required|min:6|confirmed',
        ]);

        $this->account->changePassword(
            $request->get('old_password'),
            $request->get('password')
        );

        return redirect()->route('user.account')
            ->withFlashSuccess(__('labels.user.password_updated'));
    }

    public function delete(Request $request)
    {
        $this->account->delete();

        auth()->logout();
        $request->session()->flush();
        $request->session()->regenerate();

        return redirect()->route('home')
            ->withFlashSuccess(__('labels.user.account_deleted'));
    }
}
