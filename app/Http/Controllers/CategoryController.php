<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('admin.categories.index', compact('categories'));
    }

    public function show($slug)
    {
        $category = Category::with('products')->where('slug', $slug)->firstOrFail();
        return view('admin.categories.show', compact('category'));
    }
}
