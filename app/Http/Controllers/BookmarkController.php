<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Bookmark;
use App\Models\User;
use DOMDocument;
use DOMXPath;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;
use Readability;
use Redirect;
use Sabre\Uri;

class BookmarkController extends Controller
{
    /**
     * Display a listing of the bookmark.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Bookmarks';

        // folders of users
        $folders = auth()->user()->folders;

        // bookmarks of user
        $bookmarks = auth()->user()->bookmarks()->with('folder')->paginate(10);

        return view('pages.bookmarks', compact('title', 'folders', 'bookmarks'));
    }

    /**
     * Searches the bookmark.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $title = 'Search Results';

        // folders of users
        $folders = auth()->user()->folders;

        // bookmarks of user
        $bookmarks = auth()->user()
            ->bookmarks()
            ->where('url', 'LIKE', '%' . $request->get('keyword') . '%')
            ->orWhere('title', 'LIKE', '%' . $request->get('keyword') . '%')
            ->orWhere('comments', 'LIKE', '%' . $request->get('keyword') . '%')
            ->with('folder')
            ->paginate(10);

        return view('pages.bookmarks', compact('title', 'folders', 'bookmarks'));
    }

    /**
     * Store a newly created bookmark in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Bookmark $bookmark
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Bookmark $bookmark)
    {
        $bookmark->fill($request->all());
        $bookmark->url = rtrim($request->url, '/');
        $bookmark->user_id = auth()->user()->id;
        $bookmark->title = normalizTitle(trim($request->get('title')));

        if ($bookmark->isBookmarked($bookmark->url, auth()->user())) {
            Flash::warning('The page is already bookmarked!');
            return Redirect::back();
        }

        return $this->saveAndRedirect($bookmark);
    }

    /**
     * Show the form for editing the specified bookmark.
     *
     * @param  int $id
     * @param Bookmark $bookmark
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Bookmark $bookmark)
    {
        $title = 'Edit Bookmark';

        $bookmark = $this->checkIsBookmarkOwner($id, $bookmark);

        // folders of users
        $folders = auth()->user()->folders;

        return view('pages.bookmark_edit', compact('title', 'bookmark', 'folders'));
    }

    /**
     * Update the specified bookmark in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @param Bookmark $bookmark
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, Bookmark $bookmark)
    {
        $bookmark = $this->checkIsBookmarkOwner($id, $bookmark);

        $bookmark->fill($request->all());
        $bookmark->url = rtrim($request->url, '/');

        return $this->saveAndRedirect($bookmark);
    }

    /**
     * Remove the specified bookmark from storage.
     *
     * @param  int $id
     * @param Bookmark $bookmark
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Bookmark $bookmark)
    {
        $bookmark = $this->checkIsBookmarkOwner($id, $bookmark);

        if ($redirect = $this->deleteAndRedirect($bookmark)) {
            // also delete annotations of this bookmark
            $bookmark->annotations()->delete();

            return $redirect;
        }
    }


    /**
     * Remove the specified bookmark from storage.
     *
     * @param  int $id
     * @param Bookmark $bookmarkModel
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroyAnnotate($id, Bookmark $bookmarkModel)
    {
        $bookmark = $this->checkIsBookmarkOwner($id, $bookmarkModel);

        if (!$bookmark->delete()) {
            return Redirect::back()->withErrors($bookmark->errors());
        }

        $prevBookmark = $bookmarkModel
            ->where('user_id', auth()->user()->id)
            ->where('id', '<', $id)
            ->limit(1)
            ->first();

        return redirect()->to('/annotate/' . $prevBookmark->id);
    }

    /**
     * Remove the specified bookmark from storage.
     *
     * @param  int $id
     * @param $folder_id
     * @param Bookmark $bookmarkModel
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroyAnnotateFolder($folder_id, $id, Bookmark $bookmarkModel)
    {
        $bookmark = $this->checkIsBookmarkOwner($id, $bookmarkModel);

        if (!$bookmark->delete()) {
            return Redirect::back()->withErrors($bookmark->errors());
        }

        $prevBookmark = $bookmarkModel
            ->where('user_id', auth()->user()->id)
            ->where('id', '<', $bookmark->id)
            ->where('folder_id', $folder_id)
            ->limit(1)
            ->first();

        return redirect()->to('/annotate_folder/' . $folder_id . '/' . $prevBookmark->id);
    }

    /**
     * sets read status for given bookmark.
     *
     * @param  int $id
     * @param Bookmark $bookmark
     * @return \Illuminate\Http\Response
     */
    public function setReadStatus($id, Bookmark $bookmark)
    {
        $bookmark = $this->checkIsBookmarkOwner($id, $bookmark);

        $isRead = $bookmark->isread == 1 ? 0 : 1;
        $bookmark->isread = $isRead;

        return $this->saveAndRedirect($bookmark);
    }

