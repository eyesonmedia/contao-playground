<?php

namespace Sioweb\DummyBundle\Service;

use Doctrine\ORM\EntityManager;
use Sioweb\DummyBundle\Entity\Intern;
use Sioweb\DummyBundle\Entity\Registration;
use Sioweb\DummyBundle\Entity\Study;

class Search
{

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * WorkflowService constructor.
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Find all workflow
     *
     * @return array|\Freshframes\WorkflowBundle\Entity\Workflow[]
     */
    public function findAll()
    {
        $workflowList = $this->entityManager->getRepository('SiowebDummyBundle:Test')->findAll();

        return $workflowList;
    }

	public function getResult() {
		return [
			'lorem','ipsum','dolor'
		];
	}

    /**
     * Find all dates for booking
     *
     * @return array|\Freshframes\WorkflowBundle\Entity\Workflow[]
     */
    public function findAllDates()
    {
        $workflowList = $this->entityManager
            ->getRepository('SiowebDummyBundle:Test')
            ->findBy(array(), array('date' => 'DESC'));
        #$workflowList = $this->entityManager->getRepository('SiowebDummyBundle:Test')->findAll();

        return $workflowList;
    }

    /**
     * Find all dates for booking
     *
     * @return array|\Freshframes\WorkflowBundle\Entity\Workflow[]
     */
    public function findAllTimesArray()
    {

        $times = $this->entityManager
            ->createQueryBuilder()
            ->select('t, d.date')
            ->from('SiowebDummyBundle:Test', 'd')
            ->leftJoin('SiowebDummyBundle:Time', 't', 'WITH', 'd.id = t.dateid')
            ->where('d.id LIKE t.dateid')
            ->andWhere('t.count != 0')
            ->orderBy('t.time', 'ASC')
            ->getQuery()
            ->getResult();


        $avaibledates = array();

        $i = 0;
        foreach ($times as $time ) {
            $avaibledates[$i]['date'] = $time['date']->format('d.m.Y');
            $avaibledates[$i]['time'] = $time[0]->getTime();
            $avaibledates[$i]['id'] = $time[0]->getId();
            $i++;
        }

        #return $avaibledates;

        return $avaibledates;
    }

    public function setInternDate($varValue)
    {

        if($varValue != 'NULL') {
            $timeid = $varValue;

            $time = $this->updateInternRegistation($_GET['id'], $timeid);

            $this->reduceTimeCount($timeid);

            return $time;

        } else {
            \Contao\Message::addError('<span style="padding: 20px 5px; display: inline-block;">Die Anmeldung ist bereits aktiv und kann nur storniert werden.</span>');
        }

    }


    /**
     * Find all dates for booking
     *
     * @return array|\Freshframes\WorkflowBundle\Entity\Workflow[]
     */
    public function findAllTimes()
    {

        $times = $this->entityManager
            ->createQueryBuilder()
            ->select('t, d.date, d.published')
            ->from('SiowebDummyBundle:Test', 'd')
            ->leftJoin('SiowebDummyBundle:Time', 't', 'WITH', 'd.id = t.dateid')
            ->where('d.id LIKE t.dateid')
            ->andWhere('t.count != 0')
            ->andWhere('d.published = 1')
            ->orderBy('t.time', 'ASC')
            ->getQuery()
            ->getResult();


        $avaibledates = array();

        #var_dump($times);die;

        $i = 0;
        foreach ($times as $time ) {
            $avaibledates[$time['date']->format('d.m.Y')][$i]['time'] = $time[0]->getTime();
            $avaibledates[$time['date']->format('d.m.Y')][$i]['id'] = $time[0]->getId();
            $i++;
        }

        #return $avaibledates;

        return json_encode($avaibledates);
    }

    /**
     * Find all workflow
     *
     * @return array|\Freshframes\WorkflowBundle\Entity\Workflow[]
     */
    public function findTimeById($id)
    {
        $data = $this->entityManager->getRepository('SiowebDummyBundle:Time')->find($id);

        return $data->getTime();
    }


    public function getInternRegistrationById($id) {
        $data = $this->entityManager->getRepository('SiowebDummyBundle:Intern')->find($id);

        return $data;
    }

    /**
     * Find all workflow
     *
     * @return array|\Freshframes\WorkflowBundle\Entity\Workflow[]
     */
    public function findAllTimeById($id)
    {
        $data = $this->entityManager->getRepository('SiowebDummyBundle:Time')->find($id);

        return $data;
    }

    /**
     * Find all workflow
     *
     * @return array|\Freshframes\WorkflowBundle\Entity\Workflow[]
     */
    public function getDateById($id)
    {
        $data = $this->entityManager->getRepository('SiowebDummyBundle:Test')->find($id);

        return $data;
    }

