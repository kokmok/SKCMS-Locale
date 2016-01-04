<?php
namespace SKCMS\LocaleBundle\Logging;
/**
 * Created by jona on 4/01/16
 */

use SKCMS\LocaleBundle\Event\MissingTranslationEvent;
use Symfony\Component\EventDispatcher\Debug\TraceableEventDispatcher;
use \Symfony\Component\Translation\LoggingTranslator as BaseLoggingTranslator;

class LoggingTranslator extends BaseLoggingTranslator
{

    /**
     * @var TraceableEventDispatcher
     */
    private $eventDispatcher;

    public function setEventDispatcher(TraceableEventDispatcher $ed){
        $this->eventDispatcher = $ed;
    }

    public function trans($id, array $parameters = array(), $domain = null, $locale = null)
    {
        $parentValue = parent::trans($id, $parameters, $domain, $locale);
        $this->dispatchIfNotExists($id, $parameters, $domain, $locale);
        return $parentValue;
    }
    /**
     * {@inheritdoc}
     */
    public function transChoice($id, $number, array $parameters = array(), $domain = null, $locale = null)
    {
        $parentValue = parent::transChoice($id,$number,$parameters,$domain,$locale);
        return $parentValue;
    }


    protected function dispatchIfNotExists($id, $parameters, $domain, $locale){

        if (null === $domain) {
            $domain = 'messages';
        }

        $id = (string) $id;

        if ($this->getCatalogue()->defines($id, $domain)) {
            return;
        }

        $event = new MissingTranslationEvent($id, $parameters, $domain, $locale === null ? $this->getCatalogue()->getLocale():$locale);
        $this->eventDispatcher->dispatch(MissingTranslationEvent::EVENT_NAME,$event);

    }

}