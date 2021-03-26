<?php

namespace SafeColis\VoyageurBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Serializable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use Symfony\Component\Validator\Constraints as Assert;


/**
 * User
 *
 * @ORM\Table(name="voyageur")
 * @ORM\Entity()
  * @ORM\HasLifecycleCallbacks

 */
class Voyageur
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
      * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", cascade={"persist", "remove"})
     */
    private $user;

     /**
     * @ORM\Column(name="heuredepart", type="string", length=255)
     */
    private $heuredepart;

     /**
     * @ORM\Column(name="kilodisponible", type="float")
     */
    private $kilodisponible;

    /**
     * @ORM\Column(name="kilovendu", type="float")
     */
    private $kilovendu;

    /**
     * @ORM\Column(name="prixkilo", type="float")
     */
    private $prixkilo;

    /**
     * @ORM\Column(name="villedepart", type="string" , length=255)
     */
    private $villedepart;

     /**
     * @ORM\Column(name="villearrive", type="string" , length=255)
     */
    private $villearrive;

    /**
     * @ORM\Column(name="termcondition", type="string" , length=255)
     */
    private $termcondition;

      /**
     * @ORM\Column(name="dateajout", type="datetime")
     */
    private $dateajout;

     /**
     * @ORM\Column(name="datedepart", type="string" , length=255)
     */
    private $datedepart;

     /** 
      * @ORM\OneToOne(targetEntity="SafeColis\VoyageurBundle\Entity\Identification", cascade={"persist", "remove"})
     */
    private $identification;

     /** 
      * @ORM\OneToOne(targetEntity="SafeColis\VoyageurBundle\Entity\Justification", cascade={"persist", "remove"})
     */
    private $justification;

   
    /**
     * @ORM\Column(name="active", type="boolean" , length=255, nullable=true)
     */
    private $active;

    /**
     * @ORM\Column(name="etat", type="string" , length=255, nullable=true)
     */
    private $etat;
    
     /**
     * @ORM\Column(name="statut", type="boolean", nullable=true)
     */
    private $statut;


        /**
     * @ORM\Column(name="url_justification", type="text" , nullable=true)
     */
    private $url_justification;

     /**
     * @ORM\Column(name="url_identification", type="text", nullable=true)
     */
    private $url_identification;



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
     * Set heuredepart
     *
     * @param string $heuredepart
     *
     * @return Voyageur
     */
    public function setHeuredepart($heuredepart)
    {
        $this->heuredepart = $heuredepart;

        return $this;
    }

    /**
     * Get heuredepart
     *
     * @return string
     */
    public function getHeuredepart()
    {
        return $this->heuredepart;
    }

    /**
     * Set kilodisponible
     *
     * @param float $kilodisponible
     *
     * @return Voyageur
     */
    public function setKilodisponible($kilodisponible)
    {
        $this->kilodisponible = $kilodisponible;

        return $this;
    }

    /**
     * Get kilodisponible
     *
     * @return float
     */
    public function getKilodisponible()
    {
        return $this->kilodisponible;
    }

    /**
     * Set kilovendu
     *
     * @param float $kilovendu
     *
     * @return Voyageur
     */
    public function setKilovendu($kilovendu)
    {
        $this->kilovendu = $kilovendu;

        return $this;
    }

    /**
     * Get kilovendu
     *
     * @return float
     */
    public function getKilovendu()
    {
        return $this->kilovendu;
    }

    /**
     * Set prixkilo
     *
     * @param float $prixkilo
     *
     * @return Voyageur
     */
    public function setPrixkilo($prixkilo)
    {
        $this->prixkilo = $prixkilo;

        return $this;
    }

    /**
     * Get prixkilo
     *
     * @return float
     */
    public function getPrixkilo()
    {
        return $this->prixkilo;
    }

    /**
     * Set villedepart
     *
     * @param string $villedepart
     *
     * @return Voyageur
     */
    public function setVilledepart($villedepart)
    {
        $this->villedepart = $villedepart;

        return $this;
    }

    /**
     * Get villedepart
     *
     * @return string
     */
    public function getVilledepart()
    {
        return $this->villedepart;
    }

    /**
     * Set villearrive
     *
     * @param string $villearrive
     *
     * @return Voyageur
     */
    public function setVillearrive($villearrive)
    {
        $this->villearrive = $villearrive;

        return $this;
    }

    /**
     * Get villearrive
     *
     * @return string
     */
    public function getVillearrive()
    {
        return $this->villearrive;
    }

    /**
     * Set termcondition
     *
     * @param string $termcondition
     *
     * @return Voyageur
     */
    public function setTermcondition($termcondition)
    {
        $this->termcondition = $termcondition;

        return $this;
    }

    /**
     * Get termcondition
     *
     * @return string
     */
    public function getTermcondition()
    {
        return $this->termcondition;
    }

    /**
     * Set dateajout
     *
     * @param \DateTime $dateajout
     *
     * @return Voyageur
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
     * Set datedepart
     *
     * @param string $datedepart
     *
     * @return Voyageur
     */
    public function setDatedepart($datedepart)
    {
        $this->datedepart = $datedepart;

        return $this;
    }

    /**
     * Get datedepart
     *
     * @return string
     */
    public function getDatedepart()
    {
        return $this->datedepart;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Voyageur
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
     * Set etat
     *
     * @param string $etat
     *
     * @return Voyageur
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get etat
     *
     * @return string
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * Set statut
     *
     * @param boolean $statut
     *
     * @return Voyageur
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return boolean
     */
    public function getStatut()
    {
        return $this->statut;
    }



    /**
     * Set urlIdentification
     *
     * @param string $urlIdentification
     *
     * @return Voyageur
     */
    public function setUrlIdentification($urlIdentification)
    {
        $this->url_identification = $urlIdentification;

        return $this;
    }

    /**
     * Get urlIdentification
     *
     * @return string
     */
    public function getUrlIdentification()
    {
        return $this->url_identification;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Voyageur
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

    /**
     * Set identification
     *
     * @param \SafeColis\VoyageurBundle\Entity\Identification $identification
     *
     * @return Voyageur
     */
    public function setIdentification(\SafeColis\VoyageurBundle\Entity\Identification $identification = null)
    {
        $this->identification = $identification;

        return $this;
    }

    /**
     * Get identification
     *
     * @return \SafeColis\VoyageurBundle\Entity\Identification
     */
    public function getIdentification()
    {
        return $this->identification;
    }

    /**
     * Set justification
     *
     * @param \SafeColis\VoyageurBundle\Entity\Justification $justification
     *
     * @return Voyageur
     */
    public function setJustification(\SafeColis\VoyageurBundle\Entity\Justification $justification = null)
    {
        $this->justification = $justification;

        return $this;
    }

    /**
     * Get justification
     *
     * @return \SafeColis\VoyageurBundle\Entity\Justification
     */
    public function getJustification()
    {
        return $this->justification;
    }

    /**
     * Set urlJustification
     *
     * @param string $urlJustification
     *
     * @return Voyageur
     */
    public function setUrlJustification($urlJustification)
    {
        $this->url_justification = $urlJustification;

        return $this;
    }

    /**
     * Get urlJustification
     *
     * @return string
     */
    public function getUrlJustification()
    {
        return $this->url_justification;
    }
}
