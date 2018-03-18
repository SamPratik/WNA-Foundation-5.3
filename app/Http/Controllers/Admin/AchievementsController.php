<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notice as Notice;
use App\Achievement as Achievement;
use App\ContactUs as ContactUs;
use Validator;
use Image;

class AchievementsController extends Controller
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
        $achievements = Achievement::latest()->paginate(9);
        return view('pages.achievements', ['contactus' => $contactus, 'achievements' => $achievements,'notices' => $notices]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.achievements.add');
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
            'achImage' => 'image',
            'description' => 'required',
            'summary' => 'required|max:250'
        ];

        // Custom validation messages...
        $messages = [
            'achImage.image' => 'Display image is required'
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
        $image = $request->file('achImage');
        $fileName = time() . '.' . $image->getClientOriginalExtension();
        $location = public_path('images/achievement-images/' . $fileName);
        Image::make($image)->resize(640, 400)->save($location);

        $achievement = new Achievement;
        $achievement->title = $request->title;
        $achievement->description = $request->description;
        $achievement->image = $fileName;
        $achievement->summary = $request->summary;
        $achievement->save();

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
        $achievement = Achievement::find($id);
        $contactus = ContactUs::find(1);
        $notices = Notice::latest()->get();
        return view('admin.achievements.show', ['contactus' => $contactus, 'notices' => $notices, 'achievement' => $achievement]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $achievement = Achievement::find($id);
        return view('admin.achievements.edit', ['achievement' => $achievement]);
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

        $achievement = Achievement::find($id);
        $achievement->title = $request->title;
        $achievement->description = $request->description;
        $achievement->summary = $request->summary;

        if($request->hasFile('achImage')) {
            $image = $request->file('achImage');
            $fileName = time() . '.' . $image->getClientOriginalExtension();
            $location = public_path('images/achievement-images/' . $fileName);
            Image::make($image)->resize(640, 400)->save($location);
            $achievement->image = $fileName;
        }
        $achievement->save();
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
        $achievement = Achievement::find($id);
        $achievement->delete();
        return 'success';
    }
}
