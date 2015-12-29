<?php

/**
 * Created by jona on 28/12/15
 */
namespace SKCMS\LocaleBundle\Loader;


use JMS\TranslationBundle\Util\FileUtils;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Yaml\Yaml;

class TranslationFilesLoader
{

    private $format;
    private $rootDir;
    private $locales = [];
    private $translationTree=[];
    private $unTranslatedTree=[];

    public function __construct($rootDir,$format = 'yml',array $locales){
        $this->format = 'yml';
        $this->rootDir = $rootDir;
        $this->locales = $locales;
    }

    public function loadTranslations(){

        $files = $this->searchRecursively($this->rootDir.'/Resources');
        $files = array_merge($this->searchRecursively($this->rootDir.'/../vendor'),$files);


        //make translations trees
        foreach ($files as $file){
            preg_match('#\.([a-z]{2})\.'.$this->format.'$#',$file,$matches);

            $locale = $matches[1];
            if( in_array($locale,$this->locales))
            {
                //Translation Tree
//                if (!array_key_exists($locale,$this->translationTree)){
//                    $this->translationTree[$locale] = [];
//                }
                //Get Domain
                preg_match('#.+\/([a-zA-Z]+)\.[a-z]{2}\.'.$this->format.'$#',$file,$matches);
                $domain = $matches[1];
                if (!array_key_exists($domain,$this->translationTree)){
                    $this->translationTree[$domain] = [];
                }
                $translations = $this->formatTranslations(Yaml::parse($file),$locale);
                $this->translationTree[$domain] = array_merge_recursive($this->translationTree[$domain],$translations);

                $untranslated = $this->makeUntranslatedTree($translations,$locale);
                if (count($untranslated)){
                    if (!array_key_exists($domain,$this->unTranslatedTree)){
                        $this->unTranslatedTree[$domain] = [];
                    }
                    $this->unTranslatedTree[$domain] = array_merge_recursive($this->unTranslatedTree[$domain],$untranslated);
                }

            }

        }

    }

    private function formatTranslations($translations,$locale){
        $tree = [];
        foreach ($translations as $translationKey => $translation){
            if (is_array($translation)){
                $tree[$translationKey] = $this->formatTranslations($translation,$locale);
            }
            else{
                $tree[$translationKey][$locale]= $translation;

            }
        }
        return $tree;
    }

    private function makeUntranslatedTree($translations,$locale){
        $tree = [];
        foreach ($translations as $translationKey => $translation){
            if (is_array($translation)){
                $tempTree = $this->makeUntranslatedTree($translation,$locale);
                if (count($tempTree)){
                    $tree[$translationKey] = $tempTree;
                }
            }
            else{
                if (preg_match('#^__#',$translation)){
                    $tree[$translationKey][$locale] = $translation;
                }
            }
        }
        return $tree;
    }

    private function searchRecursively($directory){
        $files = [];
        $dirContent = scandir($directory);
        foreach ($dirContent as $content){
            if ('.' !== $content && '..' !== $content){
                if (is_dir($directory.'/'.$content)){
                    $files = array_merge($files,$this->searchRecursively($directory.'/'.$content));
                }
                else{
                    if (preg_match('#\.[a-z]{2}\.'.$this->format.'$#',$content)){
                        $files[] = $directory.'/'.$content;
                    }
                }
            }
        }
        return $files;

    }

    public function getTranslationTree(){
        return $this->translationTree;
    }

}