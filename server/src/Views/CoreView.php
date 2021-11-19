<?php

namespace MusicRating\Views;

class CoreView
{
    private $template;
    private $mainFolder;
    private $fileBaseExtension;

    public function __construct(?string $mainFolder = null, ?string $mainTemplate = null, ?string $fileBaseExtension = 'php') {
        
        $this->mainFolder = $mainFolder ?? dirname(__FILE__);
        $template = $mainTemplate !== null ? $mainTemplate : '/_template.php';
        $this->fileBaseExtension = $fileBaseExtension;
        
        $this->template = file_get_contents("{$this->mainFolder}{$template}");

    }

    public function render(?string $section, ?array $variables = null) {

        $this->setSectionInTemplate($section);

        if($variables) {
            $this->setValuesInHtml($section, $variables);
        }
        
        return $this->template;
    }

    private function setValuesInHtml(string $section, array $variables) {
        foreach($variables as $key => $value) {
            $this->template =  str_replace("{{ {$key} }}", $value, $this->template);
        }
    }

    private function setSectionInTemplate(string $sectionFileName) {

        $section = file_get_contents("{$this->mainFolder}/{$sectionFileName}.{$this->fileBaseExtension}");
        $this->template = str_replace("{{ @content }}", $section, $this->template);

    }
}