    /**
     * saves chosen bookmark from chrome extension
     *
     * @return mixed
     */
    public function saveBookmark()
    {
        if (isset($_GET['url'], $_GET['email'], $_GET['password'])) {
            $user = $this->checkExtensionAuthentication($_GET['email'], $_GET['password']);

            if ($user instanceof User) {

                $bookmark = new Bookmark();
                $bookmark->url = rtrim($_GET['url'], '/');
                $bookmark->comments = $_GET['comments'];
                $bookmark->folder_id = $_GET['folder'];
                $bookmark->user_id = $user->id;
                $bookmark->title = normalizTitle(trim($_GET['title']));

                if ($bookmark->isBookmarked($bookmark->url, $user)) {
                    exit('The page is already bookmarked!');
                }

                if (!$bookmark->save()) {
                    print_r($bookmark->errors()->all()[0]);
                } else {
                    echo 'Bookmarked successfully :)';
                }

            }

        } else {
            echo 'Error: Cannot save, invalid information';
        }
    }

    /**
     * @param $id
     * @param Bookmark $bookmarkModel
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function annotate($id, Bookmark $bookmarkModel)
    {
        $bookmark = $this->checkIsBookmarkOwner($id, $bookmarkModel);

        $prevBookmark = $bookmarkModel
            ->where('user_id', auth()->user()->id)
            ->where('id', '<', $bookmark->id)
            ->limit(1)
            ->first();

        $nextBookmark = $bookmarkModel;
        $nextBookmark::$orderDirection = 'ASC';

        $nextBookmark = $nextBookmark
            ->where('user_id', auth()->user()->id)
            ->where('id', '>', $bookmark->id)
            ->limit(1)
            ->first();

        // for count
        $total = auth()->user()->bookmarks()->count();

        $currentCount = $bookmarkModel
            ->where('user_id', auth()->user()->id)
            ->where('id', '>', $bookmark->id)
            ->count();

        $count = '(' . ($currentCount + 1) . '/' . $total . ') ';

        return view('pages.annotate', compact('bookmark', 'prevBookmark', 'nextBookmark', 'count'));
    }

    /**
     * @param $id
     * @param $bookmark_id
     * @param Bookmark $bookmarkModel
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function annotateFolder($id, $bookmark_id, Bookmark $bookmarkModel)
    {
        $bookmark = $this->checkIsBookmarkOwner($bookmark_id, $bookmarkModel);

        $prevBookmark = $bookmarkModel
            ->where('user_id', auth()->user()->id)
            ->where('id', '<', $bookmark->id)
            ->where('folder_id', $id)
            ->limit(1)
            ->first();

        $nextBookmark = $bookmarkModel;
        $nextBookmark::$orderDirection = 'ASC';

        $nextBookmark = $nextBookmark
            ->where('user_id', auth()->user()->id)
            ->where('id', '>', $bookmark->id)
            ->where('folder_id', $id)
            ->limit(1)
            ->first();

        // for count
        $total = $bookmark->folder->bookmarks()->count();
        $readFromFolderCount = $bookmark->folder->bookmarks()->where('isread', '1')->count();

        $currentCount = $bookmarkModel
            ->where('user_id', auth()->user()->id)
            ->where('id', '>', $bookmark->id)
            ->where('folder_id', $id)
            ->count();

        $count = '(' . ($currentCount + 1) . '/' . $total . ') ';
        @$readPercentage = number_format(($readFromFolderCount * 100) / $total, 2) . '%';

        return view('pages.annotate_folder',
            compact('bookmark', 'prevBookmark', 'nextBookmark', 'id', 'count', 'readPercentage'));
    }

    /**
     * @param $id
     * @param Bookmark $bookmark
     * @return Bookmark
     */
    public function checkIsBookmarkOwner($id, Bookmark $bookmark)
    {
        $bookmark = $bookmark->findOrFail($id);

        $isBookmarkOwner = auth()->user()->isBookmarkOwner($bookmark);

        if (!$isBookmarkOwner) {
            abort(404);
        }

        return $bookmark;
    }

    /**
     * gets complete html of page including css, images and optionally javascript
     *
     * @param $id
     * @param Bookmark $bookmark
     */
    public function loadPage($id, Bookmark $bookmark)
    {
        $bookmark = $this->checkIsBookmarkOwner($id, $bookmark);

        //echo $this->extractArticle($bookmark);

        echo $this->getContentUsingBaseTag($bookmark);
    }

    /**
     * gets title of page for given url
     *
     * @param Request $request
     * @return string
     */
    public function getTitle(Request $request)
    {
        libxml_use_internal_errors(true);

        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->strictErrorChecking = false;
        $dom->loadHTML(@file_get_contents($request->get('url')));

        $xpath = new DOMXpath($dom);
        $tag = $xpath->query('//title');
        @$title = $tag->item(0)->textContent;

        if ($title) {
            return normalizTitle($title);
        }
    }

