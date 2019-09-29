<?php

namespace Sioweb\DummyBundle\Entity;

use \Doctrine\ORM\Mapping as ORM;

/**
 * Class Time
 *
 * @ORM\Table(name="eonm_times")
 * @ORM\Entity(repositoryClass="Sioweb\DummyBundle\Entity\Repository\TestRepository")
 */
class Time
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="text", length=65535, nullable=true)
     */
    protected $tstamp;

    /**
     * @var int
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    protected $dateid;

    /**
     * @var int
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    protected $count;

    /**
     * @var int
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    protected $maxcount;

    /**
     * @var string
     * @ORM\Column(type="text", length=65535, nullable=true)
     */
    private $time;

    /**
     * @var string
     * @ORM\Column(type="text", length=65535, nullable=true)
     */
    protected $description;

    /**
     * @var string
     * @ORM\Column(type="string", length=1, options={"default" : ""})
     */
    protected $published = 1;

    // Diese Funktion ist sehr hilfreich, um alle Daten als Array zu erhalten.
    public function getData() {
        $arrData = [];
        foreach(preg_grep('|^get(?!Data)|', get_class_methods($this)) as $method) {
            $arrData[($Field = lcfirst(substr($method, 3)))] = $this->{$method}();
            if(is_object($arrData[$Field])) {
                $arrData[$Field] = $arrData[$Field]->getData();
            }
        }

        return $arrData;
    }


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of tstamp
     *
     * @return  string
     */
    public function getTstamp()
    {
        return $this->tstamp;
    }

    /**
     * Set the value of tstamp
     *
     * @param  string  $tstamp
     *
     * @return  self
     */
    public function setTstamp($tstamp)
    {
        $this->tstamp = $tstamp;

        return $this;
    }

    /**
     * Get the value of dateid
     *
     * @return  int
     */
    public function getDateid()
    {
        return $this->dateid;
    }

    /**
     * Set the value of dateid
     *
     * @param  int  $dateid
     *
     * @return  self
     */
    public function setDateid($dateid)
    {
        $this->dateid = $dateid;

        return $this;
    }

    /**
     * Get the value of count
     *
     * @return  int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Set the value of count
     *
     * @param  int  $count
     *
     * @return  self
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Get the value of maxcount
     *
     * @return  int
     */
    public function getMaxcount()
    {
        return $this->maxcount;
    }

    /**
     * Set the value of maxcount
     *
     * @param  int  $maxcount
     *
     * @return  self
     */
    public function setMaxcount($maxcount)
    {
        $this->maxcount = $maxcount;

        return $this;
    }

    /**
     * Get the value of time
     *
     * @return  int
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set the value of date
     *
     * @param  int  $date
     *
     * @return  self
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get the value of description
     *
     * @return  string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @param  string  $description
     *
     * @return  self
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of published
     *
     * @return  string
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set the value of published
     *
     * @param  string  $published
     *
     * @return  self
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }
}