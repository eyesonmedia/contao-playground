<?php

namespace Sioweb\DummyBundle\Service;

use Doctrine\ORM\EntityManager;

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

    /**
     * reduce count of Time
     *
     * @return array|\Freshframes\WorkflowBundle\Entity\Workflow[]
     */
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
}