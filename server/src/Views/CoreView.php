<?php

namespace MusicRating\Views;

class CoreView
{
    private $template;
    private $mainFolder;
    private $fileBaseExtension;

    public function __construct(?string $mainFolder = null, ?string $mainTemplate = null, ?string $fileBaseExtension = 'php') {
        
        $this->mainFolder = $mainFolder ?? dirname(__FILE__);
        $template = $mainTemplate !== null ? "{$mainTemplate}.{$fileBaseExtension}" : "_template.{$fileBaseExtension}";
        $this->fileBaseExtension = $fileBaseExtension;
        
        ob_start();
        include("{$this->mainFolder}/{$template}");
        $this->template = ob_get_clean();
    }

    public function render(?string $section, ?array $variables = null) {

        $this->setSectionInTemplate($section);

        if($variables) {
            $this->setValuesInHtml($variables);
        }
        return $this->template;
    }

    private function setValuesInHtml(array $variables) {
        foreach($variables as $key => $value) {
            $this->template =  str_replace("{{ {$key} }}", $value, $this->template);
        }
        $this->template =  str_replace("{{ @css }}", dirname(__DIR__, 1) . '/assets/main.css', $this->template);
    }

    private function setSectionInTemplate(string $sectionFileName) {

        ob_start();
        include("{$this->mainFolder}/{$sectionFileName}.{$this->fileBaseExtension}");
        $section = ob_get_clean();
        
        $this->template = str_replace("{{ @content }}", $section, $this->template);
                    
        if(substr_count($this->template, "@folder->") > 0) {

            $templateArr = explode("{{ @folder", $this->template);
    
            foreach($templateArr as $elementString) {
                 $folderSectionElements[] = $this->getStringBetween($elementString, "->", " }}");
            }
    
            array_shift($folderSectionElements);
            
            foreach($folderSectionElements as $folderSectionElement) {
                ob_start();
                include("{$this->mainFolder}/{$folderSectionElement}.{$this->fileBaseExtension}");
                $folderFiles[] = ob_get_clean();
            }

            foreach($folderFiles as $key => $value) {
                $this->template = str_replace("{{ @folder->{$folderSectionElements[$key]} }}", $value, $this->template);
            }
        }


    }

    private function getStringBetween($string, $start, $end){
        
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;

        return substr($string, $ini, $len);
    }
}