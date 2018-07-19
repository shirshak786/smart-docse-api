<?php

namespace Modules\Contact\Http\Controllers\User;

use function response;
use App\Http\Controllers\Controller;
use Modules\Contact\Models\Contact\Contact;
use Modules\Contact\Http\Requests\StoreContactRequest;

class ContactController extends Controller
{
    public function store(StoreContactRequest $request)
    {
        $contact = Contact::make($request->all());
        $contact->ip = $request->ip();
        $contact->save();

        return response()->json([
            'data' => [
                'message' => 'Your message has succesfully been recieved. If we need to reply we will contact you via email you supplied. Thanks',
            ],
        ])->setStatusCode(201);
    }
}
