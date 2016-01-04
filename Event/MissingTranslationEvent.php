<?php
namespace SKCMS\LocaleBundle\Event;
use Symfony\Component\EventDispatcher\Event;

/**
 * Created by jona on 4/01/16
 */

class MissingTranslationEvent extends Event
{
    const EVENT_NAME = 'skcms_missing_translation_event';

    private $locale;
    private $domain;
    private $parameters;
    private $id;

    public function __construct($id,$parameters,$domain,$locale)
    {
        $this->id = $id;
        $this->parameters = $parameters;
        $this->domain = $domain;
        $this->locale = $locale;
    }

    /**
     * @return mixed
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param mixed $locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * @return mixed
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param mixed $domain
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    /**
     * @return mixed
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param mixed $parameters
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }




}