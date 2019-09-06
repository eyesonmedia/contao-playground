<?php

namespace Sioweb\DummyBundle\Service;

use Doctrine\ORM\EntityManager;
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
    public function findAllTimes()
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

    public function addTimeCount($id, $registrationid)
    {
        $time = $this->entityManager->getRepository('SiowebDummyBundle:Time')->find($id);

        if (!$time) {
            throw $this->createNotFoundException(
                'No Time found for id '.$id
            );
        }

        $count = $time->getCount();
        if ( $count < 5 ) {

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
}