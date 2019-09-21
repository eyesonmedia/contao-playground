<?php

use Contao\DataContainer;

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


$GLOBALS['TL_DCA']['eonm_study']['list']['operations']['checkin'] = [
    'label'           => &$GLOBALS['TL_LANG']['eonm_study']['checkin'],
    //'icon'            => 'workflow-start.svg',
    'button_callback' => ['eonm_study_cancel_backend', 'checkinButtonStudy']
];
/*
$GLOBALS['TL_DCA']['eonm_study']['list']['operations']['checkout'] = [
    'label'           => &$GLOBALS['TL_LANG']['eonm_study']['checkout'],
    //'icon'            => 'workflow-start.svg',
    'button_callback' => ['eonm_study_cancel_backend', 'checkoutButtonStudy']
];
*/

$GLOBALS['TL_DCA']['eonm_study'] = array(
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
            'fields' => array('cdate', 'title','firstname', 'lastname', 'birthday', 'email', 'registerdate', 'registertime', 'status'),
            'showColumns' => true,
            'format' => '%s',
            //'label_callback' => array('tl_betasearch_search_backend', 'searchLabelCallback')
            //'label_callback' => array('eonm_registration_cancel_backend', 'cancelButtonRegistration')
        ),
        'sorting' => array
        (
            'mode'                    => 2,
            'flag'                    => 1,
            'fields'                  => array('cdate DESC'),
            //'headerFields'            => array('firstname, lastname'),
            'panelLayout'             => 'filter;sort,search,limit',
            //'child_record_class'      => 'no_padding'
        ),
        'global_operations' => array
        (
            /*
            'export' => array
            (
                'label'               => 'Registrationen als CSV exportierten',
                'href'                => 'key=export',
                'class'               => 'header_sync',
                //'class'               => 'header_xls_export',
                #'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
                #'showOnSelect'        => true
            ),
            */
        ),
        'operations' => array
        (
            /*'edit' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['eonm_registration']['edit'],
                'href'                => 'table=tl_content',
                'icon'                => 'edit.svg'
            ),*/
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['eonm_study']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.svg'
            ),
            'checkin' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['eonm_study']['checkin'],
                'href'                => 'act=show',
                'icon'                => 'show.svg',
                'button_callback' => ['eonm_study_cancel_backend', 'checkinButtonStudy']
            ),
            /*'checkout' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['eonm_study']['checkout'],
                'href'                => 'act=show',
                'icon'                => 'show.svg',
                'button_callback' => ['eonm_study_cancel_backend', 'checkoutButtonStudy']
            ),*/
        )
    ),

    // Palettes
    'palettes' => array
    (
        '__selector__'                => [],
        'default'                     => 'firstname, lastname'
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
        'cdate' => [
            'sql' => "int(10) unsigned NOT NULL",
            'label' => &$GLOBALS['TL_LANG']['eonm_study']['cdate'],
        ],
        'tstamp' => [
            'sql' => "int(10) unsigned NOT NULL",
            'label' => &$GLOBALS['TL_LANG']['eonm_study']['tstamp'],
        ],
        'timeid' => [
            'sql' => "int(11) unsigned NOT NULL"
        ],
        'studyid' => [
            'sql' => "int(11) unsigned NOT NULL"
        ],
        'title' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['eonm_study']['title'],
            'exclude'                 => true,
            'filter'                  => true,
            'sorting'                 => true,
            'inputType'               => 'select',
            'options'                 => [1=>'weiblich', 2=>'meanlich'],
            'default'                 => 1,
            //'foreignKey'            => 'tl_user.name',
            //'options_callback'      => array('CLASS', 'METHOD'),
            'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'registergroup' => [
            'label' => &$GLOBALS['TL_LANG']['eonm_study']['registergroup'],
            'exclude' => true,
            'search' => true,
            'sorting' => true,
            'flag' => 1,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 510],
            'sql' => "varchar(510) NOT NULL default ''"
        ],
        'firstname' => [
            'label' => &$GLOBALS['TL_LANG']['eonm_study']['firstname'],
            'exclude' => true,
            'search' => true,
            'sorting' => true,
            'flag' => 1,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 510],
            'sql' => "varchar(510) NOT NULL default ''"
        ],
        'lastname' => [
            'label' => &$GLOBALS['TL_LANG']['eonm_study']['lastname'],
            'exclude' => true,
            'search' => true,
            'sorting' => true,
            'flag' => 1,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 510],
            'sql' => "varchar(510) NOT NULL default ''"
        ],
        'email' => [
            'label' => &$GLOBALS['TL_LANG']['eonm_study']['email'],
            'exclude' => true,
            'search' => true,
            'sorting' => true,
            'flag' => 1,
            'inputType' => 'text',
            'eval'                    => array('mandatory'=>true, 'rgxp'=>'email', 'maxlength'=>510, 'decodeEntities'=>true, 'tl_class'=>'w50'),
            //'eval' => ['mandatory' => true, 'maxlength' => 510],
            'sql' => "varchar(510) NOT NULL default ''"
        ],
        'birthday' => [
            'label' => &$GLOBALS['TL_LANG']['eonm_study']['birthday'],
            'exclude' => true,
            'search' => true,
            'sorting' => true,
            'flag' => 1,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 510],
            'sql' => "varchar(510) NOT NULL default ''"
        ],
        'phone' => [
            'label' => &$GLOBALS['TL_LANG']['eonm_study']['phone'],
            'exclude' => true,
            'search' => true,
            'sorting' => true,
            'flag' => 1,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 510],
            'sql' => "varchar(510) NOT NULL default ''"
        ],
        'street' => [
            'label' => &$GLOBALS['TL_LANG']['eonm_study']['street'],
            'exclude' => true,
            'search' => true,
            'sorting' => true,
            'flag' => 1,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 510],
            'sql' => "varchar(510) NOT NULL default ''"
        ],
        'zip' => [
            'label' => &$GLOBALS['TL_LANG']['eonm_study']['zip'],
            'exclude' => true,
            'search' => true,
            'sorting' => true,
            'flag' => 1,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 510],
            'sql' => "varchar(510) NOT NULL default ''"
        ],
        'city' => [
            'label' => &$GLOBALS['TL_LANG']['eonm_study']['city'],
            'exclude' => true,
            'search' => true,
            'sorting' => true,
            'flag' => 1,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 510],
            'sql' => "varchar(510) NOT NULL default ''"
        ],
        'registerdate' => [
            'label' => &$GLOBALS['TL_LANG']['eonm_study']['registerdate'],
            'exclude' => true,
            'search' => true,
            'sorting' => true,
            'flag' => 1,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 510],
            'sql' => "varchar(510) NOT NULL default ''"
        ],
        'registertime' => [
            'label' => &$GLOBALS['TL_LANG']['eonm_study']['registertime'],
            'exclude' => true,
            'search' => true,
            'sorting' => true,
            'flag' => 1,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 510],
            'sql' => "varchar(510) NOT NULL default ''"
        ],
        'study' => [
            'label' => &$GLOBALS['TL_LANG']['eonm_study']['study'],
            'exclude' => true,
            'search' => true,
            'sorting' => true,
            'flag' => 1,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 510],
            'sql' => "varchar(510) NOT NULL default ''"
        ],
        'status' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['eonm_study']['status'],
            'exclude'                 => true,
            'filter'                  => true,
            'sorting'                 => true,
            'inputType'               => 'select',
            'options'                 => [1=>'aktiv', 2=>'storniert'],
            'default'                 => 1,
            //'foreignKey'            => 'tl_user.name',
            //'options_callback'      => array('CLASS', 'METHOD'),
            'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        )
    )
);


