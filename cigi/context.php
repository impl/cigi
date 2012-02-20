<?php

define('CIGI_PARAMETER_DEFAULT', 'page');

define('CIGI_INDEX_PAGE_DEFAULT', 'index');
define('CIGI_NOT_FOUND_PAGE_DEFAULT', 'not_found');
define('CIGI_ERROR_PAGE_DEFAULT', 'error');

function _cigi_context_coalesce($array, $key, $default) {
    return empty($array[$key]) ? $default : $array[$key];
}

function cigi_context_new(array $configuration) {
    $context = array();
    $context['parameter'] = _cigi_context_coalesce($configuration, 'parameter', CIGI_PARAMETER_DEFAULT);

    $context['paths'] = array();
    $context['paths']['root'] = $configuration['root_path'];
    $context['paths']['pages'] = $context['paths']['root'] . '/pages';
    $context['paths']['templates'] = $context['paths']['root'] . '/templates';
    $context['paths']['slots'] = $context['paths']['root'] . '/slots';

    $context['pages'] = array();
    $context['pages']['index'] = _cigi_context_coalesce($configuration, 'index_page', CIGI_INDEX_PAGE_DEFAULT);
    $context['pages']['not_found'] = _cigi_context_coalesce($configuration, 'not_found_page',
                                                            CIGI_NOT_FOUND_PAGE_DEFAULT);
    $context['pages']['error'] = _cigi_context_coalesce($configuration, 'error_page', CIGI_ERROR_PAGE_DEFAULT);

    $context['requested_page'] =
        empty($_GET[$context['parameter']])
        ? $context['pages']['index']
        : $_GET[$context['parameter']];

    $context['configuration'] = $configuration;

    return $context;
}

?>