<?php

namespace SafeColis\AbonnementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Serializable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * User
 *
 * @ORM\Table(name="abonnement")
 * @ORM\Entity()
 *
 * @ORM\Entity(repositoryClass="SafeColis\AbonnementBundle\Repository\AbonnementRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Abonnement
{
     /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** 
      * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", cascade={"persist"})
     */
    private $user;


    /**
     * @var string
     *
     * @ORM\Column(name="plan", type="string" , length=255)
     */
    private $plan;

    /**
     * @var string
     *
     * @ORM\Column(name="date_debut", type="string" , length=255, nullable=true)
     */
    private $date_debut;

    /**
     * @var string
     *
     * @ORM\Column(name="date_fin", type="string" , length=255, nullable=true)
     */
    private $date_fin;


    /**
     * @var string
     *
     * @ORM\Column(name="renouvellement", type="boolean", nullable=true)
     */
    private $renouvellement;

        /**
     * @var string
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;






    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set plan
     *
     * @param string $plan
     *
     * @return Abonnement
     */
    public function setPlan($plan)
    {
        $this->plan = $plan;

        return $this;
    }

    /**
     * Get plan
     *
     * @return string
     */
    public function getPlan()
    {
        return $this->plan;
    }

    /**
     * Set dateDebut
     *
     * @param string $dateDebut
     *
     * @return Abonnement
     */
    public function setDateDebut($dateDebut)
    {
        $this->date_debut = $dateDebut;

        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return string
     */
    public function getDateDebut()
    {
        return $this->date_debut;
    }

    /**
     * Set dateFin
     *
     * @param string $dateFin
     *
     * @return Abonnement
     */
    public function setDateFin($dateFin)
    {
        $this->date_fin = $dateFin;

        return $this;
    }

    /**
     * Get dateFin
     *
     * @return string
     */
    public function getDateFin()
    {
        return $this->date_fin;
    }

    /**
     * Set renouvellement
     *
     * @param boolean $renouvellement
     *
     * @return Abonnement
     */
    public function setRenouvellement($renouvellement)
    {
        $this->renouvellement = $renouvellement;

        return $this;
    }

    /**
     * Get renouvellement
     *
     * @return boolean
     */
    public function getRenouvellement()
    {
        return $this->renouvellement;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Abonnement
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Abonnement
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
