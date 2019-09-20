<?php

namespace Sioweb\DummyBundle\Service;

use Doctrine\ORM\EntityManager;
use Sioweb\DummyBundle\Entity\Intern;
use Sioweb\DummyBundle\Entity\Registration;

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
            ->findBy(array(), array('date' => 'ASC'));
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
            ->select('r.lastname, r.email, r.birthday')
            ->from('SiowebDummyBundle:Registration', 'r')
            ->where('LOWER(r.lastname) LIKE :name')
            ->andWhere('LOWER(r.email) LIKE :email')
            ->andWhere('LOWER(r.birthday) LIKE :birthday')
            ->setParameter('name', $lastname)
            ->setParameter('email', $email)
            ->setParameter('birthday', $birthday)
            ->getQuery()
            ->getResult();

         if($check) {
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
}