<?php
// This file is part of cigi, a remarkably simple framework for the Web.
//
// Copyright (c) 2008-2010 Invectorate LLC. All rights reserved.

define('CIGI_PATH_LIBRARY', dirname(__FILE__));

require_once(CIGI_PATH_LIBRARY . '/configure.php');
require_once(CIGI_PATH_LIBRARY . '/page.php');
require_once(CIGI_PATH_LIBRARY . '/template.php');

function _cigi_dispatch_page($name) {
    $page = cigi_page_get($name);
    $result = cigi_page_render($page);

    if(!empty($result)) {
        echo $result;
    }
}

function _cigi_error_handler($errno, $error, $file, $line) {
    if(!(error_reporting() & $errno)) {
        return true;
    }

    if(!defined('_CIGI_CONFIGURED') || defined('CIGI_ERROR_ERRNO')) {
        return false;
    }

    define('CIGI_ERROR_ERRNO', $errno);
    define('CIGI_ERROR_REASON', $error);
    define('CIGI_ERROR_FILE', $file);
    define('CIGI_ERROR_LINE', $line);

    _cigi_dispatch_page(CIGI_PAGE_ERROR);
    die(); // To prevent any weirdness trying to continue rendering the page
}

function cigi_dispatch() {
    if(!defined('_CIGI_CONFIGURED')) {
        trigger_error('cigi application does not appear to have been configured; ' .
                      'try loading a configuration file using cigi_configure_from()', E_USER_ERROR);
    }

    // Set up error handling
    set_error_handler('_cigi_error_handler');

    // Serve up the page
    _cigi_dispatch_page(CIGI_REQUEST);
}

?>