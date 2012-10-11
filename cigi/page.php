<?php
// This file is part of cigi, a remarkably simple framework for the Web.
//
// Copyright (c) 2008-2010 Invectorate LLC. All rights reserved.

define('CIGI_PAGE_DECORATOR', 'decorator');
define('CIGI_PAGE_VARIABLES', 'variables');
define('CIGI_PAGE_TEMPLATE', 'template');
define('CIGI_PAGE_NAME', 'name');
define('CIGI_PAGE_SOURCE', 'source');
define('CIGI_PAGE_PATH', 'path');
define('CIGI_PAGE_OUTPUT', 'output');

function _cigi_page_sanitize($name) {
    return trim(str_replace(array('../', '..\\'), '', preg_replace('#[^\x21-\x7E]#i', '', $name)), '/\\');
}

function _cigi_page_require($path) {
    // Requires a file in a new scope
    return require($path);
}

function cigi_page_get_from($source, $name) {
    $name = _cigi_page_sanitize($name);

    if($name === '' || !is_dir($source . '/' . $name) || !file_exists($source . '/' . $name . '/page.php') || is_dir($source . '/' . $name . '/page.php')) {
        if($name === CIGI_PAGE_NOT_FOUND) {
            trigger_error(sprintf('Cannot retrieve page %s and the not-found page does not exist', CIGI_REQUEST), E_USER_ERROR);
        }

        return cigi_page_get(CIGI_PAGE_NOT_FOUND);
    }

    $page = array();
    $page[CIGI_PAGE_NAME] = $name;
    $page[CIGI_PAGE_SOURCE] = $source;
    $page[CIGI_PAGE_PATH] = $source . '/' . $name;

    $result = _cigi_page_require($source . '/' . $name . '/page.php');
    if(!is_array($result)) {
        trigger_error(sprintf('Cannot retrieve page %s: expected page to return an array, but got %s', $name, gettype($result)), E_USER_ERROR);
    }

    $page = array_merge($page, $result);

    return $page;
}

function cigi_page_get($name) {
    return cigi_page_get_from(CIGI_PATH_PAGES, $name);
}

function cigi_page_template_get($page, $template) {
    $sources = array(CIGI_PATH_PAGES => 'pages',
                     CIGI_PATH_SLOTS => 'slots');

    $source = isset($sources[$page[CIGI_PAGE_SOURCE]]) ? $sources[$page[CIGI_PAGE_SOURCE]] : $sources[CIGI_PATH_PAGES];

    return cigi_template_get(CIGI_PATH_TEMPLATES . '/' . $source . '/' . $page[CIGI_PAGE_NAME], $template);
}

function cigi_page_render($page) {
    $template = isset($page[CIGI_PAGE_VARIABLES]) && is_array($page[CIGI_PAGE_VARIABLES]) ? $page[CIGI_PAGE_VARIABLES] : array();

    if(!cigi_template_is($page[CIGI_PAGE_TEMPLATE])) {
        $page[CIGI_PAGE_TEMPLATE] = cigi_page_template_get($page, $page[CIGI_PAGE_TEMPLATE]);
    }

    $compiled = cigi_template_compile($page[CIGI_PAGE_TEMPLATE], array('template' => $template, 'page' => $page));

    if(isset($page[CIGI_PAGE_DECORATOR])) {
        if(!cigi_template_is($page[CIGI_PAGE_DECORATOR])) {
            $page[CIGI_PAGE_DECORATOR] = cigi_page_template_get($page, $page[CIGI_PAGE_DECORATOR]);
        }

        return cigi_template_compile($page[CIGI_PAGE_DECORATOR], array('template' => $template, 'page' => $page, 'inner' => $compiled));
    }
    else {
        return $compiled;
    }
}

?>