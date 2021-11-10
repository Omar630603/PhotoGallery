<?php

namespace App\Http\Controllers;

use App\Models\Oracle;
use App\Models\Photo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class PhotoController extends Controller
{
    public function oracle()
    {
        $data = new Oracle;
        return $data;
    }
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'images' => 'required',
          ]);
          $user = User::where('id_user', Auth::user()->id_user)->first();
          if ($request->hasfile('images')) {
              $images = $request->file('images');
  
              foreach($images as $image) {
                //oci
                $imagefirstname = uniqid() .'-'.Auth::user()->name;
                $extension = $image->getClientOriginalExtension();
                
                $file_name = "{$imagefirstname}.{$extension}";
                $ociFilePath = 'user_images/' . $user->id_user.'/photos' ;
                $upload = $image->move($ociFilePath,$file_name);
                $this->oracle()->upFileOracle($upload);
                File::delete($upload);
                // local
                // $ociFilePath = $image->store('user_images', 'public'); 
                $name = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $photo = new Photo;
                $photo->user()->associate($user);
                $photo->title = $name;
                $photo->img = $upload;
                $photo->file_name = $file_name;
                $photo->extension = $extension;
                $photo->save();
              }
           }
          return back()->with('success', 'Images uploaded successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function show(Photo $photo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function edit(Photo $photo)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Photo $photo)
    {
        $old_title = $photo->title;
        $old_description = $photo->description;
        $photo->title = $request->get('title');
        $photo->description = $request->get('description');
        $photo->save();
        $msg = 'Photo '.$old_title.' edited successfully to -> '. 
        $request->get('title'). '<br> The old description '.$old_description.' to -> '. $request->get('description'). '.';
        return back()->with('success', $msg);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Photo $photo)
    {
        $path = 'images/user_images/'.Auth::user()->id_user.'/photos\\'.$photo->file_name;
        $old_title = $photo->title;
        Storage::delete($path);
        $msg = 'Photo '.$old_title.' has been deleted successfully.';
        $photo->delete();
        return back()->with('success', $msg);
    }
    public function deleteAll()
    {
        $photos =Photo::where('id_user', Auth::user()->id_user)->get();
        foreach ($photos as $photo) {
            Storage::disk('s3')->delete('images/' . $photo->img);
            $photo->delete();
        }
        return back()->with('success', 'All images have been deleted');
    }
    public function download(Photo $photo){
        $path = Storage::url('images/'.$photo->img);
        $filename = $photo->title.'.'.$photo->extension;
        $tempImage = tempnam(sys_get_temp_dir(), $filename);
        copy($path, $tempImage);
        return response()->download($tempImage, $filename);
    }
}
