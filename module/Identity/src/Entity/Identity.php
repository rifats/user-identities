<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Identity\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Identity
 * @package Identity\Entity
 * @ORM\Entity
 * @ORM\Table(name="identity")
 */
class Identity 
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id")   
     */
    protected $id;

    /** 
     * @ORM\Column(name="ident_type")
     */
    protected $identType;

    /** 
     * @ORM\Column(name="name")  
     */
    protected $name;

    /** 
     * @ORM\Column(name="surname")  
     */
    protected $surname;

    /**
     * @ORM\Column(name="date_created")  
     */
    protected $dateCreated;

    /**
     * @ORM\ManyToOne(targetEntity="\User\Entity\User", inversedBy="identities", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $dateCreated
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
    }

    /**
     * @return mixed
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * @param mixed $identType
     */
    public function setIdentType($identType)
    {
        $this->identType = $identType;
    }

    /**
     * @return mixed
     */
    public function getIdentType()
    {
        return $this->identType;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $surname
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    }

    /**
     * @return mixed
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @return \User\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param \User\Entity\User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
        //$user->addIdentity($this);
    }
}