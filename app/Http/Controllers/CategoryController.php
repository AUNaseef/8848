<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'success' => true,
            'categories' => Category::with('children')->where('parent_id', null)->get()
        ]);
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
            "parent_id" => 'nullable',
            "name" => 'required',
            "description" => 'required',
            "icon" => 'required|file',
            "background_image" => 'required|file'
        ]);

        $category = Category::create([
            "parent_id" => $request->parent_id ?? null,
            "name" => $request->name,
            "description" => $request->description,
            "icon_url" => $request->icon->store('public/category_images'),
            "background_image_url" => $request->background_image->store('public/category_images')
        ]);

        return response()->json([
            'success' => true,
            'category' => $category->load('parent', 'children')
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {

        return response()->json([
            'success' => true,
            'category' => $category->load('parent', 'children')
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            "parent_id" => 'nullable',
            "name" => 'required',
            "description" => 'required',
            "icon" => 'nullable|file',
            "background_image" => 'nullable|file'
        ]);

        $category->update([
            "parent_id" => $request->parent_id ?? null,
            "name" => $request->name,
            "description" => $request->description,
            "icon_url" => $request->icon ? $request->icon->store('public/category_images') : $category->icon_url,
            "background_image_url" => $request->background_image ? $request->background_image->store('public/category_images') : $category->background_image_url
        ]);

        return response()->json([
            'success' => true,
            'category' => $category->load('parent', 'children')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json([
            'success' => true,
        ]);
    }
}
