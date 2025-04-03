<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BackgroundImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BackgroundImageController extends Controller
{
    public function index()
    {
        $images = BackgroundImage::all();
        return view('admin.background_images.index', compact('images'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ],[
            'image.required' => 'Please select an image.',
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif, svg, avif.',
            'image.max' => 'The image must be a file of type: jpeg, png, jpg, gif, svg, avif and size less than 2MB.',
        ]);

        $imagePath = $request->file('image')->store('background_images', 'public');

        BackgroundImage::create([
            'image_path' => $imagePath,
        ]);

        return redirect()->route('background_images.index')->with('success', 'Image uploaded successfully.');
    }

    public function destroy(BackgroundImage $backgroundImage)
    {
        Storage::disk('public')->delete($backgroundImage->image_path);
        $backgroundImage->delete();

        return redirect()->route('background_images.index')->with('success', 'Image deleted successfully.');
    }
}