    public function addTimeCount($id, $registrationid)
    {

        $time = $this->entityManager->getRepository('SiowebDummyBundle:Time')->find($id);

        if (!$time) {
            throw $this->createNotFoundException(
                'No Time found for id '.$id
            );
        }

        $count = $time->getCount();
        if ( $count < 10 ) {

            $time->setCount($count+1);
            $this->entityManager->flush();

            $registration = $this->entityManager->getRepository('SiowebDummyBundle:Registration')->find($registrationid);
            $registration->setStatus('storniert');
            $this->entityManager->flush();


        } else {
            \Contao\Message::addError('<span style="padding: 20px 5px; display: inline-block;">Die Registration konnte nicht storniert werden! Maximale Termine vorhanden.</span>');
            \Contao\Controller::redirect('contao/main.php?do=NuvisanManageRegistration');
        }
    }

    public function addTimeCountIntern($id, $registrationid)
    {

        $time = $this->entityManager->getRepository('SiowebDummyBundle:Time')->find($id);

        if (!$time) {
            throw $this->createNotFoundException(
                'No Time found for id '.$id
            );
        }

        $count = $time->getCount();
        if ( $count < 10 ) {

            $time->setCount($count+1);
            $this->entityManager->flush();

            $registration = $this->entityManager->getRepository('SiowebDummyBundle:Intern')->find($registrationid);
            $registration->setStatus('storniert');
            $this->entityManager->flush();


        } else {
            \Contao\Message::addError('<span style="padding: 20px 5px; display: inline-block;">Die Registration konnte nicht storniert werden! Maximale Termine vorhanden.</span>');
            \Contao\Controller::redirect('contao/main.php?do=NuvisanManageIntern');
        }
    }

    public function reduceTimeCount($id)
    {
        $time = $this->entityManager->getRepository('SiowebDummyBundle:Time')->find($id);

        if (!$time) {
            throw $this->createNotFoundException(
                'No Time found for id '.$id
            );
        }

        $count = $time->getCount();
        if ($count != 0) {
            $time->setCount($count-1);
            $this->entityManager->flush();
        } else {
            die('Fehler! Anfrage konnte nicht verarbeitet werden. Time 0');
        }
    }

    public function setRegistation($data, $definedtime, $timeid, $female, $patient)
    {
        #$registration = $this->entityManager->getRepository('SiowebDummyBundle:Registration');

        //tstamp, timeid, group, title, firstname, lastname, email, phone, street, zip, city, birthday, registerdate, study, patientdata

        /*
         * prepare registerdata
         */
        $tage = array("Sonntag", "Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag");
        $date = $data['booking']['day'];
        $week = date('w',strtotime($date));
        $time = $data['booking']['time'];
        $day = $tage[$week];

        $registration = new Registration();
        //$registration->setTstamp('');
        $registration->setTimeid($timeid);
        $registration->setRegistergroup($data['study-group']);
        $registration->setTitle($data['anrede']);
        $registration->setFirstname($data['first-name']);
        $registration->setLastname($data['last-name']);
        $registration->setEmail($data['email']);
        $registration->setPhone($data['phone']);
        $registration->setStreet($data['street']);
        $registration->setZip($data['postal-code']);
        $registration->setCity($data['city']);
        $registration->setBirthday($data['birthday']['day'].'.'.$data['birthday']['month'].'.'.$data['birthday']['year']);
        $registration->setRegisterdate($definedtime.' Uhr');
        $registration->setStudy($data['study-title']);
        $registration->setPatientdata(serialize($patient));
        $registration->setFemaledata(serialize($female));
        $registration->setPopulation($data['state']);
        $registration->setSmoker($data['smoker']);
        $registration->setStatus('aktiv');

        $this->entityManager->persist($registration);
        $this->entityManager->flush();
        //$this->entityManager->clear()

        #$this->entityManager
        #    ->createQueryBuilder()
        #    ->insert('eonm_registration')
        #    ->setValue('firstname', '?')
        #   ->setParameter(0, $data['first-name']);



        #$registration->setFistname($data['first-name']);
        #$this->entityManager->flush();
    }

