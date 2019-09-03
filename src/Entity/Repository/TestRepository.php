<?php
namespace Sioweb\DummyBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class TestRepository extends EntityRepository
{
    public function findAll()
    {
        return $this->findBy(array(), array('date' => 'ASC'));
    }
}