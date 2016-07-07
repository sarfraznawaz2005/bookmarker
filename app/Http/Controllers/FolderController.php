<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Bookmark;
use App\Models\Folder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class FolderController extends Controller
{
    /**
     * Display a listing of the folder.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Folders';

        $folders = auth()->user()->folders()->with('bookmarks')->get();

        return view('pages.folders', compact('title', 'folders'));
    }

    /**
     * Store a newly created folder in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Folder $folder
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Folder $folder)
    {
        $folderExists = $this->checkFolderExists($request, $folder);

        if ($folderExists) {
            return Redirect::back()->withErrors('The folder name already exists');
        }

        $folder->fill($request->all());
        $folder->user_id = $request->user()->id;

        return $this->saveAndRedirect($folder);
    }

    /**
     * Display the specified folder.
     *
     * @param  int $id
     * @param Folder $folder
     * @param Bookmark $bookmark
     * @return \Illuminate\Http\Response
     */
    public function show($id, Folder $folder, Bookmark $bookmark)
    {
        $folder = $this->getUserFolder($id, $folder);

        if (!$folder) {
            abort(404);
        }

        $title = ucfirst($folder->name);

        // first bookmark to start annotation from for this folder
        $bookmark::$orderDirection = 'DESC';

        $bookmark = $bookmark
            ->where('user_id', auth()->user()->id)
            ->where('folder_id', $id)
            ->limit(1)
            ->first();

        return view('pages.folder_show', compact('title', 'folder', 'bookmark'));
    }

    /**
     * Show the form for editing the specified folder.
     *
     * @param  int $id
     * @param Folder $folder
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Folder $folder)
    {
        $title = 'Edit Folder';

        $folder = $this->getUserFolder($id, $folder);

        if (!$folder) {
            abort(404);
        }

        return view('pages.folder_edit', compact('title', 'folder'));
    }

    /**
     * Update the specified folder in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @param Folder $folder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, Folder $folder)
    {
        $folder = $this->getUserFolder($id, $folder);

        if (!$folder) {
            abort(404);
        }

        $folder->fill($request->all());

        return $this->saveAndRedirect($folder);
    }

    /**
     * Remove the specified folder from storage.
     *
     * @param  int $id
     * @param Folder $folder
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Folder $folder)
    {
        $folder = $this->getUserFolder($id, $folder);

        if (!$folder) {
            abort(404);
        }

        if ($redirect = $this->deleteAndRedirect($folder)) {
            // also delete bookmarks of this folder
            $folder->bookmarks()->delete();

            return $redirect;
        }
    }

    /**
     * Checks if folder with specified name for given user id already exists
     *
     * @param Request $request
     * @param Folder $folder
     * @return Folder
     */
    public function checkFolderExists(Request $request, Folder $folder)
    {
        $folder = $folder
            ->where('name', $request->name)
            ->where('user_id', $request->user()->id)
            ->first();

        return $folder;
    }

    /**
     * Checks if folder with specified id for given user exists
     *
     * @param $id
     * @param Folder $folder
     * @return Folder
     */
    public function getUserFolder($id, Folder $folder)
    {
        $folder = $folder
            ->where('id', $id)
            ->where('user_id', auth()->user()->id)
            ->first();

        return $folder;
    }


    /**
     * Returns all folders of logged user for chrome extension
     *
     * @param $email
     * @param $password
     * @return mixed
     */
    public function getFolders($email, $password)
    {
        $user = $this->checkExtensionAuthentication($email, $password);

        if ($user instanceof User) {
            return $user->folders;
        }
    }

}