    public function updateInternRegistation($id, $timeid)
    {

        $timedata = $this->findAllTimeById($timeid);
        $tage = array("Sonntag", "Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag");
        $dateobj = $this->getDateById($timedata->getDateid());
        $date = $dateobj->getDate();
        $date = $date->format('d.m.Y');
        $week = date('w',strtotime($date));
        $time = $timedata->getTime();
        $day = $tage[$week];

        $timeasstring =  $day.', den '.$date.' um '.$time.' Uhr';

        $registration = $this->entityManager->getRepository('SiowebDummyBundle:Intern')->find($id);

        $registration->setStatus('aktiv');

        //$registration->setRegisterdate($timeasstring);
        $registration->setTimeid($timeid);

        $this->entityManager->flush();

        /*
         * Sending Mail
         */


        /*
         * autoload swift classes
         */

        /*
         * get transport class / set smtp config
         */
        $transport = \Swift_SmtpTransport::newInstance( 'w00a0565.kasserver.com', 587 );
        $transport->setUsername( 'm042205d' );
        $transport->setPassword( 'UBqaImtNxpgVJ9gavxzI4G421G' );

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
        $message->setSubject('NUVISAN / Terminbestätigung zur Blutspende');
        $message->setBody('<h1>Ihr Termin zur Blutspende</h1><h3>Studie: N-A-PH1-19-020</h3><p>Sehr geehrte Kollegin, sehr geehrter Kollege,</p><p>wir freuen uns Ihnen hiermit den Termin zur Blutspende bestätigen zu können.</p><p>Ihr Blutspende-Termin ist am:<br><strong>'.$timeasstring.'</strong></p><p>Bitte haben Sie Verständnis, dass wir auf Grund des engen Zeitplans Ihre Buchung als verbindlich ansehen. Wir erwarten Sie pünktlich zu Ihrem Termin.</p><p>Nach dem Telefonat erhalten Sie eine Terminbestätigung per Email – diese ist dann verbindlich.</p><h2>Wichtig:</h2><p>Sollten Sie kurzfristig verhindert sein und Ihren Termin stornieren müssen, so melden Sie sich bitte umgehend bei der Studieninformation.</p><p><strong>Studieninfo Hotline: +49 731 9840 222</strong></p><p>Wir freuen uns darauf Sie bei Nuvisan begrüßen zu dürfen!</p><p>Mit freundlichen Grüßen,</p><p>Ihre Studieninformation</p><p>___________________________________________________________________________________</p><p>Nuvisan GmbH<br>Wegenerstrasse 13<br>89231 Neu-Ulm<br>Germany</p><p>Telefon:<br>0800  0788 343 (gebührenfrei)<br>+49 731 9840 222</p><p>Fax:<br>+49 731 9840 280</p><p>E-Mail:<br>studieninfo.neu-ulm@nuvisan.de</p><p>WEB:<br>www.nuvisan.de (Direktlink zum Studienangebot)<br>www.nuvisan.com (englische Seite)</p><p>Registered Office Neu-Ulm • District Court Memmingen HRB 14249 • VAT-No: DE271570059<br>Geschäftsführer: Dr. Dietrich Bruchmann</p><p>___________________________________________________________________________________</p><p>Confidentiality Notice: This e-mail transmission may contain confidential or legally privileged information that is intended only for the individual or entity named in the e-mail address. If you are not the intended recipient, you are hereby notified that any disclosure, copying, distribution, or reliance upon the contents of this e-mail is strictly prohibited.<br>If you have received this e-mail transmission in error, please reply to the sender, so that we can arrange for proper delivery, and then please delete the message from your inbox. Thank you.</p>', 'text/html');

        /*
         * get mailer class / send mail
         */
        $mailer = \Swift_Mailer::newInstance( $transport );

        if (!$mailer->send($message) ) {
            die('Fehler! Anfrage konnte nicht verarbeitet werden.');
        }

        return $timeasstring;


    }

    public function setInternRegistation($data, $female, $patient)
    {
        #$registration = $this->entityManager->getRepository('SiowebDummyBundle:Registration');

        //tstamp, timeid, group, title, firstname, lastname, email, phone, street, zip, city, birthday, registerdate, study, patientdata

        /*
         * prepare registerdata
         */
        $tage = array("Sonntag", "Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag");
        $date = $data['booking']['day'];
        $week = date('w',strtotime($date));
        $time = $data['booking']['time'];
        $day = $tage[$week];

        $registration = new Intern();
        //$registration->setTstamp('');
        $registration->setTimeid(0);
        $registration->setRegistergroup($data['study-group']);
        $registration->setTitle($data['anrede']);
        $registration->setFirstname($data['first-name']);
        $registration->setLastname($data['last-name']);
        $registration->setEmail($data['email']);
        $registration->setPhone($data['phone']);
        $registration->setStreet($data['street']);
        $registration->setZip($data['postal-code']);
        $registration->setCity($data['city']);
        $registration->setBirthday($data['birthday']['day'].'.'.$data['birthday']['month'].'.'.$data['birthday']['year']);
        $registration->setRegisterdate('');
        $registration->setStudy($data['study-title']);
        $registration->setPatientdata(serialize($patient));
        $registration->setFemaledata(serialize($female));
        $registration->setPopulation($data['state']);
        $registration->setSmoker($data['smoker']);
        $registration->setStatus('inaktiv');

        $this->entityManager->persist($registration);
        $this->entityManager->flush();
        //$this->entityManager->clear()

        #$this->entityManager
        #    ->createQueryBuilder()
        #    ->insert('eonm_registration')
        #    ->setValue('firstname', '?')
        #   ->setParameter(0, $data['first-name']);



        #$registration->setFistname($data['first-name']);
        #$this->entityManager->flush();
    }

    public function getRegistrationTable() {
        $data = $this->entityManager->getRepository('SiowebDummyBundle:Registration')->findAll();
        return $data;
    }

    public function getInternRegistrationTable() {
        $data = $this->entityManager->getRepository('SiowebDummyBundle:Intern')->findAll();
        return $data;
    }

    public function getRegistrationById($id) {
        $data = $this->entityManager->getRepository('SiowebDummyBundle:Registration')->find($id);
        return $data;
    }

