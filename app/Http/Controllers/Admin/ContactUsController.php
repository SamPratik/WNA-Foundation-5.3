<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ContactUs as ContactUs;

class ContactUsController extends Controller
{
    public function update(Request $request, $id) {
        $contactus = ContactUs::find($id);
        $contactus->address = $request->address;
        $contactus->phone = $request->phone;
        $contactus->email = $request->email;
        $contactus->save();

        return 'success';
    }
}
