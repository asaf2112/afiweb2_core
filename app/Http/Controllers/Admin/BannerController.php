<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    /**
     * Display a listing of the banners.
     */
    public function index()
    {
        $banners = Banner::ordered()->paginate(15);
        return view('admin.banners.index', compact('banners'));
    }

    /**
     * Show the form for creating a new banner.
     */
    public function create()
    {
        return view('admin.banners.create');
    }

    /**
     * Store a newly created banner in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'top_badge_icon' => 'nullable|string|max:100',
            'top_badge_text' => 'nullable|string|max:255',
            'top_badge_color' => 'required|string|in:amber,cyan,blue,red,emerald,purple',
            'tag1_icon' => 'nullable|string|max:100',
            'tag1_text' => 'nullable|string|max:255',
            'tag2_icon' => 'nullable|string|max:100',
            'tag2_text' => 'nullable|string|max:255',
            'tag3_icon' => 'nullable|string|max:100',
            'tag3_text' => 'nullable|string|max:255',
            'button_text' => 'required|string|max:100',
            'button_url' => 'nullable|string|max:255',
            'card_header' => 'nullable|string|max:255',
            'card_badge' => 'nullable|string|max:100',
            'card_title' => 'nullable|string|max:255',
            'card_spec1' => 'nullable|string|max:255',
            'card_spec2' => 'nullable|string|max:255',
            'card_spec3' => 'nullable|string|max:255',
            'card_old_price' => 'nullable|string|max:100',
            'card_price' => 'nullable|string|max:100',
            'card_button_text' => 'nullable|string|max:100',
            'card_button_url' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'bg_gradient' => 'required|string|max:255',
            'glow_color' => 'required|string|max:100',
            'watermark_text' => 'nullable|string|max:100',
            'order' => 'required|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['card_button_text'] = $validated['card_button_text'] ?? 'Ürüne Git';

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('banners', 'public');
            $validated['image_path'] = 'storage/' . $path;
        }

        Banner::create($validated);

        return redirect()->route('admin.banners.index')->with('success', 'Banner / Slayt başarıyla eklendi.');
    }

    /**
     * Show the form for editing the specified banner.
     */
    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    /**
     * Update the specified banner in storage.
     */
    public function update(Request $request, Banner $banner)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'top_badge_icon' => 'nullable|string|max:100',
            'top_badge_text' => 'nullable|string|max:255',
            'top_badge_color' => 'required|string|in:amber,cyan,blue,red,emerald,purple',
            'tag1_icon' => 'nullable|string|max:100',
            'tag1_text' => 'nullable|string|max:255',
            'tag2_icon' => 'nullable|string|max:100',
            'tag2_text' => 'nullable|string|max:255',
            'tag3_icon' => 'nullable|string|max:100',
            'tag3_text' => 'nullable|string|max:255',
            'button_text' => 'required|string|max:100',
            'button_url' => 'nullable|string|max:255',
            'card_header' => 'nullable|string|max:255',
            'card_badge' => 'nullable|string|max:100',
            'card_title' => 'nullable|string|max:255',
            'card_spec1' => 'nullable|string|max:255',
            'card_spec2' => 'nullable|string|max:255',
            'card_spec3' => 'nullable|string|max:255',
            'card_old_price' => 'nullable|string|max:100',
            'card_price' => 'nullable|string|max:100',
            'card_button_text' => 'nullable|string|max:100',
            'card_button_url' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'bg_gradient' => 'required|string|max:255',
            'glow_color' => 'required|string|max:100',
            'watermark_text' => 'nullable|string|max:100',
            'order' => 'required|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['card_button_text'] = $validated['card_button_text'] ?? 'Ürüne Git';

        if ($request->hasFile('image')) {
            if ($banner->image_path && file_exists(public_path($banner->image_path))) {
                @unlink(public_path($banner->image_path));
            }
            $path = $request->file('image')->store('banners', 'public');
            $validated['image_path'] = 'storage/' . $path;
        }

        $banner->update($validated);

        return redirect()->route('admin.banners.index')->with('success', 'Banner / Slayt güncellendi.');
    }

    /**
     * Remove the specified banner from storage.
     */
    public function destroy(Banner $banner)
    {
        if ($banner->image_path && file_exists(public_path($banner->image_path))) {
            @unlink(public_path($banner->image_path));
        }

        $banner->delete();

        return redirect()->route('admin.banners.index')->with('success', 'Banner silindi.');
    }

    /**
     * Toggle active status of a banner.
     */
    public function toggleActive(Banner $banner)
    {
        $banner->is_active = !$banner->is_active;
        $banner->save();

        return redirect()->back()->with('success', 'Banner durumu güncellendi.');
    }
}
