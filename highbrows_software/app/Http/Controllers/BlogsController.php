<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Storage;

class BlogsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $blogs = Blog::all(); 
        return view('admin.blogs', compact('blogs')); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.add-blogs');
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
            'heading' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'nullable|string',
        ]);
    
       
        $file = $request->file('image'); 
        $folderName = 'blogs';
        $fileName = time() . '-' . $file->getClientOriginalName();
        $filePath = $file->storeAs($folderName, $fileName, 'public'); 
    
      
        Blog::create([
            'heading' => $request->heading,
            'image' => $folderName . '/' . $fileName, 
            'description' => $request->description,
        ]);
    
        return redirect()->route('blogs.index')->with('success', 'Blog added successfully!');
    }
    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $blog = Blog::findOrFail($id); 

   
    return view('admin.edit-blogs', compact('blog'));
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
        $blog = Blog::findOrFail($id);

        $request->validate([
            'heading' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'nullable|string',
        ]);
    

        $blog->heading = $request->heading;
        $blog->description = $request->description;
    
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $folderName = 'blogs';
            $fileName = time() . '-' . $file->getClientOriginalName();
            $filePath = $file->storeAs($folderName, $fileName, 'public');
    
 
            if ($blog->image) {
                Storage::delete('public/' . $blog->image);
            }
    
            $blog->image = $folderName . '/' . $fileName;
        }
    
        $blog->save();
    
        return redirect()->route('blogs.index')->with('success', 'Blog updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $blog = Blog::findOrFail($id); 
        if ($blog->image) {
            Storage::delete('public/' . $blog->image);
        }
        $blog->delete();
        return redirect()->route('blogs.index')->with('success', 'Blog deleted successfully!');
    }
}
