<?php

/**
 * Contao Open Source CMS
 */

/**
 * @file tl_dummy.php
 * @author Sascha Weidner
 * @package sioweb.dummybundle
 * @copyright Sioweb, Sascha Weidner
 */

/**
 * @Wiki: Hier wird demonstriert, wie Callbacks als Service ausgeführt werden
 * @see /src/Resources/config/services.yml
 * @see /src/DependencyInjection/Extension.php: $loader->load('services.yml');
 */

/**
 * Hinweis: Hier wird kein SQL notiert, SQL wird in der
 * Entity /src/Entity/Dummy.php hinterlegt!
 */

$GLOBALS['TL_DCA']['eonm_times'] = array(
    // Config
    'config' => array
    (
        'dataContainer' => 'Table',
        'switchToEdit' => true,
        'enableVersioning' => true,
        'sql' => [
            'keys' => [
                'id' => 'primary',
            ]
        ]
    ),

    // List
    'list' => array
    (
        'label' => array
        (
            'fields' => array('id', 'title'),
            'showColumns' => true,
            'format' => '%s',
            //'label_callback' => array('tl_betasearch_search_backend', 'searchLabelCallback')
        ),
        'sorting' => array
        (
            'mode'                    => 2,
            'flag'                    => 1,
            'fields'                  => array('time'),
            'headerFields'            => array('time'),
            'panelLayout'             => 'filter;sort,search,limit',
            //'child_record_class'      => 'no_padding'
        ),
        'global_operations' => array
        (
            /*'all' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'                => 'act=select',
                'class'               => 'header_edit_all',
                'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
            ),*/
        ),
        'operations' => array
        (
            'edit' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['eonm_times']['edit'],
                'href'                => 'table=tl_content',
                'icon'                => 'edit.svg'
            ),
            'toggle' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['eonm_times']['toggle'],
                'icon'                => 'visible.svg',
                'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
                'button_callback'     => array('sioweb.dummy.dca.tl_dummy', 'toggleIcon')
            ),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['eonm_times']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.svg'
            )
        )
    ),

    // Palettes
    'palettes' => array
    (
        '__selector__'                => [],
        'default'                     => 'time'
    ),

    'subpalettes' => array
    (
        '' => ''
    ),

    // Fields
    'fields' => array
    (
        // Achtung, keine Einträge mehr, in denen lediglich 'sql' => '' Einträge vorkamen!
        // ID, tstamp befinden sich in der Entity "Entity\Dummy.php"
        'id' => [
            'sql' => "int(10) unsigned NOT NULL auto_increment"
        ],
        'time' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['eonm_times']['time'],
            'inputType'               => 'text',
            'exclude'                 => true,
            'filter'                  => true,
            'sorting'                 => true,
            'eval'                    => array('mandatory'=>true,'maxlength'=>255,'tl_class'=>'w50','gsIgnore'=>true),
            'sql' => "varchar(255) NOT NULL default ''"
        ),
    )
);