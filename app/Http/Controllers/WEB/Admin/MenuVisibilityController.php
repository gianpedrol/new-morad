<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Traits\TranslationTrait;
use Illuminate\Http\Request;
use App\Models\MenuVisibility;
use App\Models\MenuVisibilityTranslation;
use Stichoza\GoogleTranslate\GoogleTranslate;

class MenuVisibilityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $menus = MenuVisibility::all();
        $languages = Language::all();
        return view('admin.menu_visibility', compact('menus', 'languages'));
    }

    public function update(Request $request)
    {

        foreach ($request->ids as $index => $id) {
            $menu = MenuVisibility::find($id);
            $menu->custom_name = $request->customs[$index];
            $menu->status = $request->status[$index];
            $menu->save();
        }

        $notification = trans('admin_validation.Update successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }

    public function editTranslation($code)
    {
        if ($code == 'en') {
            $this->updateDefaultTranslation($code);

            $notification = array(
                'messege' => trans('admin_validation.Update Successfully'),
                'alert-type' => 'success'
            );

            return redirect()->route('admin.menu-visibility')->with($notification);
        }

        $MenuVisibilites = MenuVisibility::all();

        foreach ($MenuVisibilites as $key => $MenuVisibility) {
            $translation = MenuVisibilityTranslation::firstOrCreate([
                'menu_visibility_id' => $MenuVisibility->id,
                'language_code' => $code
            ]);

            if (!$translation->custom_name) {
                try {
                    $tr = new GoogleTranslate($code);
                    $translatedCustomName = $tr->translate($MenuVisibility->menu_name);
                } catch (\Throwable $th) {
                    $translatedCustomName = null;
                }
                $translation->custom_name = $translatedCustomName;
                $translation->save();
            }
        }

        $menus = MenuVisibilityTranslation::where('language_code', $code)->get();

        return view('admin.menu_visibility_translation', [
            'menus' => $menus,
        ]);
    }

    public function updateTranslation(Request $request, $code)
    {
        if ($code == 'en') {
            $this->updateDefaultTranslation($code);

            $notification = array(
                'messege' => trans('admin_validation.Update Successfully'),
                'alert-type' => 'success'
            );

            return redirect()->route('admin.menu-visibility')->with($notification);
        }

        $rules = [
            'customs.*' => 'required',
        ];

        $customMessages = [
            'customs.required' => trans('admin_validation.Custom Name is required')
        ];

        $this->validate($request, $rules, $customMessages);

        foreach ($request->ids as $index => $id) {
            $menu = MenuVisibilityTranslation::find($id);
            $menu->custom_name = $request->customs[$index];
            $menu->save();
        }

        $notification = array(
            'messege' => trans('admin_validation.Update Successfully'),
            'alert-type' => 'success'
        );

        return redirect()->route('admin.menu-visibility')->with($notification);
    }

    private function updateDefaultTranslation($code)
    {
        $MenuVisibilites = MenuVisibility::all();

        foreach ($MenuVisibilites as $key => $MenuVisibility) {
            $translation = MenuVisibilityTranslation::firstOrCreate([
                'menu_visibility_id' => $MenuVisibility->id,
                'language_code' => $code
            ]);

            if (!$translation->custom_name) {
                try {
                    $tr = new GoogleTranslate($code);
                    $translatedCustomName = $tr->translate($MenuVisibility->menu_name);
                } catch (\Throwable $th) {
                    $translatedCustomName = null;
                }
                $translation->custom_name = $translatedCustomName;
                $translation->save();
            }
        }
    }
}
