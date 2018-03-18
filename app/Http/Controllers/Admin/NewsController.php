<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\News as News;
use App\Notice as Notice;
use App\ContactUs as ContactUs;
use Validator;
use Image;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notices = Notice::all();
        $contactus = ContactUs::find(1);
        $news = News::latest()->simplePaginate(9);
        return view('pages.news', ['contactus' => $contactus, 'notices' => $notices, 'news' => $news]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.news.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // validation rules...
        $rules = [
            'title' => 'required|max:190',
            'newsImage' => 'image',
            'description' => 'required',
            'summary' => 'required|max:250'
        ];

        // Custom validation messages...
        $messages = [
            'newsImage.image' => 'Display image is required'
        ];

        // validator instance...
        $validator = Validator::make($request->all(), $rules, $messages);

        // if validation fails return error messages...
        if($validator->fails()) {
          // adding an additional field called 'error'...
          $validator->errors()->add('error', 'true');
          return response()->json($validator->errors());
        }
        // if no of stored works are below or equal to 3 then
        // store the record in the database...
        $image = $request->file('newsImage');
        $fileName = time() . '.' . $image->getClientOriginalExtension();
        $location = public_path('images/news-images/' . $fileName);
        Image::make($image)->resize(640, 400)->save($location);

        $news = new News;
        $news->title = $request->title;
        $news->description = $request->description;
        $news->image = $fileName;
        $news->summary = $request->summary;
        $news->save();

        // sending a response to the ajax...
        return 'success';
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $notices = Notice::latest()->get();
        $contactus = ContactUs::find(1);
        $new = News::find($id);
        return view('admin.news.show', ['contactus' => $contactus, 'new' => $new, 'notices' => $notices]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $new = News::find($id);
        return view('admin.news.edit', ['new' => $new]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // validation rules...
        $rules = [
            'title' => 'required|max:191',
            'description' => 'required',
            'summary' => 'required|max:250'
        ];

        // validator instance...
        $validator = Validator::make($request->all(), $rules);

        // if validation fails return error messages...
        if($validator->fails()) {
          // adding an additional field called 'error'...
          $validator->errors()->add('error', 'true');
          return response()->json($validator->errors());
        }

        $new = News::find($id);
        $new->title = $request->title;
        $new->description = $request->description;
        $new->summary = $request->summary;

        if($request->hasFile('newsImage')) {
            $image = $request->file('newsImage');
            $fileName = time() . '.' . $image->getClientOriginalExtension();
            $location = public_path('images/news-images/' . $fileName);
            Image::make($image)->resize(640, 400)->save($location);
            $new->image = $fileName;
        }
        $new->save();
        return 'success';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $new = News::find($id);
        $new->delete();
        return "success";
    }
}