/**
 * Class tl_article_workflow_backend
 *
 */
class  eonm_study_cancel_backend extends Backend
{
    /**
     * All pages
     *
     * @var null
     */
    protected static $arrPages = null;


    /**
     * Generate the workflow button
     *
     * @param $arrRow
     * @param $href
     * @param $label
     * @param $title
     * @param $icon
     * @param $attributes
     *
     * @return string
     */
    public function checkinButtonStudy($arrRow)
    {
        if($arrRow['status'] == 'offen') {
            return '<a onclick="if(!confirm(\'Soll diese Anmeldung als Checkin/Einweisung hinterlegt werden?\'))return false;Backend.getScrollOffset()" href="' . $this->addToUrl('do=NuvisanManageStudy&key=checkin&study_id='.$arrRow['id'], true, ['do']) . '" title="Checkin/Einweisung" style="display: inline-block; padding: 5px 8px; font-weight:bold; color: white; background: green">Checkin/Einweisung</a>';
        } elseif ($arrRow['status'] == 'Checkin/Einweisung') {
            return '<a onclick="if(!confirm(\'Soll diese Anmeldung als Blut abgenommen/Auszahlung werden?\'))return false;Backend.getScrollOffset()" href="' . $this->addToUrl('do=NuvisanManageStudy&key=checkout&study_id='.$arrRow['id'], true, ['do']) . '" title="stornieren" style="display: inline-block; padding: 5px 8px; font-weight:bold; color: white; background: green">Blut abgenommen/Auszahlung</a>';
        } else {
            return '<span style="cursor: no-drop; display: inline-block; padding: 4px 6px; font-weight:bold; color: white; background: #c42302">storniert</span>';
        }
    }

