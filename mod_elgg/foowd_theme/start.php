<?php

elgg_register_event_handler('init','system','foowd_theme_init');

function foowd_theme_init() {
    elgg_register_plugin_hook_handler('index', 'system', 'new_index');
}

function new_index() {
    if (!include_once(dirname(__FILE__) . "/index.php"))
        return false;

    return true;
}
