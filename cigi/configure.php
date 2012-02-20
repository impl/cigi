<?php
// This file is part of cigi, a remarkably simple framework for the Web.
//
// Copyright (c) 2008-2010 Invectorate LLC. All rights reserved.

function _cigi_maybe_define($name, $value) {
    if(!defined($name)) {
        define($name, $value);
    }
}

function cigi_configure_from($source) {
    if(defined('_CIGI_CONFIGURED')) {
        trigger_error('cigi application is already configured!', E_USER_ERROR);
    }
    
    if(is_dir($source)) {
        $source .= '/configuration.php';
    }

    if(!is_readable($source) || is_dir($source)) {
        trigger_error(sprintf('Unreadable configuration path: Not readable or a directory: %s', $source), E_USER_ERROR);
    }

    require($source);

    // Page accessor
    _cigi_maybe_define('CIGI_PARAMETER', 'page');

    // Paths
    define('CIGI_PATH_ROOT', realpath(dirname($source)));
    define('CIGI_PATH_PAGES', CIGI_PATH_ROOT . '/pages');
    define('CIGI_PATH_TEMPLATES', CIGI_PATH_ROOT . '/templates');
    define('CIGI_PATH_SLOTS', CIGI_PATH_ROOT . '/slots');

    // Pages
    _cigi_maybe_define('CIGI_PAGE_INDEX', 'index');
    _cigi_maybe_define('CIGI_PAGE_NOT_FOUND', 'not-found');
    _cigi_maybe_define('CIGI_PAGE_ERROR', 'error');

    // Requested page
    define('CIGI_REQUEST', empty($_GET[CIGI_PARAMETER]) ? CIGI_PAGE_INDEX : $_GET[CIGI_PARAMETER]);

    define('_CIGI_CONFIGURED', true);
}

?>