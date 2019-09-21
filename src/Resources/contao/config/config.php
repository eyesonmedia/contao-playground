<?php

/**
 * Contao Open Source CMS
 */

/**
 * @file autoload.php
 * @author Sascha Weidner
 * @package sioweb.dummybundle
 * @copyright Sioweb, Sascha Weidner
 */

// Achtung:
// Diese Datei ist nur in Version 4.4 nötig, aber 4.5 kann die Datei weggelassen werden.
// Die Config befindet sich dann in der Klasse DummyBundle/src/EventListener/System
// Der "Hook", der die Klasse aufruft, ist in DummyBundle/src/Resoureces/config/listener.yml definiert
if(VERSION <= 4.5) {
    if (TL_MODE === 'FE') { // Früher TL_MODE == 'FE'
        // Pfad ggf. anpassen
        // Alle Dateien in /src/Ressources/public werden unter /web/bundles/bundle-name
        // als Symlink veröffentlicht nach composer install/update
        //$GLOBALS['TL_CSS'][] = 'bundles/dummybundle/css/dummy.css';
        //$GLOBALS['TL_JAVASCRIPT'][] = 'bundles/dummybundle/js/dummy.js';
    }
/*
    array_insert($GLOBALS['TL_CTE']['texts'], 2, array(
        'content_dummy' => 'Sioweb\DummyBundle\ContentElement\ContentDummy',
    ));
*/
/*
    array_insert($GLOBALS['BE_MOD']['DummyBundle'], 1 ,[
        'NuvisanManageRegistration' => [
            'tables'      => array('eonm_registration'),
            'table' => ['TableWizard', 'importTable'],
            'list' => ['ListWizard', 'importList'],
            //'callback'    => 'Sioweb\DummyBundle\ModuleBetasearch',
            'export'     => array('DummyBundle', 'exportRegistration')
        ]
    ]);

    array_insert($GLOBALS['BE_MOD']['DummyBundle'], 2 ,[
        'NuvisanManageIntern' => [
            'tables'      => array('eonm_intern'),
            'table' => ['TableWizard', 'importTable'],
            'list' => ['ListWizard', 'importList'],
            //'callback'    => 'Sioweb\DummyBundle\ModuleBetasearch',
            'export'     => array('DummyBundle', 'exportRegistration')
        ]
    ]);

    array_insert($GLOBALS['BE_MOD']['DummyBundle'], 0 ,[
        'NuvisanManageStudy' => [
            'tables'      => array('eonm_registration'),
            'table' => ['TableWizard', 'importTable'],
            'list' => ['ListWizard', 'importList'],
            //'callback'    => 'Sioweb\DummyBundle\ModuleBetasearch',
            //'export'     => array('DummyBundle', 'exportRegistration')
        ]
    ]);
*/
}

    array_insert($GLOBALS['BE_MOD'],0, array(
        'DummyBundle' => array(
            'NuvisanManageStudy' => array(
                'tables'      => array('eonm_study'),
                'table' => ['TableWizard', 'importTable'],
                'list' => ['ListWizard', 'importList'],
                //'callback'    => 'Sioweb\DummyBundle\ModuleBetasearch',
                //'export'     => array('DummyBundle', 'exportRegistration')
            ),
            'NuvisanManageRegistration' => array(
                'tables'      => array('eonm_registration'),
                'table' => ['TableWizard', 'importTable'],
                'list' => ['ListWizard', 'importList'],
                //'callback'    => 'Sioweb\DummyBundle\ModuleBetasearch',
                'export'     => array('DummyBundle', 'exportRegistration')
            ),
            'NuvisanManageIntern' => array(
                'tables'      => array('eonm_intern'),
                'table' => ['TableWizard', 'importTable'],
                'list' => ['ListWizard', 'importList'],
                //'callback'    => 'Sioweb\DummyBundle\ModuleBetasearch',
                'export'     => array('DummyBundle', 'exportRegistration')
            )
        )
    ));


$GLOBALS['BE_MOD']['DummyBundle']['NuvisanManageStudy']['checkin'] = array('Sioweb\DummyBundle\SiowebDummyBundle', 'setCheckin');
$GLOBALS['BE_MOD']['DummyBundle']['NuvisanManageStudy']['checkout'] = array('Sioweb\DummyBundle\SiowebDummyBundle', 'setCheckout');

$GLOBALS['BE_MOD']['DummyBundle']['NuvisanManageRegistration']['study'] = array('Sioweb\DummyBundle\SiowebDummyBundle', 'startStudy');
$GLOBALS['BE_MOD']['DummyBundle']['NuvisanManageIntern']['study'] = array('Sioweb\DummyBundle\SiowebDummyBundle', 'startStudyIntern');

$GLOBALS['BE_MOD']['DummyBundle']['NuvisanManageRegistration']['storno'] = array('Sioweb\DummyBundle\SiowebDummyBundle', 'makeStorno');
$GLOBALS['BE_MOD']['DummyBundle']['NuvisanManageIntern']['storno'] = array('Sioweb\DummyBundle\SiowebDummyBundle', 'makeStornoIntern');

$GLOBALS['BE_MOD']['DummyBundle']['NuvisanManageRegistration']['export'] = array('Sioweb\DummyBundle\SiowebDummyBundle', 'exportRegistration');
$GLOBALS['BE_MOD']['DummyBundle']['NuvisanManageIntern']['export'] = array('Sioweb\DummyBundle\SiowebDummyBundle', 'exportInternRegistration');