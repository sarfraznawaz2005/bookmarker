<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Bookmark;
use App\Models\Folder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Laracasts\Flash\Flash;

class SettingController extends Controller
{
    /**
     * Display a listing of the setting.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.setting', ['title' => 'Settings']);
    }

    /**
     * Store a newly created setting in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user)
    {
        $user = $user->find($request->user()->id);

        // if password is not empty, apply these validation rules
        User::$rules['password'] = 'sometimes|confirmed|min:6';
        // email validation rule
        User::$rules['email'] = 'required|email|unique:users,' . $request->user()->id;

        $user->fill($request->all());

        // do not update password if not specified
        if (!trim($request->get('password'))) {
            $user->purgeFields[] = 'password';
        }

        try {
            // save now
            return $this->saveAndRedirect($user);

        } catch (\Exception $e) {
            // unique constraint
            if ($e->getCode() == 23000) {
                return Redirect::back()->withErrors('The specified email cannot be used');
            }
        }
    }


    /**
     * deletes account permanently
     *
     * @param Request $request
     * @return mixed
     */
    public function deleteAccount(Request $request)
    {
        $user = $request->user();

        if ($redirect = $this->deleteAndRedirect($user)) {

            // also delete folders of this folder
            $user->folders()->delete();

            // also delete bookmarks of this folder
            $user->bookmarks()->delete();

            return $redirect;
        }
    }

    /**
     * Exports saved bookmarks to a file.
     *
     * @param Request $request
     */
    public function export(Request $request)
    {
        $data = [];
        $user = $request->user();
        $folders = $user->folders;

        foreach ($folders as $folder) {
            $bookmarks = $folder->bookmarks->toArray();
            $removeFields = ['id', 'user_id', 'folder_id'];

            $data[$folder->name] = $this->removeUselessFields($bookmarks, $removeFields);
        }

        if (!count($data)) {
            Flash::warning('No data to be exported!');
            return Redirect::back();
        }

        $fileName = $user->id . '_export.json';
        $exportFilePath = public_path('files/' . $fileName);

        file_put_contents($exportFilePath, json_encode($data, JSON_PRETTY_PRINT));

        Flash::info('Exported successfully, <strong class="pulsate btn btn-default btn-sm">' . sprintf('<a href="' . url('download/' . $fileName) . '">Download</a>') . '</strong>');
        return Redirect::back();

        //return response()->download($exportFilePath);
    }

    /**
     * imports previously exported bookmakrs.
     *
     * @param Request $request
     */
    public function import(Request $request)
    {
        if ($request->hasFile('bookmarks_file')) {

            $extension = $request->file('bookmarks_file')->getClientOriginalExtension();
            $fileName = 'import.' . $extension;
            $path = public_path() . '/files/';

            $request->file('bookmarks_file')->move($path, $fileName);

            if (file_exists($path)) {

                $data = file_get_contents($path . $fileName);
                $folders = json_decode($data, true);

                if (!is_array($folders) || !count($folders)) {
                    Flash::warning('No or invalid data!');
                    return Redirect::back();
                }

                $bookmarkObject = new Bookmark();

                // insert data in db now
                foreach ($folders as $folder => $bookmarks) {

                    // add or update folder
                    $folderObject = Folder::firstOrNew(
                        [
                            'name' => $folder,
                            'user_id' => $request->user()->id
                        ]
                    );

                    $folderRow = [
                        'name' => $folder,
                        'user_id' => $request->user()->id,
                        'created_at' => date('Y-m-d h:i:s'),
                    ];

                    $folderObject->fill($folderRow);

                    if ($folderObject->save()) {

                        $bookmarksArray = [];
                        foreach ($bookmarks as $key => $bookmark) {
                            $bookmarksArray[] = $bookmark;
                            $bookmarksArray[$key]['user_id'] = $request->user()->id;
                            $bookmarksArray[$key]['folder_id'] = $folderObject->id;
                            $bookmarksArray[$key]['created_at'] = date('Y-m-d h:i:s');
                        }

                        Bookmark::insert($bookmarksArray);
                    }
                }

                Flash::info('Finished import process!');
                return Redirect::back();

            } else {
                Flash::warning('Unable to import file');
                return Redirect::back();
            }
        }

        Flash::warning('Unable to import file');
        return Redirect::back();
    }

    /**
     * Removes not needed key vales from given array.
     *
     * @param array $array
     * @param array $removeFields
     * @return array
     */
    public function removeUselessFields(array $array, array $removeFields)
    {
        $array = collect($array);

        $array->transform(function ($item, $key) use ($removeFields) {
            foreach ($removeFields as $removeField) {
                if (isset($item[$removeField])) {
                    unset($item[$removeField]);
                }
            }

            return $item;
        });

        return $array->all();
    }
}
