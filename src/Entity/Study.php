<?php

namespace Sioweb\DummyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Time
 * @ORM\Table(name="`eonm_study`")
 * @ORM\Entity(repositoryClass="Sioweb\DummyBundle\Entity\Repository\TestRepository")
 */
class Study
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
     * @ORM\Column(type="text", length=255)
     */
    protected $tstamp;

    /**
     * @var int
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    protected $timeid;

    /**
     * @var int
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    protected $registrationid;

    /**
     * @var string
     * @ORM\Column(type="text", length=255)
     */
    private $registergroup;

    /**
     * @var string
     * @ORM\Column(type="text", length=255)
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(type="text", length=510)
     */
    private $firstname;

    /**
     * @var string
     * @ORM\Column(type="text", length=510)
     */
    private $lastname;

    /**
     * @var string
     * @ORM\Column(type="text", length=510)
     */
    private $email;

    /**
     * @var string
     * @ORM\Column(type="text", length=255)
     */
    private $phone;

    /**
     * @var string
     * @ORM\Column(type="text", length=510)
     */
    private $street;

    /**
     * @var string
     * @ORM\Column(type="text", length=255)
     */
    private $zip;

    /**
     * @var string
     * @ORM\Column(type="text", length=255)
     */
    private $city;

    /**
     * @var string
     * @ORM\Column(type="text", length=255)
     */
    private $birthday;

    /**
     * @var string
     * @ORM\Column(type="text", length=255)
     */
    private $registerdate;

    /**
     * @var string
     * @ORM\Column(type="text", length=255)
     */
    private $registertime;

    /**
     * @var string
     * @ORM\Column(type="text", length=255)
     */
    private $checkindate;

    /**
     * @var string
     * @ORM\Column(type="text", length=255)
     */
    private $blooddate;

    /**
     * @var string
     * @ORM\Column(type="text", length=255)
     */
    private $study;

    /**
     * @var string
     * @ORM\Column(type="text", length=255)
     */
    private $status;

    /**
     * @var string
     * @ORM\Column(type="text", length=255)
     */
    private $cdate;


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
     * Get the value of timeid
     *
     * @return  int
     */
    public function getTimeid()
    {
        return $this->timeid;
    }

    /**
     * Set the value of timeid
     *
     * @param  int  $timeid
     *
     * @return  self
     */
    public function setTimeid($timeid)
    {
        $this->timeid = $timeid;

        return $this;
    }


    /**
     * Get the value of registrationid
     *
     * @return  int
     */
    public function getRegistrationid()
    {
        return $this->registrationid;
    }

    /**
     * Set the value of registrationid
     *
     * @param  int  $timeid
     *
     * @return  self
     */
    public function setRegistrationid($registrationid)
    {
        $this->registrationid = $registrationid;

        return $this;
    }

    /**
     * Get the value of registergroup
     *
     * @return  int
     */
    public function getRegistergroup()
    {
        return $this->registergroup;
    }

    /**
     * Set the value of registergroup
     *
     * @param  int  $registergroup
     *
     * @return  self
     */
    public function setRegistergroup($registergroup)
    {
        $this->registergroup = $registergroup;

        return $this;
    }

    /**
     * Get the value of birthday
     *
     * @return  string
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set the value of birthday
     *
     * @param  string  $birthday
     *
     * @return  self
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get the value of title
     *
     * @return  int
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @param  int  $title
     *
     * @return  self
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of firstname
     *
     * @return  int
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set the value of firstname
     *
     * @param  int  $firstname
     *
     * @return  self
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get the value of lastname
     *
     * @return  int
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set the value of lastname
     *
     * @param  int  $lastname
     *
     * @return  self
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get the value of email
     *
     * @return  int
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @param  int  $email
     *
     * @return  self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of phone
     *
     * @return  int
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set the value of phone
     *
     * @param  int  $phone
     *
     * @return  self
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get the value of street
     *
     * @return  int
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set the value of street
     *
     * @param  int  $street
     *
     * @return  self
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get the value of zip
     *
     * @return  int
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * Set the value of zip
     *
     * @param  int  $zip
     *
     * @return  self
     */
    public function setZip($zip)
    {
        $this->zip = $zip;

        return $this;
    }

    /**
     * Get the value of city
     *
     * @return  int
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set the value of city
     *
     * @param  int  $city
     *
     * @return  self
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get the value of registerdate
     *
     * @return  int
     */
    public function getRegisterdate()
    {
        return $this->registerdate;
    }

    /**
     * Set the value of registerdate
     *
     * @param  int  $registerdate
     *
     * @return  self
     */
    public function setRegisterdate($registerdate)
    {
        $this->registerdate = $registerdate;

        return $this;
    }

    /**
     * Get the value of checkindate
     *
     * @return  int
     */
    public function getCheckindate()
    {
        return $this->checkindate;
    }

    /**
     * Set the value of checkindate
     *
     * @param  int  $checkindate
     *
     * @return  self
     */
    public function setCheckindate($checkindate)
    {
        $this->checkindate = $checkindate;

        return $this;
    }

    /**
     * Get the value of blooddate
     *
     * @return  int
     */
    public function getBlooddate()
    {
        return $this->blooddate;
    }

    /**
     * Set the value of blooddate
     *
     * @param  int  $blooddate
     *
     * @return  self
     */
    public function setBlooddate($blooddate)
    {
        $this->blooddate = $blooddate;

        return $this;
    }

    /**
     * Get the value of registertime
     *
     * @return  int
     */
    public function getRegistertime()
    {
        return $this->registertime;
    }

    /**
     * Set the value of registertime
     *
     * @param  int  $registerdate
     *
     * @return  self
     */
    public function setRegistertime($registertime)
    {
        $this->registertime = $registertime;

        return $this;
    }

    /**
     * Get the value of study
     *
     * @return  int
     */
    public function getStudy()
    {
        return $this->study;
    }

    /**
     * Set the value of study
     *
     * @param  int  $study
     *
     * @return  self
     */
    public function setStudy($study)
    {
        $this->study = $study;

        return $this;
    }

    /**
     * Get the value of status
     *
     * @return  int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @param  int  $status
     *
     * @return  self
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get the value of cdate
     *
     * @return  int
     */
    public function getCdate()
    {
        return $this->cdate;
    }

    /**
     * Set the value of cdate
     *
     * @param  int  $cdate
     *
     * @return  self
     */
    public function setCdate($cdate)
    {
        $this->cdate = $cdate;

        return $this;
    }
}