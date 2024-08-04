<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::get();
        return view('file-upload',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            // 'photo'=>'required | mimes:png,jpg,jpeg | max:3000 | dimensions:min_width:100,min_height:100,max_height:1000,max_width:1000,'
            'photo'=>'required | mimes:png,jpg,jpeg | max:3000'
        ]);
        
        $file = $request->file('photo');
        
        // $extension = $file->getClientOriginalExtension();
        // $extension = $file->extension();
        // $hashName = $file->hashName();
        // $fileType = $file->getClientMimeType();
        // $fileSize = $file->getSize();
        // return $fileSize;
        
        //  $path = $request->photo->store('images','local');
        // $fileName =time()."_". $file->getClientOriginalName();
        //  $path = $request->file('photo')->storeAs('images',$fileName,'public');
        $path = $request->file('photo')->store('images','public');
        User::create([
            'file_name'=>$path
        ]);
        
        // return $path;
        // dd($file);
        // return $file;
         return redirect()->route('user.index')->with('status', 'User Image Uploaded Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {   
          $user = User::find($id);

        return view('file-update', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
     $user = User::find($id);
     if($request->hasFile('photo')){
        $image_path = public_path('storage/'). $user->file_name;
         if(file_exists($image_path)){
            @unlink($image_path);
        }
        $path = $request->photo->store('images','public');
        $user->file_name = $path;
        $user->save();
        return redirect()->route('user.index')->with('status', 'User Image Updated Successfully');
     }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
     $user=  User::find($id);
     $user->delete();
     $image_path = public_path('storage/'). $user->file_name;
    //  return $image_path;
     if(file_exists($image_path)){
        @unlink($image_path);
    }
    return redirect()->route('user.index')->with('status', 'User Image Deleted Successfully');
    }
}
