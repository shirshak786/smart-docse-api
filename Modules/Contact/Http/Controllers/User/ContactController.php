<?php

namespace Modules\Contact\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Modules\Contact\Http\Requests\StoreContactRequest;
use Modules\Contact\Models\Contact\Contact;
use function response;

class ContactController extends Controller
{
    public function store(StoreContactRequest $request)
    {
        $contact = Contact::make($request->all());
        $contact->ip = $request->ip();
        $contact->save();

        return response()->json([
            'data' => [
                'message' => 'Your message has succesfully been recieved. If we need to reply we will contact you via email you supplied. Thanks'
            ]
        ])->setStatusCode(201);
    }
}
