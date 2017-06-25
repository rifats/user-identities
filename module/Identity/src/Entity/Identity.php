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
     * @ORM\Column(name="ident_type", type="string", length=50, nullable=false)
     */
    protected $identType;

    /** 
     * @ORM\Column(name="name", type="string", length=30, nullable=false)
     */
    protected $name;

    /** 
     * @ORM\Column(name="surname", type="string", length=40, nullable=false)
     */
    protected $surname;

    /**
     * @ORM\Column(name="date_created", type="datetime", nullable=false)
     */
    protected $dateCreated;

    /**
     * @ORM\Column(name="range", type="integer", nullable=true)
     */
    protected $range;

    /**
     * @ORM\Column(name="identity_id", type="string", length=50, nullable=false)
     */
    protected $identityId;

    /**
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
     * @ORM\Column(name="date_of_issue", type="datetime", nullable=false)
     */
    protected $dateOfIssue;

    /**
     * @ORM\Column(name="date_of_expire", type="datetime", nullable=false)
     */
    protected $dateOfExpire;

    /**
     * @ORM\Column(name="authority", type="string", length=250, nullable=true)
     */
    protected $authority;

    /**
     * @ORM\Column(name="is_valid", type="boolean", options={"default": true})
     */
    protected $isValid;

    /**
     * @ORM\ManyToOne(targetEntity="\User\Entity\User", inversedBy="identities")
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

    /**
     * @param mixed $authority
     */
    public function setAuthority($authority)
    {
        $this->authority = $authority;
    }

    /**
     * @return mixed
     */
    public function getAuthority()
    {
        return $this->authority;
    }

    /**
     * @param mixed $dateOfExpire
     */
    public function setDateOfExpire($dateOfExpire)
    {
        $this->dateOfExpire = $dateOfExpire;
    }

    /**
     * @return mixed
     */
    public function getDateOfExpire()
    {
        return $this->dateOfExpire;
    }

    /**
     * @param mixed $dateOfIssue
     */
    public function setDateOfIssue($dateOfIssue)
    {
        $this->dateOfIssue = $dateOfIssue;
    }

    /**
     * @return mixed
     */
    public function getDateOfIssue()
    {
        return $this->dateOfIssue;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $identityId
     */
    public function setIdentityId($identityId)
    {
        $this->identityId = $identityId;
    }

    /**
     * @return mixed
     */
    public function getIdentityId()
    {
        return $this->identityId;
    }

    /**
     * @param mixed $isValid
     */
    public function setIsValid($isValid)
    {
        $this->isValid = $isValid;
    }

    /**
     * @return mixed
     */
    public function getIsValid()
    {
        return $this->isValid;
    }

    /**
     * @param mixed $range
     */
    public function setRange($range)
    {
        $this->range = $range;
    }

    /**
     * @return mixed
     */
    public function getRange()
    {
        return $this->range;
    }
}