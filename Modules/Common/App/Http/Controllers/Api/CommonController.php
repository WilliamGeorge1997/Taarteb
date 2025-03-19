<?php

namespace Modules\Common\App\Http\Controllers\Api;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Modules\Common\App\Emails\ContactUsEmail;
use Modules\Common\App\Http\Requests\ContactRequest;

class CommonController extends Controller
{
    public function contact(ContactRequest $request)
    {
        try {
            Mail::to(env('MAIL_USERNAME'))->send(new ContactUsEmail($request->validated()));
            return returnMessage(true, 'Contact message sent successfully', null);
        } catch (Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 500);
        }
    }
}