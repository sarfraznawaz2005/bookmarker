<?php

// make listing edit button
use App\Models\Folder;

function listingEditButton($link)
{
    $html = <<< HTML
    <a data-placement="top" data-tooltip data-original-title="Edit" title="Edit" class="edit_btn" href="$link">
        <b class="btn-primary btn-sm glyphicon glyphicon-pencil"></b>
    </a>
HTML;

    return $html;
}

// make listing delete button
function listingDeleteButton($link, $title = 'this')
{
    $html = <<< HTML

        <a data-placement="top" data-tooltip data-original-title="Delete" title="Delete" class="delete_btn confirm-delete" data-label="$title" rel="$link" href="javascript:void(0);">
            <b class="btn-danger btn-sm glyphicon glyphicon-trash"></b>
        </a>
HTML;

    return $html;
}

function listingAnnotateButton($link)
{

    $html = <<< HTML
    <a target="_blank" data-placement="top" data-tooltip data-original-title="Annotate Bookmarked Page" title="Annotate Bookmarked Page" class="edit_btn" href="$link">
        <b class="btn-info btn-sm glyphicon glyphicon-book"></b>
    </a>
HTML;

    return $html;
}

function listingMarkReadButton($link, $readStatus)
{

    $title = $readStatus == 0 ? 'Mark as read' : 'Mark as unread';
    $icon = $readStatus == 0 ? 'glyphicon-ok' : 'glyphicon-star';

    $html = <<< HTML
    <a data-placement="top" data-tooltip data-original-title="$title" title="$title" class="edit_btn" href="$link">
        <b class="btn-success btn-sm glyphicon $icon"></b>
    </a>
HTML;

    return $html;
}

function listingLoginButton($link)
{
    $html = <<< HTML
    <a data-placement="top" data-tooltip data-original-title="Login as this user" title="Login as this user" class="edit_btn" href="$link">
        <b class="btn-success btn-sm glyphicon glyphicon-dashboard"></b>
    </a>
HTML;

    return $html;
}

function listingMarkAdminButton($link, $adminStatus)
{

    $title = $adminStatus == 0 ? 'Make Admin' : 'Revoke Admin';
    $icon = $adminStatus == 0 ? 'glyphicon-ok' : 'glyphicon-user';

    $html = <<< HTML
    <a data-placement="top" data-tooltip data-original-title="$title" title="$title" class="edit_btn" href="$link">
        <b class="btn-success btn-sm glyphicon $icon"></b>
    </a>
HTML;

    return $html;
}

function getFolderColor(Folder $folder)
{
    $count = count($folder->bookmarks);

    if ($count < 1) {
        return 'dark';
    } elseif ($count > 0 && $count < 25) {
        return 'yellow';
    } elseif ($count >= 25 && $count < 50) {
        return 'blue';
    } elseif ($count >= 50) {
        return 'green';
    }

    return 'dark';
}

// create BTS popover with text cut off
function popoverShortText($text, $length = 30, $title = 'Details')
{
    return '<span data-placement="top" class="dotted" style="cursor:pointer;" data-container="body" rel="popover" data-original-title="' . $title . '" data-content="' . nl2br($text) . '">' . str_limit($text,
        $length) . '</span>';
}

function readPercentage()
{
    @$percent = (auth()->user()->readBookmarkCount() * 100) / auth()->user()->bookmarkCount();
    $percent = number_format($percent, 2);

    return $percent . '%';
}

function readPagesStats()
{
    return auth()->user()->readBookmarkCount() . '/' . auth()->user()->bookmarkCount();
}

function getElaspedTime()
{
    // php 5.5 onwards only
    return (microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']) . 's';
}

function normalizTitle($title)
{
    $title = normalizeChars($title);
    $title = html_entity_decode($title);
    $title = preg_replace("/[^a-zA-Z0-9_ ]+/", '', $title);

    return str_limit($title);
}

// Replaces special characters with non-special equivalents
function normalizeChars($str)
{
    # Quotes cleanup
    $str = preg_replace('#' . chr(ord("`")) . '#', "'", $str); # `
    $str = preg_replace('#' . chr(ord("�")) . '#', "'", $str); # �
    $str = preg_replace('#' . chr(ord("�")) . '#', ",", $str); # �
    $str = preg_replace('#' . chr(ord("`")) . '#', "'", $str); # `
    $str = preg_replace('#' . chr(ord("�")) . '#', "'", $str); # �
    $str = preg_replace('#' . chr(ord("�")) . '#', "\"", $str); # �
    $str = preg_replace('#' . chr(ord("�")) . '#', "\"", $str); # �
    $str = preg_replace('#' . chr(ord("�")) . '#', "'", $str); # �

    $unwanted_array = array(
        '�' => 'S',
        '�' => 's',
        '�' => 'Z',
        '�' => 'z',
        '�' => 'A',
        '�' => 'A',
        '�' => 'A',
        '�' => 'A',
        '�' => 'A',
        '�' => 'A',
        '�' => 'A',
        '�' => 'C',
        '�' => 'E',
        '�' => 'E',
        '�' => 'E',
        '�' => 'E',
        '�' => 'I',
        '�' => 'I',
        '�' => 'I',
        '�' => 'I',
        '�' => 'N',
        '�' => 'O',
        '�' => 'O',
        '�' => 'O',
        '�' => 'O',
        '�' => 'O',
        '�' => 'O',
        '�' => 'U',
        '�' => 'U',
        '�' => 'U',
        '�' => 'U',
        '�' => 'Y',
        '�' => 'B',
        '�' => 'Ss',
        '�' => 'a',
        '�' => 'a',
        '�' => 'a',
        '�' => 'a',
        '�' => 'a',
        '�' => 'a',
        '�' => 'a',
        '�' => 'c',
        '�' => 'e',
        '�' => 'e',
        '�' => 'e',
        '�' => 'e',
        '�' => 'i',
        '�' => 'i',
        '�' => 'i',
        '�' => 'i',
        '�' => 'o',
        '�' => 'n',
        '�' => 'o',
        '�' => 'o',
        '�' => 'o',
        '�' => 'o',
        '�' => 'o',
        '�' => 'o',
        '�' => 'u',
        '�' => 'u',
        '�' => 'u',
        '�' => 'y',
        '�' => 'y',
        '�' => 'b',
        '�' => 'y'
    );
    $str = strtr($str, $unwanted_array);

    # Bullets, dashes, and trademarks
    $str = preg_replace('#' . chr(149) . '#', "&#8226;", $str); # bullet �
    $str = preg_replace('#' . chr(150) . '#', "&ndash;", $str); # en dash
    $str = preg_replace('#' . chr(151) . '#', "&mdash;", $str); # em dash
    $str = preg_replace('#' . chr(153) . '#', "&#8482;", $str); # trademark
    $str = preg_replace('#' . chr(169) . '#', "&copy;", $str); # copyright mark
    $str = preg_replace('#' . chr(174) . '#', "&reg;", $str); # registration mark

    return $str;
}