<?php

namespace Sioweb\DummyBundle;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Sioweb\DummyBundle\DependencyInjection\Extension;

/**
 * @author Sascha Weidner <http://www.sioweb.de>
 */
class SiowebDummyBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        return new Extension();
    }

    public function exportRegistration(\Contao\DC_Table $dc)
    {


        $exportData = array();

        $registrationData = \Contao\System::getContainer()->get('sioweb_dummybundle.service.search')->getRegistrationTable();

        $data = array();

        foreach ($registrationData as $searchData ) {

            //$data = array('Suchausdruck' => utf8_decode($searchData['0']->getKeyword()), 'so oft gesucht' => $searchData['1'], 'Treffer' => $searchData['0']->getResults() );

#var_dump($searchData);die;

            $femaledataExport = '';
            $femaledata = unserialize(stream_get_contents($searchData->getFemaledata()));

            if($femaledata != NULL ) {
                $countFemale = count($femaledata);
                foreach ($femaledata as $female) {
                    $femaledataExport .= utf8_decode($female).', ';
                }
            }



            $patientdataExport = '';
            $patientdata = unserialize(stream_get_contents($searchData->getPatientdata()));
            if($patientdata != NULL ) {
                $countPatient = count($patientdata);
                foreach ($patientdata as $patient) {
                    $patientdataExport .= utf8_decode($patient).', ';
                }
            }



            $data = array(
                'Gruppe' => utf8_decode($searchData->getRegistergroup()),
                'Anrede' => utf8_decode($searchData->getTitle()),
                'Vorname' => utf8_decode($searchData->getFirstname()),
                'Nachname' => utf8_decode($searchData->getLastname()),
                'E-Mail' => utf8_decode($searchData->getEmail()),
                'Telefon' => utf8_decode($searchData->getPhone()),
                'Geburtstag' => utf8_decode($searchData->getBirthday()),
                'Strasse' => utf8_decode($searchData->getStreet()),
                'PLZ' => utf8_decode($searchData->getZip()),
                'Stadt' => utf8_decode($searchData->getCity()),
                'Buchungsdatum' => utf8_decode($searchData->getRegisterdate()),
                'Studiencode' => utf8_decode($searchData->getStudy()),
                'Patientendaten' => $patientdataExport,
                utf8_decode('Verhütungsdaten') => $femaledataExport,
                'Registrierungsdatum' => utf8_decode($searchData->getTstamp()),
            );

            #foreach ($searchData[0] as $datas ) {
            #var_dump($datas->getKeyword());
            #    $data = array('Suchausdruck' => utf8_decode($datas->getKeyword()), 'so oft gesucht' => $datas->getQuantity(), 'Treffer' => $datas->getResults() );
            #}
            #$data = array('Suchausdruck' => utf8_decode($searchData['keyword']), 'so oft gesucht' => $searchData['quantity'], 'Treffer' => $searchData['results'] );

            #exportData[]['id'] = $searchData->getId();
            #$exportData[]['searchdate'] = date('d.m.Y. h:i:s' ,$searchData->getLastsearch());
            #$exportData[]['keyword'] = $searchData->getKeyword();
            #$exportData[]['quantity'] = $searchData->getQuantity();
            #$exportData[]['results'] = $searchData->getResults();

            array_push($exportData,$data);
        }
        #$data = array(
        #    array( 'item' => 'Server', 'cost' => 10000, 'approved by' => 'Joe'),
        #    array( 'item' => 'Mt Dew', 'cost' => 1.25, 'approved by' => 'John'),
        #    array( 'item' => 'IntelliJ IDEA', 'cost' => 500, 'approved by' => 'James')
        #);

        \System::getContainer()->get('sioweb_dummybundle.service.search')->outputCsv('export-registrations-'.time().'.csv', $exportData);


        //die('ddd');
        // Objekt des Mitglieds laden
        #$objMember = \Contao\MemberModel::findByPk($dc->id);
        // Login verbieten
        #$objMember->login = '';     // Nicht false oder 0, sonst werden die Felder Benutzername und Passwort weiterhin angezeigt!
        // Änderung speichern
        #$objMember->save();
        // Eine Meldung im Backend erzeugen
        #\Contao\Message::addInfo('<span style="padding: 20px 5px; display: inline-block;">Export wird runtergeladen...</span>');
        \Contao\Controller::redirect('contao/main.php?do=NuvisanManageRegistration');

    }

    public function makeStorno(\Contao\DC_Table $dc)
    {
        $timeid = \Input::get('time_id');
        $registrationid = \Input::get('registration_id');

        \Contao\System::getContainer()->get('sioweb_dummybundle.service.search')->addTimeCount($timeid, $registrationid);
        \Contao\Message::addInfo('<span style="padding: 20px 5px; display: inline-block;">Die Registration wurde storniert!</span>');
        \Contao\Controller::redirect('contao/main.php?do=NuvisanManageRegistration');
    }
}