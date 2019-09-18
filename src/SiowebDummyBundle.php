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


        /*
         * Sending Mail
         */
        $registration = \Contao\System::getContainer()->get('sioweb_dummybundle.service.search')->getRegistrationById($registrationid);
        $timedata = \Contao\System::getContainer()->get('sioweb_dummybundle.service.search')->findAllTimeById($timeid);
        $tage = array("Sonntag", "Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag");
        $dateobj = \Contao\System::getContainer()->get('sioweb_dummybundle.service.search')->getDateById($timedata->getDateid());
        $date = $dateobj->getDate();
        $date = $date->format('d.m.Y');
        $week = date('w',strtotime($date));
        $time = $timedata->getTime();
        $day = $tage[$week];

        $timeasstring =  $day.', den '.$date.' um '.$time.' Uhr';

        /*
         * get transport class / set smtp config
         */
        $transport = \Swift_SmtpTransport::newInstance( 'w00a0565.kasserver.com', 587 );
        $transport->setUsername( 'm042205d' );
        $transport->setPassword( 'qBxxf6YENJWXtb38' );

        /*
         * get message class / set message and set mail header
         */
        $message = \Swift_Message::newInstance();
        $message->setFrom('studienanmeldung@nuvisan.eu');
        $message->setTo($registration->getEmail(), $registration->getFirstname().' '.$registration->getLastname());
        #$message->setBcc('studinfo@nuvisan.com', 'Studienanmeldung');
        //$message->addBcc('studinfo@nuvisan.com', 'Studienanmeldung');
        $message->addBcc('aydin@eonm.de', 'Studienanmeldung');
        #$message->addBcc('aydin@eonm.de', 'eyes on media');
        #$message->addBcc('rothenfusser@freshframes.com', 'fresh frames');
        //$message->addBcc('nuvisan@freshframesmedia.com', 'fresh frames');
        $message->setSubject('NUVISAN / Terminstornierung');
        $message->setBody('<h1>Ihre Terminstornierung</h1><h3>Studie: N-A-PH1-19-020</h3><p>Sehr geehrte Studieninteressentin, sehr geehrter Studieninteressent,</p><p>leider haben Sie Ihren Termin zur Blutabnahme telefonisch storniert.</p><p>Hiermit bestätigen wir folgende Terminstornierung:<br><strong>'.$timeasstring.'</strong></p><p>Wir würden uns freuen, wenn Sie zu einem anderen Zeitpunkt bei unserer Blutspende teilnehmen könnten. Alle aktuellen Termine finden Sie unter: www.nuvisan.de</p>Für Rückfragen stehen wir Ihnen gern und jederzeit zur Verfügung.</p><p><strong>Studieninfo Hotline: 0800 0788 343 (gebührenfrei).</strong></p><p>Wir freuen uns darauf Sie bei Nuvisan begrüßen zu dürfen!</p><p>Mit freundlichen Grüßen,</p><p>Ihre Studieninformation</p><p>___________________________________________________________________________________</p><p>Nuvisan GmbH<br>Wegenerstrasse 13<br>89231 Neu-Ulm<br>Germany</p><p>Telefon:<br>0800  0788 343 (gebührenfrei)<br>+49 731 9840 222</p><p>Fax:<br>+49 731 9840 280</p><p>E-Mail:<br>studieninfo.neu-ulm@nuvisan.de</p><p>WEB:<br>www.nuvisan.de (Direktlink zum Studienangebot)<br>www.nuvisan.com (englische Seite)</p><p>Registered Office Neu-Ulm • District Court Memmingen HRB 14249 • VAT-No: DE271570059<br>Geschäftsführer: Dr. Dietrich Bruchmann</p><p>___________________________________________________________________________________</p><p>Confidentiality Notice: This e-mail transmission may contain confidential or legally privileged information that is intended only for the individual or entity named in the e-mail address. If you are not the intended recipient, you are hereby notified that any disclosure, copying, distribution, or reliance upon the contents of this e-mail is strictly prohibited.<br>If you have received this e-mail transmission in error, please reply to the sender, so that we can arrange for proper delivery, and then please delete the message from your inbox. Thank you.</p>', 'text/html');

        /*
         * get mailer class / send mail
         */
        $mailer = \Swift_Mailer::newInstance( $transport );

        if (!$mailer->send($message) ) {
            die('Fehler! Anfrage konnte nicht verarbeitet werden.');
        }


        \Contao\Message::addInfo('<span style="padding: 20px 5px; display: inline-block;">Die Registration wurde storniert!</span>');
        \Contao\Controller::redirect('contao/main.php?do=NuvisanManageRegistration');
    }

    public function makeStornoIntern(\Contao\DC_Table $dc)
    {

        $timeid = \Input::get('time_id');
        $registrationid = \Input::get('registration_id');

        \Contao\System::getContainer()->get('sioweb_dummybundle.service.search')->addTimeCountIntern($timeid, $registrationid);


        /*
         * Sending Mail
         */
        $registration = \Contao\System::getContainer()->get('sioweb_dummybundle.service.search')->getInternRegistrationById($registrationid);
        $timedata = \Contao\System::getContainer()->get('sioweb_dummybundle.service.search')->findAllTimeById($timeid);
        $tage = array("Sonntag", "Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag");
        $dateobj = \Contao\System::getContainer()->get('sioweb_dummybundle.service.search')->getDateById($timedata->getDateid());
        $date = $dateobj->getDate();
        $date = $date->format('d.m.Y');
        $week = date('w',strtotime($date));
        $time = $timedata->getTime();
        $day = $tage[$week];

        $timeasstring =  $day.', den '.$date.' um '.$time.' Uhr';

        /*
         * get transport class / set smtp config
         */
        $transport = \Swift_SmtpTransport::newInstance( 'w00a0565.kasserver.com', 587 );
        $transport->setUsername( 'm042205d' );
        $transport->setPassword( 'qBxxf6YENJWXtb38' );

        /*
         * get message class / set message and set mail header
         */
        $message = \Swift_Message::newInstance();
        $message->setFrom('studienanmeldung@nuvisan.eu');
        $message->setTo($registration->getEmail(), $registration->getFirstname().' '.$registration->getLastname());
        #$message->setBcc('studinfo@nuvisan.com', 'Studienanmeldung');
        //$message->addBcc('studinfo@nuvisan.com', 'Studienanmeldung');
        $message->addBcc('aydin@eonm.de', 'Studienanmeldung');
        #$message->addBcc('aydin@eonm.de', 'eyes on media');
        #$message->addBcc('rothenfusser@freshframes.com', 'fresh frames');
        //$message->addBcc('nuvisan@freshframesmedia.com', 'fresh frames');
        $message->setSubject('NUVISAN / Terminstornierung');
        $message->setBody('<h1>Ihre Terminstornierung</h1><h3>Studie: N-A-PH1-19-020</h3><p>Sehr geehrte Kollegin, sehr geehrter Kollege,</p><p>leider haben Sie Ihren Termin zur Blutabnahme storniert.</p><p>Hiermit bestätigen wir folgende Terminstornierung:<br><strong>'.$timeasstring.'</strong></p><p>Wir würden uns freuen, wenn Sie zu einem anderen Zeitpunkt bei unserer Blutspende teilnehmen könnten. Alle aktuellen Termine finden Sie unter: www.nuvisan.de</p>Für Rückfragen stehen wir Ihnen gern und jederzeit zur Verfügung.</p><p><strong>Studieninfo Hotline: 0800 0788 343 (gebührenfrei).</strong></p><p>Wir freuen uns darauf Sie bei Nuvisan begrüßen zu dürfen!</p><p>Mit freundlichen Grüßen,</p><p>Ihre Studieninformation</p><p>___________________________________________________________________________________</p><p>Nuvisan GmbH<br>Wegenerstrasse 13<br>89231 Neu-Ulm<br>Germany</p><p>Telefon:<br>0800  0788 343 (gebührenfrei)<br>+49 731 9840 222</p><p>Fax:<br>+49 731 9840 280</p><p>E-Mail:<br>studieninfo.neu-ulm@nuvisan.de</p><p>WEB:<br>www.nuvisan.de (Direktlink zum Studienangebot)<br>www.nuvisan.com (englische Seite)</p><p>Registered Office Neu-Ulm • District Court Memmingen HRB 14249 • VAT-No: DE271570059<br>Geschäftsführer: Dr. Dietrich Bruchmann</p><p>___________________________________________________________________________________</p><p>Confidentiality Notice: This e-mail transmission may contain confidential or legally privileged information that is intended only for the individual or entity named in the e-mail address. If you are not the intended recipient, you are hereby notified that any disclosure, copying, distribution, or reliance upon the contents of this e-mail is strictly prohibited.<br>If you have received this e-mail transmission in error, please reply to the sender, so that we can arrange for proper delivery, and then please delete the message from your inbox. Thank you.</p>', 'text/html');

        /*
         * get mailer class / send mail
         */
        $mailer = \Swift_Mailer::newInstance( $transport );

        if (!$mailer->send($message) ) {
            die('Fehler! Anfrage konnte nicht verarbeitet werden.');
        }


        \Contao\Message::addInfo('<span style="padding: 20px 5px; display: inline-block;">Die Registration wurde storniert!</span>');
        \Contao\Controller::redirect('contao/main.php?do=NuvisanManageIntern');
    }
}