<?php

namespace SKCMS\LocaleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Translatable;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * TranslatableEntity
 *
 *  @ORM\MappedSuperclass 
 *  @Gedmo\TranslationEntity(class="SKCMS\LocaleBundle\Entity\Translation\EntityTranslation")  
 */
class TranslatableEntity implements Translatable
{
   /** 
     * @ORM\Column(name="id",type="integer") 
     */
    protected $id;

    /**
     * Post locale
     * Used locale to override Translation listener's locale
     *
     * @Gedmo\Locale
     */
    protected $locale;


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
     * Set locale
     *
     * @param string $locale
     * @return TranslatableEntity
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Get locale
     *
     * @return string 
     */
    public function getLocale()
    {
        return $this->locale;
    }
}
