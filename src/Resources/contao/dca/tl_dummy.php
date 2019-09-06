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
    'dataContainer'               => 'Table',
    'enableVersioning'            => true
  ),

  // List
  'list' => array
  (
    'sorting' => array
    (
      'mode'                    => 4,
      'flag'                    => 2,
      'fields'                  => array('time'),
      'headerFields'            => array('time'),
      'panelLayout'             => 'filter;sort,search,limit',
      'child_record_class'      => 'no_padding'
    ),
    'global_operations' => array
    (
      'all' => array
      (
        'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
        'href'                => 'act=select',
        'class'               => 'header_edit_all',
        'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
      ),
    ),
    'operations' => array
    (
      'edit' => array
      (
        'label'               => &$GLOBALS['TL_LANG']['eonm_times']['edit'],
        'href'                => 'table=tl_content',
        'icon'                => 'edit.svg'
      ),
      'editheader' => array
      (
        'label'               => &$GLOBALS['TL_LANG']['eonm_times']['editmeta'],
        'href'                => 'act=edit',
        'icon'                => 'header.svg'
      ),
      'copy' => array
      (
        'label'               => &$GLOBALS['TL_LANG']['eonm_times']['copy'],
        'href'                => 'act=paste&amp;mode=copy',
        'icon'                => 'copy.svg'
      ),
      'cut' => array
      (
        'label'               => &$GLOBALS['TL_LANG']['eonm_times']['cut'],
        'href'                => 'act=paste&amp;mode=cut',
        'icon'                => 'cut.svg'
      ),
      'delete' => array
      (
        'label'               => &$GLOBALS['TL_LANG']['eonm_times']['delete'],
        'href'                => 'act=delete',
        'icon'                => 'delete.svg',
        'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
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
    '__selector__'                => array('time'),
    'default'                     => '{title_time}'
  ),

  'subpalettes' => array
  (
    'published'           => 'start,stop'
  ),

  // Fields
  'fields' => array
  (
    // Achtung, keine Einträge mehr, in denen lediglich 'sql' => '' Einträge vorkamen!
    // ID, tstamp befinden sich in der Entity "Entity\Dummy.php"
    'title' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['eonm_times']['time'],
      'inputType'               => 'text',
      'exclude'                 => true,
      'filter'                  => true,
      'sorting'                 => true,
      'eval'                    => array('mandatory'=>true,'maxlength'=>255,'tl_class'=>'w50','gsIgnore'=>true),
    ),
  )
);