    public function outputCsv($fileName, $assocDataArray)
    {
        ob_clean();
        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: private', false);
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename=' . $fileName);
        if(isset($assocDataArray['0'])){
            $fp = fopen('php://output', 'w');
            fputcsv($fp, array_keys($assocDataArray['0']), ';');
            foreach($assocDataArray AS $values){
                fputcsv($fp, $values, ';');
            }
            fclose($fp);
        }
        ob_flush();
    }


    public function entryExists($lastname, $email, $birthday) {

        $lastname = strtolower($lastname);
        $email = strtolower($email);
        $birthday = strtolower($birthday);


        $check = $this->entityManager
            ->createQueryBuilder()
            ->select('r.lastname, r.email, r.birthday, r.status')
            ->from('SiowebDummyBundle:Registration', 'r')
            ->where('LOWER(r.lastname) LIKE :name')
            ->andWhere('LOWER(r.email) LIKE :email')
            ->andWhere('LOWER(r.birthday) LIKE :birthday')
            ->setParameter('name', $lastname)
            ->setParameter('email', $email)
            ->setParameter('birthday', $birthday)
            ->getQuery()
            ->getResult();

        if($check['0']['status'] == 'storniert') {
            //registrierung vorhanden aber storniert
            return false;
        } elseif($check) {
            //registrierung vorhanden
            return true;
        } else {
            //registrierung nicht vorhanden
            return false;
        }

    }

    public function entryExistsIntern($lastname, $email, $birthday) {



        $lastname = strtolower($lastname);
        $email = strtolower($email);
        $birthday = strtolower($birthday);

        $check = $this->entityManager
            ->createQueryBuilder()
            ->select('r.lastname, r.email, r.birthday, r.status')
            ->from('SiowebDummyBundle:Intern', 'r')
            ->where('LOWER(r.lastname) LIKE :name')
            ->andWhere('LOWER(r.email) LIKE :email')
            ->andWhere('LOWER(r.birthday) LIKE :birthday')
            ->setParameter('name', $lastname)
            ->setParameter('email', $email)
            ->setParameter('birthday', $birthday)
            ->getQuery()
            ->getResult();


        if($check['0']['status'] == 'storniert') {
            //registrierung vorhanden aber storniert
            return false;
        } elseif($check) {
            //registrierung vorhanden
            return true;
        } else {
            //registrierung nicht vorhanden
            return false;
        }

    }

    public function validateAge($birthday, $age = 18)
    {
        // $birthday can be UNIX_TIMESTAMP or just a string-date.
        if(is_string($birthday)) {
            $birthday = strtotime($birthday);
        }

        // check
        // 31536000 is the number of seconds in a 365 days year.
        if(time() - $birthday < $age * 31536000)  {
            return false;
        }

        return true;
    }


    public function setRegistrationCheckin($id) {
        /*
         * update registration status to checkin first
         */
        $registration = $this->entityManager->getRepository('SiowebDummyBundle:Registration')->find($id);
        $registration->setStatus('checked-in');
        $this->entityManager->flush();
    }

    public function generateStudy($id, $date, $timedata) {
        $registrationdata =\Contao\System::getContainer()->get('sioweb_dummybundle.service.search')->getRegistrationById($id);
        $study = new Study();
        $study->setTimeid($registrationdata->getTimeid());
        $study->setRegistrationid($registrationdata->getId());
        $study->setRegistergroup($registrationdata->getRegistergroup());
        if($registrationdata->getTitle() == 'Herr') {
            $study->setTitle('männlich');
        } else {
            $study->setTitle('weiblich');
        }
        $study->setFirstname($registrationdata->getFirstname());
        $study->setLastname($registrationdata->getLastname());
        $study->setEmail($registrationdata->getEmail());
        $study->setPhone($registrationdata->getPhone());
        $study->setStreet($registrationdata->getStreet());
        $study->setZip($registrationdata->getZip());
        $study->setCity($registrationdata->getCity());
        $study->setBirthday($registrationdata->getBirthday());
        $study->setRegisterdate($date);
        $study->setRegistertime($timedata);
        $study->setStudy($registrationdata->getStudy());
        $study->setStatus('offen');

        $this->entityManager->persist($study);
        $this->entityManager->flush();
    }

    public function getStudyById($id)
    {
        $study = $this->entityManager
            ->createQueryBuilder()
            ->select('s')
            ->from('SiowebDummyBundle:Study', 's')
            ->where('s.id LIKE :registrationid')
            ->setParameter('registrationid', $id)
            ->getQuery()
            ->getResult();

        return $study['0'];
    }


