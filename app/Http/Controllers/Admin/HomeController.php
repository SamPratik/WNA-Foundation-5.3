<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\AboutUs as AboutUs;
use App\Work as Work;
use App\Notice as Notice;
use App\ContactUs as ContactUs;

class HomeController extends Controller
{
    // return home view...
    public function home() {
        $aboutUs = DB::table('about_us')->select('description')->where('id', 1)->get();
        $works = Work::all();
        $notices = Notice::all();
        $contactus = ContactUs::find(1);
        return view('pages.home', ['contactus' => $contactus,'aboutUs' => $aboutUs, 'works' => $works, 'notices' => $notices]);
    }

    // show about us text in the modal...
    public function aboutUsText() {
      $aboutUs = AboutUs::find(1);
      return $aboutUs;
    }

    public function updateAboutUs(Request $request) {
        $aboutUs = AboutUs::find(1);
        $aboutUs->description = $request->description;
        $aboutUs->save();

        return "About us updated successfully";
    }
}
