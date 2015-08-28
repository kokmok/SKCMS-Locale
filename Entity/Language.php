<?php

namespace SKCMS\LocaleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Language
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SKCMS\LocaleBundle\Entity\LanguageRepository")
 */
class Language
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="nativeName", type="string", length=255)
     */
    private $nativeName;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="ISO",type="string",length=5)
     */
    private $iSO;
    
    /**
     *
     * @ORM\Column(name="active",type="boolean")
     */
    private $active;
    
    public function __construct() {
        $this->active = false;
    }


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
     * Set name
     *
     * @param string $name
     * @return Language
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set nativeName
     *
     * @param string $nativeName
     * @return Language
     */
    public function setNativeName($nativeName)
    {
        $this->nativeName = $nativeName;

        return $this;
    }

    /**
     * Get nativeName
     *
     * @return string 
     */
    public function getNativeName()
    {
        return $this->nativeName;
    }

    /**
     * Set iSO
     *
     * @param string $iSO
     * @return Language
     */
    public function setISO($iSO)
    {
        $this->iSO = $iSO;

        return $this;
    }

    /**
     * Get iSO
     *
     * @return string 
     */
    public function getISO()
    {
        return $this->iSO;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return Language
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
}
