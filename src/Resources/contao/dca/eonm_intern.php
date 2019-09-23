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
$GLOBALS['TL_DCA']['eonm_intern']['list']['operations']['storno'] = [
    'label'           => &$GLOBALS['TL_LANG']['eonm_intern']['storno'],
    'icon'            => 'workflow-start.svg',
    'button_callback' => ['eonm_intern_cancel_backend', 'cancelButtonRegistration']
];

$GLOBALS['TL_DCA']['eonm_intern']['list']['operations']['study'] = [
    'label'           => &$GLOBALS['TL_LANG']['eonm_intern']['study'],
    'icon'            => 'workflow-start.svg',
    'button_callback' => ['eonm_intern_cancel_backend', 'startButtonStudy']
];

$GLOBALS['TL_DCA']['eonm_intern'] = array(
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
            'fields' => array('cdate', 'title','firstname', 'lastname', 'birthday', 'registerdate', 'study', 'status'),
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
            'export' => array
            (
                'label'               => 'Interne Registrationen als CSV exportierten',
                'href'                => 'key=export',
                'class'               => 'header_sync',
                //'class'               => 'header_xls_export',
                #'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
                #'showOnSelect'        => true
            ),
        ),
        'operations' => array
        (
            'edit' => [
                'label' => &$GLOBALS['TL_LANG']['eonm_intern']['edit'],
                'href' => 'act=edit',
                'icon' => 'edit.svg',
                //'button_callback' => ['tl_workflow_workflow_backend', 'generateButtonEdit']
            ],
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['eonm_intern']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.svg'
            ),
            'storo' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['eonm_intern']['storno'],
                'href'                => 'act=show',
                'icon'                => 'show.svg',
                'button_callback' => ['eonm_intern_cancel_backend', 'cancelButtonRegistration']
            ),
            'study' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['eonm_intern']['study'],
                'href'                => 'act=show',
                'icon'                => 'show.svg',
                'button_callback' => ['eonm_intern_cancel_backend', 'startButtonStudy']
            ),
        )
    ),

    // Palettes
    'palettes' => array
    (
        '__selector__'                => [],
        'default'                     => 'firstname, lastname, email, phone, status, registerdate'
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
            'sql' => "int(50) unsigned NOT NULL",
            'label' => &$GLOBALS['TL_LANG']['eonm_intern']['cdate'],
        ],
        'tstamp' => [
            'sql' => "int(50) unsigned NOT NULL",
            'label' => &$GLOBALS['TL_LANG']['eonm_intern']['tstamp'],
        ],
        'timeid' => [
            'sql' => "int(11) unsigned NOT NULL"
        ],
        'title' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['eonm_intern']['title'],
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
            'label' => &$GLOBALS['TL_LANG']['eonm_intern']['registergroup'],
            'exclude' => true,
            'search' => true,
            'sorting' => true,
            'flag' => 1,
            'inputType' => 'text',
            'eval' => ['disabled' => true, 'maxlength' => 510],
            'sql' => "varchar(510) NOT NULL default ''"
        ],
        'firstname' => [
            'label' => &$GLOBALS['TL_LANG']['eonm_intern']['firstname'],
            'exclude' => true,
            'search' => true,
            'sorting' => true,
            'flag' => 1,
            'inputType' => 'text',
            'eval' => ['maxlength' => 510, 'tl_class'=>'w50', 'disabled' => true, 'style' => 'border: 0; background: white; color: #222; padding-left: 0;'],
            'sql' => "varchar(510) NOT NULL default ''"
        ],
        'lastname' => [
            'label' => &$GLOBALS['TL_LANG']['eonm_intern']['lastname'],
            'exclude' => true,
            'search' => true,
            'sorting' => true,
            'flag' => 1,
            'inputType' => 'text',
            'eval' => ['maxlength' => 510, 'tl_class'=>'w50', 'disabled' => true, 'style' => 'border: 0; background: white; color: #222; padding-left: 0;'],
            'sql' => "varchar(510) NOT NULL default ''"
        ],
        'email' => [
            'label' => &$GLOBALS['TL_LANG']['eonm_intern']['email'],
            'exclude' => true,
            'search' => true,
            'sorting' => true,
            'flag' => 1,
            'inputType' => 'text',
            'eval'                    => array('rgxp'=>'email', 'maxlength'=>510, 'decodeEntities'=>true, 'tl_class'=>'w50', 'disabled' => true, 'style' => 'border: 0; background: white; color: #222; padding-left: 0;'),
            //'eval' => ['mandatory' => true, 'maxlength' => 510],
            'sql' => "varchar(510) NOT NULL default ''"
        ],
        'birthday' => [
            'label' => &$GLOBALS['TL_LANG']['eonm_intern']['birthday'],
            'exclude' => true,
            'search' => true,
            'sorting' => true,
            'flag' => 1,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 510],
            'sql' => "varchar(510) NOT NULL default ''"
        ],
        'phone' => [
            'label' => &$GLOBALS['TL_LANG']['eonm_intern']['phone'],
            'exclude' => true,
            'search' => true,
            'sorting' => true,
            'flag' => 1,
            'inputType' => 'text',
            'eval' => ['maxlength' => 510, 'tl_class'=>'w50', 'disabled' => true, 'style' => 'border: 0; background: white; color: #222; padding-left: 0;'],
            'sql' => "varchar(510) NOT NULL default ''"
        ],
        'street' => [
            'label' => &$GLOBALS['TL_LANG']['eonm_intern']['street'],
            'exclude' => true,
            'search' => true,
            'sorting' => true,
            'flag' => 1,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 510],
            'sql' => "varchar(510) NOT NULL default ''"
        ],
        'zip' => [
            'label' => &$GLOBALS['TL_LANG']['eonm_intern']['zip'],
            'exclude' => true,
            'search' => true,
            'sorting' => true,
            'flag' => 1,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 510],
            'sql' => "varchar(510) NOT NULL default ''"
        ],
        'city' => [
            'label' => &$GLOBALS['TL_LANG']['eonm_intern']['city'],
            'exclude' => true,
            'search' => true,
            'sorting' => true,
            'flag' => 1,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 510],
            'sql' => "varchar(510) NOT NULL default ''"
        ],
        'registerdate' => [
            'label' => &$GLOBALS['TL_LANG']['eonm_intern']['registerdate'],
            'exclude' => true,
            'search' => true,
            'sorting' => true,
            'flag' => 1,
            //'inputType' => 'text',
            'inputType'               => 'select',
            //'options'                 => [1=>'weiblich', 2=>'meanlich'],
            'default'                 => 1,
            //'eval' => ['mandatory' => true, 'maxlength' => 510],
            'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50'),
            'sql' => "varchar(510) NOT NULL default ''",
            'options_callback' => ['eonm_intern_cancel_backend', 'internRegisterCallback'],
            'save_callback' => [
                function ($varValue, DataContainer $dataContainer)
                {
                    $termin = \Contao\System::getContainer()->get('sioweb_dummybundle.service.search')->setInternDate($varValue, $dataContainer);

                    return $termin;
                    //return \System::getContainer()->get('freshframes_workflow.datacontainer.workflow')->prepareWorkflowContent($varValue, $dataContainer);
                }
            ],
        ],
        'study' => [
            'label' => &$GLOBALS['TL_LANG']['eonm_intern']['study'],
            'exclude' => true,
            'search' => true,
            'sorting' => true,
            'flag' => 1,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 510],
            'sql' => "varchar(510) NOT NULL default ''"
        ],
        'population' => [
            'label' => &$GLOBALS['TL_LANG']['eonm_intern']['population'],
            'exclude' => true,
            'search' => true,
            'sorting' => true,
            'flag' => 1,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 510],
            'sql' => "varchar(510) NOT NULL default ''"
        ],
        'smoker' => [
            'label' => &$GLOBALS['TL_LANG']['eonm_intern']['smoker'],
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
            'label'                   => &$GLOBALS['TL_LANG']['eonm_intern']['status'],
            'exclude'                 => true,
            'filter'                  => true,
            'sorting'                 => true,
            'inputType' => 'text',
            //'inputType'               => 'select',
            //'options'                 => [1=>'aktiv', 2=>'storniert'],
            //'default'                 => 1,
            //'foreignKey'            => 'tl_user.name',
            //'options_callback'      => array('CLASS', 'METHOD'),
            'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50', 'disabled' => true, 'style' => 'border: 0; background: white; color: #222; padding-left: 0;'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'patientdata' => [
            'label' => &$GLOBALS['TL_LANG']['eonm_intern']['patientdata'],
            'exclude' => true,
            'search' => true,
            'sorting' => true,
            'flag' => 1,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 510],
            'sql' => "varchar(510) NOT NULL default ''"
        ],
        'femaledata' => [
            'label' => &$GLOBALS['TL_LANG']['eonm_intern']['femaledata'],
            'exclude' => true,
            'search' => true,
            'sorting' => true,
            'flag' => 1,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 510],
            'sql' => "varchar(510) NOT NULL default ''"
        ],
    )
);


