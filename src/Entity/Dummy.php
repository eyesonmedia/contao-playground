<?php

namespace Sioweb\DummyBundle\Entity;

use \Doctrine\ORM\Mapping as ORM;

/**
 * Class Log
 *
 * @ORM\Entity
 * @ORM\Table(name="eonm_dates")
 */
class Dummy
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
     * @var date $date
     *
     * @ORM\Column(name="date", type="date", nullable=true)
     */
    private $date;

    /**
     * @var string
     * @ORM\Column(type="text", length=65535, nullable=true)
     */
    protected $description;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    protected $study;

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
     * @return  int
     */
    public function getTstamp()
    {
        return $this->tstamp;
    }

    /**
     * Set the value of tstamp
     *
     * @param  int  $tstamp
     *
     * @return  self
     */
    public function setTstamp($tstamp)
    {
        $this->tstamp = $tstamp;

        return $this;
    }

    /**
     * Get the value of date
     *
     * @return  int
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set the value of date
     *
     * @param  int  $date
     *
     * @return  self
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }



    /**
     * Get the value of study
     *
     * @return  string
     */
    public function getStudy()
    {
        return $this->study;
    }

    /**
     * Set the value of study
     *
     * @param   string  $study
     *
     * @return  self
     */
    public function setStudy($study)
    {
        $this->study = $study;

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