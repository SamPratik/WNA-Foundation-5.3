<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Image;
use Validator;
use App\ContactUs as ContactUs;
// use Purifier;
use App\Work as Work;
use App\Notice as Notice;

class WorkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.works.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $work_count = Work::count();

        // validation rules...
        $rules = [
            'title' => 'required|max:191',
            'workImage' => 'image',
            'description' => 'required',
            'summary' => 'required|max:250'
        ];
        // custom validation messages...
        $messages = [
            'workImage.image' => 'Display image must be an image'
        ];
        // validator instance...
        $validator = Validator::make($request->all(), $rules, $messages);

        // if validation fails return error messages...
        if($validator->fails()) {
          // adding an additional field called 'error'...
          $validator->errors()->add('error', 'true');
          return response()->json($validator->errors());
        }

        // if validation passes check for how many works are stored...
        if($work_count <= 2) {
            // if no of stored works are below or equal to 3 then
            // store the record in the database...
            $image = $request->file('workImage');
            $fileName = time() . '.' . $image->getClientOriginalExtension();
            $location = public_path('images/work-images/' . $fileName);
            Image::make($image)->resize(640, 400)->save($location);

            $work = new Work;
            $work->title = $request->title;
            $work->description = $request->description;
            $work->image = $fileName;
            $work->summary = $request->summary;
            $work->save();

            // sending a response to the ajax...
            return 'success';
        } else {
            // if no of stored works are more than 3 then do not
            // store the record in the database...
            return 'more than 3 works';
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $work = Work::find($id);
        $contactus = ContactUs::find(1);
        $notices = Notice::all();
        return view('admin.works.show', ['work' => $work, 'notices' => $notices, 'contactus' => $contactus]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $work = Work::find($id);
        return view('admin.works.edit', ['work' => $work]);
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

        $work = Work::find($id);
        $work->title = $request->title;
        $work->description = $request->description;
        $work->summary = $request->summary;

        if($request->hasFile('workImage')) {
            $image = $request->file('workImage');
            $fileName = time() . '.' . $image->getClientOriginalExtension();
            $location = public_path('images/work-images/' . $fileName);
            Image::make($image)->resize(640, 400)->save($location);
            $work->image = $fileName;
        }
        $work->save();
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
        $work = Work::find($id);
        $work->delete();
        return "success";
    }
}