/**
 * Class tl_article_workflow_backend
 *
 */
class  eonm_intern_cancel_backend extends Backend
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
    public function cancelButtonRegistration($arrRow)
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
        if($arrRow['status'] == 'aktiv') {
            return '<a onclick="if(!confirm(\'Soll diese Anmeldung storniert werden?\'))return false;Backend.getScrollOffset()" href="' . $this->addToUrl('do=NuvisanManageIntern&key=storno&time_id='.$arrRow['timeid'].'&registration_id='.$arrRow['id'], true, ['do']) . '" title="stornieren" style="display: inline-block; padding: 4px 6px; font-weight:bold; color: white; background: green">stornieren</a>';
        } else {
            return '<span style="cursor: no-drop; display: inline-block; padding: 4px 6px; font-weight:bold; color: white; background: #c42302">'.$arrRow['status'].'</span>';
        }

    }

    public function startButtonStudy($arrRow)
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
        if($arrRow['status'] == 'aktiv') {
            return '<a onclick="if(!confirm(\'Datenübernahme/Checkin für diese Anmeldung?\'))return false;Backend.getScrollOffset()" href="' . $this->addToUrl('do=NuvisanManageIntern&key=study&time_id='.$arrRow['timeid'].'&registration_id='.$arrRow['id'], true, ['do']) . '" title="Datenübernahme/Checkin" style="display: inline-block; padding: 4px 6px; font-weight:bold; color: white; background: green; margin-left: 5px;">Datenübernahme/Checkin</a>';
        } else {
            return '';
        }

    }

    public function internRegisterCallback(DataContainer $dc)
    {

        $registration = \Contao\System::getContainer()->get('sioweb_dummybundle.service.search')->getInternRegistrationById($_GET['id']);
        $return = array();


        if($registration->getStatus() != 'aktiv') {
            $dates = \Contao\System::getContainer()->get('sioweb_dummybundle.service.search')->findAllTimesArray();

            foreach($dates as $date){
                $return[$date['id']] = $date['date']. ' - ' . $date['time'];
            }
        } else {
            $return[$registration->getTimeid()] = $registration->getRegisterdate();
        }


        return $return;

    }


}