    public function outputHTMLCheckin($data)
    {
        #var_dump($data->getBirthday());die;

        $birthdate = new \DateTime($data->getBirthday());
        $slotdate = new \DateTime($data->getRegisterdate().' '.$data->getRegistertime());
        $age = date_diff(date_create($data->getBirthday()), date_create('today'))->y;

        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: private', false);
        header('Content-Type: application/pdf');
        header('content-Disposition:attachment;filename="downloaded.pdf"');

        $pdf = new \TCPDF( 'P', 'mm', 'A4' );
        $pdf->SetMargins(22, 15, 8, true);
        $pdf->AddPage ( 'P' );

        $bMargin = $pdf->getBreakMargin();
        $auto_page_break = $pdf->getAutoPageBreak();
        $pdf->SetAutoPageBreak(false, 0);
        $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
        $pdf->setPageMark();
        $pdf->SetFontSize(10);

$html='<header>
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
<td width="70%">&nbsp;</td>
<td width="30%"><img src="https://nuvisan.de/themes/nuvisan/img/nuvisan-logo-static.png" style="width: 200px" height="auto">
</td>
</tr>
</table>
</header>
<section>
<h1>ANMELDEFORMULAR FÜR STUDIENINTERESSENTEN</h1>
<p>&nbsp;</p>
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
<td width="60%">
<p>Probanden-NR.:<br><small>(wird vom Mitarbeiter der Studieninformation ausgefüllt)
</small></p>
</td>
<td width="40%">____________________________</td>
</tr>
</table>
<p>&nbsp;</p>
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
<td width="40%">
<p><strong>Familienname</strong></p>
</td>
<td width="60%">'.$data->getLastname().'</td>
</tr>
<tr>
<td width="40%">
<p><strong>Vorname(n):</strong></p>
</td>
<td width="60%">'.$data->getFirstname().'</td>
</tr>
<tr>
<td width="40%">
<p><strong>Geburtsdatum:</strong></p>
</td>
<td width="60%">'.$data->getBirthday().'</td>
</tr>
<tr>
<td width="40%">
<p><strong>Geschlecht:</strong></p>
</td>
<td width="60%">'.$data->getTitle().'</td>
</tr>
<tr>
<td width="40%">
<p><strong>Anschrift:</strong></p>
</td>
<td width="60%">'.$data->getStreet().'<br>'.$data->getZip().' '.$data->getCity().'</td>
</tr>
<tr>
<td width="40%">
<p><strong>E-Mail:</strong></p>
</td>
<td width="60%">'.$data->getEmail().'</td>
</tr>
</table>
<p>&nbsp;</p>
</section>
<section>
<p>Zustimmung laut Datenschutzgesetz:<br>Hiermit stimme ich zu, dass die hier angegebenen und die studienbezogenen Daten bei NUVISAN GmbH elektronisch gespeichert und verarbeitet werden. 
</p>
</section>
<section>
<p>&nbsp;</p>
<p>&nbsp;</p>
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
<td width="50%">&nbsp;</td>
<td width="50%">&nbsp;</td>
</tr>
<tr>
<td width="50%" style="border-top: 1px solid #000000; padding-top: 4px;"><p><strong>Neu-Ulm, den '. date("d.m.Y") .'</strong></p></td>
<td width="50%" style="border-top: 1px solid #000000; padding-top: 4px;"><p><strong>Unterschrift Studieninteressent / in</strong></p></td>
</tr>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</section>
<section>
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
<td width="60%" valign="top"><p>Übertrag der Daten in Datenbank erfolgte von:</p></td>
<td width="40%" valign="top">____________________________<br><small>(Datum, Kürzel)</small></td>
</tr>
<tr>
<td width="60%" valign="top"><p>&nbsp;</p></td>
<td width="40%" valign="top"><p>&nbsp;</p></td>
</tr>
<tr>
<td width="60%" valign="top"><p>Review Übertrag in Datenbank <small>(festangestellter Recruiter)</small>:</p></td>
<td width="40%" valign="top">____________________________<br><small>(Datum, Kürzel)</small></td>
</tr>
</table>
</section>
';
        $pdf->WriteHTML($html);

        $pdf->AddPage ( 'P' );
        $bMargin = $pdf->getBreakMargin();
        $auto_page_break = $pdf->getAutoPageBreak();
        $pdf->SetAutoPageBreak(false, 0);
        $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
        $pdf->setPageMark();
        $pdf->SetFontSize(10);


        $html='<header>
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
<td width="20%"><img src="https://nuvisan.de/themes/nuvisan/img/nuvisan-logo-static.png" style="width: 200px" height="auto"> </td>
<td width="60%" style="padding-left: 10px;"><p><small>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;NUVISAN Study Code: N-A-PH1-19-020<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sponsor Study Code: RD005065</small></p></td>
<td width="20%" style="text-align: right"><p><small>Page 1</small></p></td>
</tr>
</table>
<p>&nbsp;</p>
<table cellspacing="0" cellpadding="8" border="0" width="100%" style="border: 1px solid #000000;">
<tr>
<td colspan="3" style="text-align: center; background-color: lightskyblue; border-bottom: 1px solid #000000"><h4>Demographic Data</h4></td>
</tr>
<tr>
<td width="30%"><p><strong>Slot date:</strong></p></td>
<td colspan="2" width="70%"><p>'.$slotdate->format('d / m / Y').' <small>(DD/MMM/YYYY)</small></p></td>
</tr>
<tr>
<td width="30%"><p><strong>Slot Time:</strong></p></td>
<td colspan="2" width="70%"><p>'.$slotdate->format('H : i').' <small>(hh:min)</small></p></td>
</tr>
<tr>
<td width="30%"><p><strong>Birth date:</strong></p></td>
<td colspan="2" width="70%"><p>'.$birthdate->format('d / m / Y').' <small>(DD/MMM/YYYY)</small></p></td>
</tr>
<tr>
<td width="30%"><p><strong>Age:</strong></p></td>
<td colspan="2" width="70%"><p>'.$age.' <small>(if age below 18 - exclusion from study)</small></p></td>
</tr>
<tr>
<td width="70%"><p>Personal data were checked by verification of the identity card during check in-as well as a double registration to avoid multiple participations:</p></td>
<td width="5%"><p><input type="checkbox" name="agree" value="1" /></p></td>
<td width="25%"><p>Yes</p></td>
</tr>
<tr>
<td width="20%"></td>
<td width="50%" style="text-align: right;"><p>Initials: </p></td>
<td width="30%"><p>___________________</p></td>
</tr>
</table>
<p>&nbsp;</p>
<table cellspacing="0" cellpadding="8" border="0" width="100%" style="border: 1px solid #000000;">
<tr>
<td colspan="5" style="text-align: center; background-color: lightskyblue; border-bottom: 1px solid #000000"><h4>Subject Informed Consent and Blood Donation</h4></td>
</tr>
<tr>
<td width="35%"><p>Informed consent was signed</p></td>
<td width="5%"><p><input type="checkbox" name="check" value="1" /></p></td>
<td width="10%"><p>Yes</p></td>
<td width="5%"><p><input type="checkbox" name="check" value="0" /></p></td>
<td width="35%"><p>No</p></td>
</tr>
<tr>
<td colspan="5"><small><strong>Subjects must have signed the informed consent form prior to the blood donation (incl. date and time point)!</strong></small></td>
</tr>
<tr>
<td width="30%"><p>Print date:</p></td>
<td colspan="4" width="70%"><p>'.date('d / m / Y').' <small>(DD/MMM/YYYY)</small></p></td>
</tr>
<tr>
<td colspan="5"><p>Required 3 Lithium Heparin plasma samples taken and filled sufficiently (start time):</p></td>
</tr>
<tr>
<td width="2%"></td>
<td colspan="4"><p>|___| |___| : |___| |___| <small>(hh:min)</small></p></td>
</tr>
<tr>
<td width="25%"></td>
<td width="50%"><table cellpadding="26" cellspacing="0" style="border: 1px solid #6c757d; background-color: #CCCCCC; "><tr><td style="text-align: center"><p><small>Please insert label</small></p></td></tr></table></td>
<td width="25%"></td>
</tr>
<tr>
<td width="76%"><p>Subject was asked prior to leaving the study center regarding his/her wellbeing:</p></td>
<td width="5%"><p><input type="checkbox" name="check1" value="1" /></p></td>
<td width="7%"><p>Yes</p></td>
<td width="5%"><p><input type="checkbox" name="check1" value="0" /></p></td>
<td width="7%"><p>No</p></td>
</tr>
<tr>
<td width="15%"><p>Comments: </p></td>
<td width="85%" colspan="4"><p><small>________________________________________________________________________________________________________</small></p></td>
</tr>
<tr>
<td width="15%"></td>
<td width="85%" colspan="4"><p><small>________________________________________________________________________________________________________</small></p></td>
</tr>
<tr>
<td width="20%" colspan="2"></td>
<td width="50%" style="text-align: right;"><p>Initials: </p></td>
<td width="30%"><p>___________________</p></td>
</tr>
</table>
</header>';
        $pdf->WriteHTML($html);

        $pdf->AddPage ( 'P' );


        $html='<header>
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
<td width="20%"><img src="https://nuvisan.de/themes/nuvisan/img/nuvisan-logo-static.png" style="width: 200px" height="auto"> </td>
<td width="60%" style="padding-left: 10px;"><p><small>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;NUVISAN Study Code: N-A-PH1-19-020<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sponsor Study Code: RD005065</small></p></td>
<td width="20%" style="text-align: right"><p><small>Page 2</small></p></td>
</tr>
</table>
<p>&nbsp;</p>
<table cellspacing="0" cellpadding="8" border="0" width="100%" style="border: 1px solid #000000;">
<tr>
<td colspan="5" style="text-align: center; background-color: lightskyblue; border-bottom: 1px solid #000000"><h4>KASSENBELEG</h4></td>
</tr>
<tr>
<td width="25%"></td>
<td width="50%" colspan="3"><table cellpadding="26" cellspacing="0" style="border: 1px solid #6c757d; background-color: #CCCCCC; "><tr><td style="text-align: center"><p><small>Please insert label</small></p></td></tr></table></td>
<td width="25%"></td>
</tr>
<tr>
<td width="30%"><p><strong>Vorname:</strong></p></td>
<td colspan="4" width="70%"><p>'.$data->getFirstname().'</p></td>
</tr>
<tr>
<td width="30%"><p><strong>Nachname:</strong></p></td>
<td colspan="4" width="70%"><p>'.$data->getLastname().'</p></td>
</tr>
<tr>
<td colspan="5"><p>Ich habe eine Aufwandsentschädigung von 60 Euro erhalten.<br>Ich wurde darüber informiert, dass diese Aufwandsentschädigung steuerpflichtig ist.<br>Die Einsichtnahme in alle Unterlagen kann in begründeten Fällen durch Dritte erfolgen, z.B. Behörden und Auditoren. </p></td>
</tr>
<tr>
<td width="30%"><p>Datum:</p></td>
<td colspan="4" width="70%"><p>'.date('d / m / Y').'</p></td>
</tr>
<tr>
<td width="30%"><p>Unterschrift:</p></td>
<td colspan="4" width="70%"><p>________________________________________</p></td>
</tr>
</table>
</header>';

        $pdf->WriteHTML($html);

        $pdf->Output ();
    }

