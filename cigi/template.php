<?php
// This file is part of cigi, a remarkably simple framework for the Web.
//
// Copyright (c) 2008-2010 Invectorate LLC. All rights reserved.

define('CIGI_TEMPLATE_SOURCE', 'source');
define('CIGI_TEMPLATE_NAME', 'name');

function cigi_template_get($source, $name)
{
    $template = array(CIGI_TEMPLATE_SOURCE => $source, CIGI_TEMPLATE_NAME => $name);

    $file = $source . '/' . $name;
    if(file_exists($file) && !is_dir($file)) {
        return $template;
    }

    trigger_error(sprintf('Template %s does not exist or is a directory', $file), E_USER_ERROR);
}

function cigi_template_is($template)
{
    return is_array($template) && isset($template[CIGI_TEMPLATE_SOURCE]) && isset($template[CIGI_TEMPLATE_NAME]);
}

function cigi_template_compile($i, $parameters)
{
    if(!cigi_template_is($i)) {
        trigger_error('Not a template', E_USER_ERROR);
    }

    extract($parameters, EXTR_SKIP);

    ob_start();
    require($i[CIGI_TEMPLATE_SOURCE] . '/' . $i[CIGI_TEMPLATE_NAME]);
    return ob_get_clean();
}

?>