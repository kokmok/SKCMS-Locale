<?php

/**
 * Created by jona on 28/12/15
 */
namespace SKCMS\LocaleBundle\Loader;


use JMS\TranslationBundle\Util\FileUtils;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Yaml\Yaml;

class TranslationFilesLoader
{

    const FORM_NAME = "translation::Form::";

    private $format;
    private $rootDir;
    private $locales = [];
    private $translationTree=[];
    private $unTranslatedTree=[];
    private $translationFiles =[];
    /**
     * @var Form
     */
    private $form;
    /**
     * @var FormFactory
     */
    private $formFactory;
    public function __construct($rootDir,$format ,array $locales,FormFactory $formFactory){
        $this->format = 'yml';
        $this->rootDir = $rootDir;
        $this->locales = $locales;
        $this->formFactory = $formFactory;
    }

    public function loadTranslations(){

        $files = $this->searchRecursively($this->rootDir.'/Resources');
        $files = array_merge($this->searchRecursively($this->rootDir.'/../src'),$files);


        //make translations trees
        foreach ($files as $file){
            preg_match('#\.([a-z]{2})\.'.$this->format.'$#',$file,$matches);

            $locale = $matches[1];
            if( in_array($locale,$this->locales))
            {

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

    private function writeTranslationsToFile($postedTree){
        $files = $this->searchRecursively($this->rootDir.'/Resources');
        $files = array_merge($this->searchRecursively($this->rootDir.'/../src'),$files);



        //make translations trees
        foreach ($files as $file) {
            preg_match('#\.([a-z]{2})\.' . $this->format . '$#', $file, $matches);

            $locale = $matches[1];
            if (in_array($locale, $this->locales)) {
                preg_match('#.+\/([a-zA-Z]+)\.[a-z]{2}\.'.$this->format.'$#',$file,$matches);
                $domain = $matches[1];
                $translations[$domain] = $this->formatTranslations(Yaml::parse($file),$locale);
                if (isset($postedTree[self::FORM_NAME][$domain])){
                    $modifiedTranslations = array_replace_recursive($translations[$domain],$postedTree[self::FORM_NAME][$domain]);
                    foreach ($modifiedTranslations as $modifiedTranslationKey => $modifiedTranslationValue){
                        if (!array_key_exists($modifiedTranslationKey,$translations[$domain])){

                            unset($modifiedTranslations[$modifiedTranslationKey]);
                        }
                    }

                    $modifiedTranslations = $this->unformatTranslations($modifiedTranslations,$locale);

                    file_put_contents($file,Yaml::dump($modifiedTranslations));
                }


            }

        }


        $this->loadTranslations();

    }

    public static function stringSanitizer($input,$decode = false){
        $search = [' ','(',')','?','!',"'",'"','.'];
        $replace = ['::e::','::opa::','::cpa::','::ipo::','::expo::','::sq::','::dq::','::p::'];
        if ($decode){
            return str_replace($replace,$search,$input);
        }
        else{
            return str_replace($search,$replace,$input);
        }

    }

    private function formatTranslations($translations,$locale){
        $tree = [];
        foreach ($translations as $translationKey => $translation){
            if (is_array($translation)){
                $tree[self::stringSanitizer($translationKey)] = $this->formatTranslations($translation,$locale);
            }
            else{
                $tree[self::stringSanitizer($translationKey)][$locale]= $translation;

            }
        }
        return $tree;
    }

    private function unformatTranslations($translations,$locale){
        $tree = [];
        foreach ($translations as $translationKey => $translation){
            if (is_array($translation)){
                $tree[self::stringSanitizer($translationKey,true)] = $this->unformatTranslations($translation,$locale);
            }
            else{
                $tree = $translation;

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



    public function getUntranslatedTree(){
        return $this->unTranslatedTree;
    }
    public function getTranslationTree(){
        return $this->translationTree;
    }

    public function getForm(){
        return $this->form;
    }
    public function createForm($path){

        $fb = $this->formFactory->createNamedBuilder(self::FORM_NAME,'form',$this->translationTree);
        $this->generateFormChildren($fb,$this->translationTree);
        $fb->add('submit','submit');

        $this->form = $fb->getForm();

        $array_path = explode('/--/',$path);
        $this->filterForm($this->form,$array_path);

        return $this;
    }

    private function filterForm(Form $form,$array_path,$level = 0){
        $formChildren = $form->all();
        foreach ($formChildren as $formChild){
            if ($formChild->getName() != $array_path[$level] && $formChild->getName() != '_token' && $formChild->getName() != 'submit'){
                $form->remove($formChild->getName());
            }
            else if (count($array_path)>$level+1 && $formChild->getName() != '_token' && $formChild->getName() != 'submit'){

                $this->filterForm($formChild,$array_path,$level+1);
            }
        }
    }

    public function bindForm(Request $request){
        $this->form->handleRequest($request);
        if ($this->form->isValid()){
            $this->writeTranslationsToFile($request->request->all());
            return true;
        }
        return false;
    }

    private function generateFormChildren($fb,$tree){
        foreach ($tree as $childKey => $childValue){
            if (is_array($childValue)){
                $virtual = $fb->create($childKey,'form',['label'=>self::stringSanitizer($childKey,true)]);
                $this->generateFormChildren($virtual,$childValue);
                $fb->add($virtual);
            }else{
                $fb->add($childKey,'textarea');
            }
        }

    }

}