    public function setCheckinStatus($id)
    {
        $study = $this->entityManager->getRepository('SiowebDummyBundle:Study')->find($id);
        $study->setStatus('Checkin/Einweisung');
        $study->setCheckindate(date('Y-m-d H:i:s'));
        $this->entityManager->flush();
    }


    public function outputHTMLCheckout($data)
    {

        $birthdate = new \DateTime($data->getBirthday());
        $slotdate = new \DateTime($data->getRegisterdate().' '.$data->getRegistertime());
        $age = date_diff(date_create($data->getBirthday()), date_create('today'))->y;

        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: private', false);
        header('Content-Type: application/pdf');
        header('content-Disposition:attachment;filename="downloaded.pdf"');

        $pdf = new \TCPDF( 'P', 'mm', 'A4' );
        $pdf->SetMargins(22, 15, 8, true);
        $pdf->AddPage ( 'P' );
        $bMargin = $pdf->getBreakMargin();
        $auto_page_break = $pdf->getAutoPageBreak();
        $pdf->SetAutoPageBreak(false, 0);
        $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
        $pdf->setPageMark();
        $pdf->SetFontSize(10);


        $html='<header>
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
<td width="20%"><img src="https://nuvisan.de/themes/nuvisan/img/nuvisan-logo-static.png" style="width: 200px" height="auto"> </td>
<td width="60%" style="padding-left: 10px;"><p><small>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;NUVISAN Study Code: N-A-PH1-19-020<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sponsor Study Code: RD005065</small></p></td>
<td width="20%" style="text-align: right"><p><small>Page 1</small></p></td>
</tr>
</table>
<p>&nbsp;</p>
<table cellspacing="0" cellpadding="8" border="0" width="100%" style="border: 1px solid #000000;">
<tr>
<td colspan="3" style="text-align: center; background-color: lightskyblue; border-bottom: 1px solid #000000"><h4>Demographic Data</h4></td>
</tr>
<tr>
<td width="30%"><p><strong>Slot date:</strong></p></td>
<td colspan="2" width="70%"><p>'.$slotdate->format('d / m / Y').' <small>(DD/MMM/YYYY)</small></p></td>
</tr>
<tr>
<td width="30%"><p><strong>Slot Time:</strong></p></td>
<td colspan="2" width="70%"><p>'.$slotdate->format('H : i').' <small>(hh:min)</small></p></td>
</tr>
<tr>
<td width="30%"><p><strong>Birth date:</strong></p></td>
<td colspan="2" width="70%"><p>'.$birthdate->format('d / m / Y').' <small>(DD/MMM/YYYY)</small></p></td>
</tr>
<tr>
<td width="30%"><p><strong>Age:</strong></p></td>
<td colspan="2" width="70%"><p>'.$age.' <small>(if age over 18 - exclusion from study)</small></p></td>
</tr>
<tr>
<td width="70%"><p>Personal data were checked by verification of the identity card during check in-as well as a double registration to avoid multiple participations:</p></td>
<td width="5%"><p><input type="checkbox" name="agree" value="1" /></p></td>
<td width="25%"><p>Yes</p></td>
</tr>
<tr>
<td width="70%"><p>Voluntary declaration was signed by employee and works council:<br><small>Only for NUVISAN  GmbH employees (needs to be completed if subject is a NUVISAN GmbH employee)</small></p></td>
<td width="5%"><p><input type="checkbox" name="agree" value="1" /></p></td>
<td width="25%"><p>Yes</p></td>
</tr>
<tr>
<td width="20%"></td>
<td width="50%" style="text-align: right;"><p>Initials: </p></td>
<td width="30%"><p>___________________</p></td>
</tr>
</table>
<p>&nbsp;</p>
<table cellspacing="0" cellpadding="8" border="0" width="100%" style="border: 1px solid #000000;">
<tr>
<td colspan="5" style="text-align: center; background-color: lightskyblue; border-bottom: 1px solid #000000"><h4>Subject Informed Consent and Blood Donation</h4></td>
</tr>
<tr>
<td width="35%"><p>Informed consent was signed</p></td>
<td width="5%"><p><input type="checkbox" name="check" value="1" /></p></td>
<td width="10%"><p>Yes</p></td>
<td width="5%"><p><input type="checkbox" name="check" value="0" /></p></td>
<td width="35%"><p>No</p></td>
</tr>
<tr>
<td colspan="5"><small><strong>Subjects must have signed the informed consent form prior to the blood donation (incl. date and time point)!</strong></small></td>
</tr>
<tr>
<td width="30%"><p>Print date:</p></td>
<td colspan="4" width="70%"><p>'.date('d / m / Y').' <small>(DD/MMM/YYYY)</small></p></td>
</tr>
<tr>
<td colspan="5"><p>Required 3 Lithium Heparin plasma samples taken and filled sufficiently(start time):</p></td>
</tr>
<tr>
<td width="2%"></td>
<td colspan="4"><p>|___| |___| : |___| |___| <small>(hh:min)</small></p></td>
</tr>
<tr>
<td width="25%"></td>
<td width="50%"><table cellpadding="26" cellspacing="0" style="border: 1px solid #6c757d; background-color: #CCCCCC; "><tr><td style="text-align: center"><p><small>Please insert label</small></p></td></tr></table></td>
<td width="25%"></td>
</tr>
<tr>
<td width="76%"><p>Subject was asked prior to leaving the study center regarding his/her wellbeing:</p></td>
<td width="5%"><p><input type="checkbox" name="check1" value="1" /></p></td>
<td width="7%"><p>Yes</p></td>
<td width="5%"><p><input type="checkbox" name="check1" value="0" /></p></td>
<td width="7%"><p>No</p></td>
</tr>
<tr>
<td width="36%"><p>Comments (only if ‘yes’ was ticked): </p></td>
<td width="64%" colspan="4"><p><small>___________________________________________________________________________</small></p></td>
</tr>
<tr>
<td width="36%"></td>
<td width="64%" colspan="4"><p><small>___________________________________________________________________________</small></p></td>
</tr>
<tr>
<td width="20%" colspan="2"></td>
<td width="50%" style="text-align: right;"><p>Initials: </p></td>
<td width="30%"><p>___________________</p></td>
</tr>
</table>
</header>';
        $pdf->WriteHTML($html);

        $pdf->AddPage ( 'P' );


        $html='<header>
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
<td width="20%"><img src="https://nuvisan.de/themes/nuvisan/img/nuvisan-logo-static.png" style="width: 200px" height="auto"> </td>
<td width="60%" style="padding-left: 10px;"><p><small>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;NUVISAN Study Code: N-A-PH1-19-020<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sponsor Study Code: RD005065</small></p></td>
<td width="20%" style="text-align: right"><p><small>Page 2</small></p></td>
</tr>
</table>
<p>&nbsp;</p>
<table cellspacing="0" cellpadding="8" border="0" width="100%" style="border: 1px solid #000000;">
<tr>
<td colspan="5" style="text-align: center; background-color: lightskyblue; border-bottom: 1px solid #000000"><h4>Subject Informed Consent and Blood Donation</h4></td>
</tr>
<tr>
<td width="25%"></td>
<td width="50%" colspan="3"><table cellpadding="26" cellspacing="0" style="border: 1px solid #6c757d; background-color: #CCCCCC; "><tr><td style="text-align: center"><p><small>Please insert label</small></p></td></tr></table></td>
<td width="25%"></td>
</tr>
<tr>
<td width="30%"><p><strong>Vorname:</strong></p></td>
<td colspan="4" width="70%"><p>'.$data->getFirstname().'</p></td>
</tr>
<tr>
<td width="30%"><p><strong>Nachname:</strong></p></td>
<td colspan="4" width="70%"><p>'.$data->getLastname().'</p></td>
</tr>
<tr>
<td colspan="5"><p>Ich habe eine Aufwandsentschädigung von 60 Euro erhalten.<br>Ich wurde darüber informiert, dass diese Aufwandsentschädigung steuerpflichtig ist.<br>Die Einsichtnahme in alle Unterlagen kann in begründeten Fällen durch Dritte erfolgen, z.B. Behörden und Auditoren. </p></td>
</tr>
<tr>
<td width="30%"><p>Datum:</p></td>
<td colspan="4" width="70%"><p>'.date('d / m / Y').'</p></td>
</tr>
<tr>
<td width="30%"><p>Unterschrift:</p></td>
<td colspan="4" width="70%"><p>________________________________________</p></td>
</tr>
</table>
</header>';

        $pdf->WriteHTML($html);
        $pdf->Output ();
    }

    public function setCheckoutStatus($id)
    {
        $study = $this->entityManager->getRepository('SiowebDummyBundle:Study')->find($id);
        $study->setStatus('Blut abgenommen/Auszahlung');
        $study->setBlooddate(date('Y-m-d H:i:s'));
        $this->entityManager->flush();
    }
}