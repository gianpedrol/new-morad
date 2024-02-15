<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;
use Auth;
use Stichoza\GoogleTranslate\GoogleTranslate;

class LanguageController extends Controller
{

    public function adminLnagugae($code)
    {
        $language = Language::where("code", $code)->firstOrFail();
        $data = include(resource_path('lang/' . $code . '/admin.php'));
        return view('admin.admin_language', compact('data'));
    }

    public function updateAdminLanguage(Request $request, $code)
    {
        $language = Language::where("code", $code)->firstOrFail();
        $dataArray = [];
        foreach ($request->values as $index => $value) {
            $dataArray[$index] = $value;
        }
        file_put_contents(resource_path('lang/' . $code . '/admin.php'), "");
        $dataArray = var_export($dataArray, true);
        file_put_contents(resource_path('lang/' . $code . '/admin.php'), "<?php\n return {$dataArray};\n ?>");

        $notification = trans('admin_validation.Update Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }

    public function adminValidationLnagugae($code)
    {
        $language = Language::where("code", $code)->firstOrFail();
        $data = include(resource_path('lang/' . $code . '/admin_validation.php'));
        return view('admin.admin_validation_language', compact('data'));
    }

    public function updateAdminValidationLnagugae(Request $request, $code)
    {
        $language = Language::where("code", $code)->firstOrFail();
        $dataArray = [];
        foreach ($request->values as $index => $value) {
            $dataArray[$index] = $value;
        }
        file_put_contents(resource_path('lang/' . $code . '/admin_validation.php'), "");
        $dataArray = var_export($dataArray, true);
        file_put_contents(resource_path('lang/' . $code . '/admin_validation.php'), "<?php\n return {$dataArray};\n ?>");

        $notification = trans('admin_validation.Update Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }

    public function websiteLanguage($code)
    {
        $language = Language::where("code", $code)->firstOrFail();
        $data = include(resource_path('lang/' . $code . '/user.php'));
        return view('admin.language', compact('data'));
    }

    public function updateLanguage(Request $request, $code)
    {
        $language = Language::where("code", $code)->firstOrFail();
        $dataArray = [];
        foreach ($request->values as $index => $value) {
            $dataArray[$index] = $value;
        }
        file_put_contents(resource_path('lang/' . $code . '/user.php'), "");
        $dataArray = var_export($dataArray, true);
        file_put_contents(resource_path('lang/' . $code . '/user.php'), "<?php\n return {$dataArray};\n ?>");

        $notification = trans('admin_validation.Update Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }


    public function websiteValidationLanguage($code)
    {
        $language = Language::where("code", $code)->firstOrFail();
        $data = include(resource_path('lang/' . $code . '/user_validation.php'));
        return view('admin.website_validation_language', compact('data'));
    }

    public function updateValidationLanguage(Request $request, $code)
    {
        $language = Language::where("code", $code)->firstOrFail();
        $dataArray = [];
        foreach ($request->values as $index => $value) {
            $dataArray[$index] = $value;
        }
        file_put_contents(resource_path('lang/' . $code . '/user_validation.php'), "");
        $dataArray = var_export($dataArray, true);
        file_put_contents(resource_path('lang/' . $code . '/user_validation.php'), "<?php\n return {$dataArray};\n ?>");

        $notification = trans('admin_validation.Update Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }


    /**
     * Retrieves the index view for the admin languages page.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('admin.languages', [
            'languages' => Language::all(),
        ]);
    }

    /**
     * Create a new view for the admin to create a language.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.create_language');
    }

    /**
     * Stores the language data from the request.
     *
     * @param Request $request The request object.
     * @return \Illuminate\Http\RedirectResponse Redirect to the language index page.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|unique:languages',
            'code' => 'required|unique:languages',
            'direction' => 'required',
        ];

        $customMessages = [
            'name.required' => trans('admin_validation.Name is required'),
            'code.required' => trans('admin_validation.Code is required'),
            'direction.required' => trans('admin_validation.Direction is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        $lang = Language::create($request->only('name', 'code', 'direction'));

        if ($lang) {
            $code = strtolower($lang->code);
            $parentDir = dirname(app_path());

            $files = [
                'admin_validation.php',
                'admin.php',
                'user.php',
                'user_validation.php'
            ];

            foreach ($files as $file) {
                $sourcePath = $parentDir . "/resources/lang/en/{$file}";
                $destinationPath = $parentDir . "/resources/lang/{$code}/{$file}";

                if (file_exists($sourcePath)) {
                    if (!file_exists(dirname($destinationPath))) {
                        mkdir(dirname($destinationPath));
                    }

                    copy($sourcePath, $destinationPath);
                }
            }
        }

        $notification = trans('admin_validation.Created Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->route('admin.languages.index')->with($notification);
    }

    /**
     * Edit a language.
     *
     * @param int $id The ID of the language to edit.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the language is not found.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View The view for editing the language.
     */
    public function edit($id)
    {
        $language = Language::findOrFail($id);

        return view('admin.edit_language', [
            'language' => $language,
        ]);
    }

    /**
     * Updates a language in the database.
     *
     * @param Request $request The HTTP request object.
     * @param int $id The ID of the language to update.
     * @throws \Throwable If an error occurs while deleting the old language code folder.
     * @return \Illuminate\Http\RedirectResponse Redirects to the language index page with a success notification.
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required|unique:languages,name,' . $id,
            'direction' => 'required',
        ];

        $customMessages = [
            'name.required' => trans('admin_validation.Name is required'),
            'code.required' => trans('admin_validation.Code is required'),
            'name.unique' => trans('admin_validation.Name is taken'),
            'code.unique' => trans('admin_validation.Code is taken'),
            'direction.required' => trans('admin_validation.Direction is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        $lang = Language::findOrFail($id);
        $oldCode = $lang->code;
        $language = $lang->update($request->only('name', 'direction'));
        $code = strtolower($lang->code);

        if ($language && ($oldCode !== $code) && ($code !== 'en')) {
            $parentDir = dirname(app_path());

            $files = [
                'admin_validation.php',
                'admin.php',
                'user.php',
                'user_validation.php'
            ];

            foreach ($files as $file) {
                $sourcePath = $parentDir . "/resources/lang/en/{$file}";
                $destinationPath = $parentDir . "/resources/lang/{$code}/{$file}";

                if (file_exists($sourcePath)) {
                    if (!file_exists(dirname($destinationPath))) {
                        mkdir(dirname($destinationPath));
                    }

                    copy($sourcePath, $destinationPath);
                }
            }

            if ($oldCode !== $code && $code !== 'en') {
                $parentDir = dirname(app_path());
                $destinationPath = $parentDir . "/resources/lang/{$oldCode}";
                try {
                    $this->deleteFolder($destinationPath);
                } catch (\Throwable $th) {
                    //throw $th;
                }
            }
        }

        $notification = trans('admin_validation.Updated Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');

        return redirect()->route('admin.languages.index')->with($notification);
    }

    /**
     * Destroy a language by its ID.
     *
     * @param int $id The ID of the language to be destroyed.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $language = Language::findOrFail($id);
        $code = $language->code;
        if ($language->code == app()->getLocale() || $language->code == 'en') {
            $notification = trans('admin.Deleting Failed');
            $notification = array('messege' => $notification, 'alert-type' => 'error');

            return redirect()->route('admin.languages.index')->with($notification);
        }

        if ($language->delete() && $code !== 'en') {
            $parentDir = dirname(app_path());
            $destinationPath = $parentDir . "/resources/lang/{$code}";
            $this->deleteFolder($destinationPath);
        }

        $notification = trans('admin_validation.Deleted Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');

        return redirect()->route('admin.languages.index')->with($notification);
    }

    public function translateAll(Request $request)
    {
        $files = [
            'admin_validation',
            'admin',
            'user',
            'user_validation'
        ];

        if (in_array($request->file, $files)) {
            $data = include(resource_path('lang/' . $request->code . '/' . $request->file . '.php'));
            $dataArray = [];
            $tr = new GoogleTranslate($request->code);
            foreach ($data as $index => $value) {
                $dataArray[$index] = $tr->translate($value) ?? $value;
            }
            file_put_contents(resource_path('lang/' . $request->code . '/' . $request->file . '.php'), "");
            $dataArray = var_export($dataArray, true);
            file_put_contents(resource_path('lang/' . $request->code . '/' . $request->file . '.php'), "<?php\n return {$dataArray};\n ?>");

            return response()->json([
                'success' => true,
                'message' => "All texts translated successfully!"
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => "File Not Found!"
        ], 404);
    }

    /**
     * Deletes a folder and all its contents recursively.
     *
     * @param string $folderPath The path to the folder to be deleted.
     * @return void
     */
    private function deleteFolder($folderPath)
    {
        if (is_dir($folderPath)) {
            $files = scandir($folderPath);
            foreach ($files as $file) {
                if ($file != '.' && $file != '..') {
                    $this->deleteFolder($folderPath . '/' . $file);
                }
            }
            rmdir($folderPath);
        } else {
            unlink($folderPath);
        }
    }
}