    public function checkoutButtonStudy($arrRow)
    {

        #var_dump();die;
        //$checkWorkflow = \System::getContainer()->get('freshframes_workflow.datacontainer.workflow')->checkWorkflowExisting($arrRow['id']);

        //die('ddd');
        #var_dump($arrRow['id']);
        // check if worklow is exist
        /*

        if($checkWorkflow['workflow'] == NULL) {
            return '<a href="' . $this->addToUrl('do=FreshframesWorkflowManageWorkflow&act=create&page_id='.$checkWorkflow['page']->getId(), true,
                    ['do']) . '" title="' . StringUtil::specialchars($title) . '"' . $attributes . ' style="display: inline-block; padding: 4px 6px; font-weight:bold; color: white; background: green">Workflow starten</a>';
            #return '<a href="' . $this->addToUrl('do=themes&table=tl_layout&act=edit&id=' . $arrPage['layout'], true,
            #        ['do']) . '" title="' . StringUtil::specialchars($title) . '"' . $attributes . '>' . Image::getHtml($icon, $label) . '</a>';
        } else {
            return '<span style="cursor: no-drop; display: inline-block; padding: 4px 6px; font-weight:bold; color: white; background: orange">Workflow gestartet</span>';
        }*/
        if($arrRow['status'] == 'offen') {
            return '<a onclick="if(!confirm(\'Soll diese Anmeldung als Checkin/Einweisung hinterlegt werden?\'))return false;Backend.getScrollOffset()" href="' . $this->addToUrl('do=NuvisanManageStudy&key=checkin&study_id='.$arrRow['id'], true, ['do']) . '" title="Checkin/Einweisung" style="display: inline-block; padding: 5px 8px; font-weight:bold; color: white; background: green">Checkin/Einweisung</a>';
        } elseif ($arrRow['status'] == 'Checkin/Einweisung') {
            return '<a onclick="if(!confirm(\'Soll diese Anmeldung als Blut abgenommen/Auszahlung werden?\'))return false;Backend.getScrollOffset()" href="' . $this->addToUrl('do=NuvisanManageStudy&key=checkout&study_id='.$arrRow['id'], true, ['do']) . '" title="Blut abgenommen/Auszahlung" style="display: inline-block; padding: 5px 8px; font-weight:bold; color: white; background: green">Blut abgenommen/Auszahlung</a>';
        } else {
            return '<span style="cursor: no-drop; display: inline-block; padding: 4px 6px; font-weight:bold; color: #222;">Blut abgenommen/Auszahlung</span>';
        }

    }


}
