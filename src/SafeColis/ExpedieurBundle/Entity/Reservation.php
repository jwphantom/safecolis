<?php

namespace SafeColis\ExpedieurBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Serializable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * User
 *
 * @ORM\Table(name="reservation")
 * @ORM\Entity()
 *
 * @ORM\Entity(repositoryClass="SafeColis\ExpedieurBundle\Repository\ReservationRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Reservation
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
    private $idreserveur;

    /** 
      * @ORM\ManyToOne(targetEntity="SafeColis\VoyageurBundle\Entity\Voyageur", cascade={"persist"})
     */
    private $voyage;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string" , length=255)
     */
    private $description;


    /**
     * @var string
     *
     * @ORM\Column(name="kilovoulu", type="float")
     */
    private $kiloVoulu;

    /**
     *
     * @ORM\Column(name="dateajout", type="date")
     */
    private $dateajout;
    

    /**
     * @var string
     *
     * @ORM\Column(name="etat", type="boolean" , length=255)
     */
    private $etat;

    /**
     * @var string
     *
     * @ORM\Column(name="paye", type="boolean" , length=255)
     */
    private $paye;



 

    

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
     * Set description
     *
     * @param string $description
     *
     * @return Reservation
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set kiloVoulu
     *
     * @param float $kiloVoulu
     *
     * @return Reservation
     */
    public function setKiloVoulu($kiloVoulu)
    {
        $this->kiloVoulu = $kiloVoulu;

        return $this;
    }

    /**
     * Get kiloVoulu
     *
     * @return float
     */
    public function getKiloVoulu()
    {
        return $this->kiloVoulu;
    }

    /**
     * Set dateajout
     *
     * @param \DateTime $dateajout
     *
     * @return Reservation
     */
    public function setDateajout($dateajout)
    {
        $this->dateajout = $dateajout;

        return $this;
    }

    /**
     * Get dateajout
     *
     * @return \DateTime
     */
    public function getDateajout()
    {
        return $this->dateajout;
    }

    /**
     * Set etat
     *
     * @param boolean $etat
     *
     * @return Reservation
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get etat
     *
     * @return boolean
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * Set paye
     *
     * @param boolean $paye
     *
     * @return Reservation
     */
    public function setPaye($paye)
    {
        $this->paye = $paye;

        return $this;
    }

    /**
     * Get paye
     *
     * @return boolean
     */
    public function getPaye()
    {
        return $this->paye;
    }

    /**
     * Set idreserveur
     *
     * @param \AppBundle\Entity\User $idreserveur
     *
     * @return Reservation
     */
    public function setIdreserveur(\AppBundle\Entity\User $idreserveur = null)
    {
        $this->idreserveur = $idreserveur;

        return $this;
    }

    /**
     * Get idreserveur
     *
     * @return \AppBundle\Entity\User
     */
    public function getIdreserveur()
    {
        return $this->idreserveur;
    }

    /**
     * Set voyage
     *
     * @param \SafeColis\VoyageurBundle\Entity\Voyageur $voyage
     *
     * @return Reservation
     */
    public function setVoyage(\SafeColis\VoyageurBundle\Entity\Voyageur $voyage = null)
    {
        $this->voyage = $voyage;

        return $this;
    }

    /**
     * Get voyage
     *
     * @return \SafeColis\VoyageurBundle\Entity\Voyageur
     */
    public function getVoyage()
    {
        return $this->voyage;
    }
}
