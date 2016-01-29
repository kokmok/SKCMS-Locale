<?php

namespace SKCMS\LocaleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Country
 *
 * @ORM\Table(name="SKCountry")
 * @ORM\Entity(repositoryClass="SKCMS\LocaleBundle\Entity\CountryRepository")
 */
class Country extends TranslatableEntity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @Gedmo\Translatable
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\OneToMany(targetEntity="SKCMS\LocaleBundle\Entity\CountryNativeName", mappedBy="country",cascade={"all"})
     */
    private $nativeNames;

    /**
     * @var string
     *
     * @ORM\Column(name="OfficialName", type="string", length=255)
     */
    private $officialName;

    /**
     * @var string
     *
     * @ORM\Column(name="ISO", type="string", length=255)
     */
    private $iSO;
    
    /**
     *
     * @ORM\ManyToOne(targetEntity="SKCMS\LocaleBundle\Entity\Currency")
     */
    private $currency;
    
    /**
     * 
     * @ORM\ManyToMany(targetEntity="SKCMS\LocaleBundle\Entity\Language")
     * 
     */
    private $languages;
    
    

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
     * @return Country
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
     * Set officialName
     *
     * @param string $officialName
     * @return Country
     */
    public function setOfficialName($officialName)
    {
        $this->officialName = $officialName;

        return $this;
    }

    /**
     * Get officialName
     *
     * @return string 
     */
    public function getOfficialName()
    {
        return $this->officialName;
    }

    /**
     * Set iSO
     *
     * @param string $iSO
     * @return Country
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
    
    
     public function __toString()
    {
        
        if (null !== $this->nativeNames && count($this->nativeNames))
        {
            return implode(',',$this->nativeNames->toArray());
        }
        else
        {
            return $this->name;
        }
        
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->language = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set currency
     *
     * @param \SKCMS\LocaleBundle\Entity\Currency $currency
     * @return Country
     */
    public function setCurrency(\SKCMS\LocaleBundle\Entity\Currency $currency = null)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get currency
     *
     * @return \SKCMS\LocaleBundle\Entity\Currency 
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    

    /**
     * Add nativeNames
     *
     * @param \SKCMS\LocaleBundle\Entity\CountryNativeName $nativeNames
     * @return Country
     */
    public function addNativeName(\SKCMS\LocaleBundle\Entity\CountryNativeName $nativeNames)
    {
        $this->nativeNames[] = $nativeNames;
        $nativeNames->setCountry($this);

        return $this;
    }

    /**
     * Remove nativeNames
     *
     * @param \SKCMS\LocaleBundle\Entity\CountryNativeName $nativeNames
     */
    public function removeNativeName(\SKCMS\LocaleBundle\Entity\CountryNativeName $nativeNames)
    {
        $nativeNames->setCountry(null);
        $this->nativeNames->removeElement($nativeNames);
        return $this;
        
    }

    /**
     * Get nativeNames
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getNativeNames()
    {
        return $this->nativeNames;
    }

    /**
     * Add languages
     *
     * @param \SKCMS\LocaleBundle\Entity\Language $languages
     * @return Country
     */
    public function addLanguage(\SKCMS\LocaleBundle\Entity\Language $languages)
    {
        $this->languages[] = $languages;

        return $this;
    }

    /**
     * Remove languages
     *
     * @param \SKCMS\LocaleBundle\Entity\Language $languages
     */
    public function removeLanguage(\SKCMS\LocaleBundle\Entity\Language $languages)
    {
        $this->languages->removeElement($languages);
    }

    /**
     * Get languages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLanguages()
    {
        return $this->languages;
    }
}
