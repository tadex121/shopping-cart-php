<?php

//mapperTranslation ID IS EQUAL THAN ID IN DATABLE 'mappers'

$mapper = array(
    '/' => array(
        'directory' => 'portal',
        'controller' => 'index',
        'action' => 'index'
    ),
    '/log-out' => array(
        'directory' => 'portal',
        'controller' => 'index',
        'action' => 'logout'
    ),
    /* PRIJAVA */
    '/prijava'  => array(
        'directory' => 'portal',
        'controller' => 'index',
        'action' => 'login'
    ),
    /* REGISTRACIJA */
    '/registracija'  => array(
        'directory' => 'portal',
        'controller' => 'index',
        'action' => 'register'
    ),
    /*
     * 
     * 
     * AJAX
     * 
     * 
     */
    '/user_login' => array(
        'directory' => 'portal',
        'controller' => 'user',
        'action' => 'user_login'
    ),
    '/user_register' => array(
        'directory' => 'portal',
        'controller' => 'user',
        'action' => 'user_register'
    ),
    '/show_list' => array(
        'directory' => 'portal',
        'controller' => 'index',
        'action' => 'show_list'
    ),
    '/edit_list' => array(
        'directory' => 'portal',
        'controller' => 'index',
        'action' => 'edit_list'
    ),
    '/delete_list' => array(
        'directory' => 'portal',
        'controller' => 'index',
        'action' => 'delete_list'
    ),
    '/mark_completed' => array(
        'directory' => 'portal',
        'controller' => 'index',
        'action' => 'mark_completed'
    ),
);

