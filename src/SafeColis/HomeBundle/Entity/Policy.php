<?php

namespace SafeColis\HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Serializable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;


/**
 *
 * @ORM\Table(name="policy")
 * @ORM\Entity()
* @ORM\HasLifecycleCallbacks()
 */
class Policy
{
 /**
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @ORM\Column(name="date_edit", type="string", length=255)
   */
  private $date_edit;

    /**
   * @ORM\Column(name="content", type="text", nullable=true)
   */
  private $content;


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
     * Set dateEdit
     *
     * @param string $dateEdit
     *
     * @return Policy
     */
    public function setDateEdit($dateEdit)
    {
        $this->date_edit = $dateEdit;

        return $this;
    }

    /**
     * Get dateEdit
     *
     * @return string
     */
    public function getDateEdit()
    {
        return $this->date_edit;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Policy
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }
}