    /**
     * @param Bookmark $bookmark
     * @return string
     */
    public function getContentUsingBaseTag(Bookmark $bookmark)
    {
        $contents = '';
        $parse = Uri\parse($bookmark->url);
        $baseDomain = $parse['scheme'] . '://' . $parse['host'];

        try {
            $contents = $this->getContents($bookmark->url);
        } catch (\ErrorException $e) {
            echo sprintf("<h3 style='margin:100px auto; width: 800px; background:lightcoral; color:#000; padding: 10px;'>%s</h3>",
                $e->getMessage());
        }

        if ($contents) {

            libxml_use_internal_errors(true);

            $dom = new DOMDocument();
            $dom->preserveWhiteSpace = false;
            $dom->strictErrorChecking = false;
            $dom->loadHTML($contents);

            // create new <base> tag
            $baseTag = $dom->createElement('base');
            $baseTag->setAttribute('href', $baseDomain);

            // find <head> tag
            $headTagList = $dom->getElementsByTagName('head');
            $headTag = $headTagList->item(0);

            // find first child of head tag to later use in insertion
            $headHasChildren = $headTag->hasChildNodes();

            // insert new base tag as first child to head tag
            if ($headHasChildren) {
                $headTagFirstChild = $headTag->firstChild;
                $headTag->insertBefore($baseTag, $headTagFirstChild);
            } else {
                $headTag->appendChild($baseTag);
            }

            $contents = $dom->saveHTML();

            return $this->removeUselessTags($contents);
        }

        return '';
    }

    private function removeUselessTags($content)
    {
        # remove empty lines
        //$this->html = preg_replace('#(\r\n[ \t]*){2,}#', "\r\n", $this->html);

        # remove @import declarations
        //preg_replace('#(@import url\([\'\"]?)([^\"\'\)]+)([\"\']?\))#', '', $this->html);

        # fix showing up of garbage characters
        $content = mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8');

        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->strictErrorChecking = false;
        $dom->loadHTML($content);

        $tags = $this->getTags('//meta | //link | //script', $dom);

        $tagsLength = $tags->length;

        # get all <link>, <meta> and <script> tags and remove them
        for ($i = 0; $i < $tagsLength; $i++) {
            $tag = $tags->item($i);

            if (strtolower($tag->nodeName) === 'link') {
                # only keep links for stylesheets
                if (stripos($tag->getAttribute('rel'), 'stylesheet') === false) {
                    $tag->parentNode->removeChild($tag);
                }
            } else {
                $tag->parentNode->removeChild($tag);
            }
        }

        return $dom->saveHTML();
    }

    /**
     * Returns tags list for specified selector
     *
     * @param $selector - xpath selector expression
     *
     * @param $dom
     * @return DOMNodeList
     */
    private function getTags($selector, $dom)
    {
        $xpath = new DOMXpath($dom);
        $tags = $xpath->query($selector);

        # free memory
        libxml_use_internal_errors(false);
        libxml_use_internal_errors(true);
        libxml_clear_errors();
        unset($xpath);
        $xpath = null;

        return $tags;
    }

    /**
     * @param Bookmark $bookmark
     * @return string
     */
    public function extractArticle(Bookmark $bookmark)
    {
        // this header is necessary
        header('Content-Type: text/plain; charset=utf-8');

        $url = $bookmark->url;
        $html = file_get_contents($url);

        // Note: PHP Readability expects UTF-8 encoded content.
        // If your content is not UTF-8 encoded, convert it
        // first before passing it to PHP Readability.
        // Both iconv() and mb_convert_encoding() can do this.

        // If we've got Tidy, let's clean up input.
        // This step is highly recommended - PHP's default HTML parser
        // often does a terrible job and results in strange output.
        if (function_exists('tidy_parse_string')) {
            $tidy = tidy_parse_string($html, array(), 'UTF8');
            $tidy->cleanRepair();
            $html = $tidy->value;
        }

        // give it to Readability
        $readability = new Readability($html, $url);

        $readability->debug = false;
        $readability->convertLinksToFootnotes = false;

        // process it
        $result = $readability->init();

        // does it look like we found what we wanted?
        if ($result) {
            $title = $readability->getTitle()->textContent;
            $content = $readability->getContent()->innerHTML;

            // if we've got Tidy, let's clean it up for output
            if (function_exists('tidy_parse_string')) {
                $tidy = tidy_parse_string($content, array('indent' => true, 'show-body-only' => true), 'UTF8');
                $tidy->cleanRepair();
                $content = $tidy->value;
            }

            return $content;
        } else {
            return '';
        }
    }

    protected function getContents($url)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_REFERER, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "{$_SERVER['HTTP_USER_AGENT']}");

        $result = curl_exec($ch);
        curl_close($ch);

        if (false === $result) {
            return file_get_contents($url);
        }

        return $result;
    }
}
