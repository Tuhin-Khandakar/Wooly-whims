<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting as SettingModel;
use App\Services\ImageService;
use App\Helpers\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index()
    {
        $settings = SettingModel::pluck('value', 'key')->all();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', 'store_logo', 'hero_banner', 'admin_name', 'admin_picture']);

        // Update Global Settings
        foreach ($data as $key => $value) {
            SettingModel::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        if ($request->hasFile('store_logo')) {
            $path = $this->imageService->upload($request->file('store_logo'), 'settings');
            SettingModel::updateOrCreate(['key' => 'store_logo'], ['value' => $path]);
        }

        if ($request->hasFile('hero_banner')) {
            $path = $this->imageService->upload($request->file('hero_banner'), 'settings');
            SettingModel::updateOrCreate(['key' => 'hero_banner'], ['value' => $path]);
        }

        // Update Admin Profile
        $user = auth()->user();
        if ($request->filled('admin_name')) {
            $user->name = $request->admin_name;
        }
        if ($request->hasFile('admin_picture')) {
            $user->profile_picture = $this->imageService->upload($request->file('admin_picture'), 'profiles');
        }
        $user->save();

        Setting::clearCache();

        return back()->with('success', 'Settings and Profile updated successfully.');
    }
}
