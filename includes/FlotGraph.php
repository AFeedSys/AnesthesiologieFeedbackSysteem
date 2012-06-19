<?php
class FlotGraph {
    
    const basisOption = '
            grid: { hoverable: true, clickable: true},
            yaxis: { max: 100 },';
    
    public $titel;
    public $type;
    public $jsonset;
    public $holder;
    public $bescrijving;
    public $tooltip;
    public $updatesHolder; //holdernaam van de graph die deze graph bij interactie moet updaten.
    
    function __construct($titel, $type, $dataset, $holder, $beschrijving, $tooltip, $updatesHolder) {
        $this->titel = $titel;
        $this->type = $type;
        $this->jsonset = $dataset;
        $this->bescrijving = $beschrijving;
        $this->tooltip = $tooltip;
        $this->holder = $holder;
        $this->updatesHolder = $updatesHolder;
    }
    
    /*function __construct(){
        $this->titel = "Geen";
        $this->type = "Geen";
        $this->jsonset = "[[0,0]]";
        $this->bescrijving = "Standaard grafiek";
        $this->tooltip = "geen interactie mogelijk";
        $this->holder = "";
        $this->updatesHolder = null;
    }
    
    function __construct($titel, $type, $holder, $beschrijving, $tooltip, $updatesHolder){
        $this->titel = $titel;
        $this->type = $type;
        $this->bescrijving = $beschrijving;
        $this->tooltip = $tooltip;
        $this->holder = $holder;
        $this->updatesHolder = $updatesHolder;
        
        $this->jsonset = null;
    }*/
    
    public function getScript(){
        
    }
    
    public function getHolderHTML(){
        return '<div id="' . $toBePlaced->holder . '" class="' . $toBePlaced->holder . '></div>';
    }
    
    public function getScriptOptions(){
        return basisOption;
    }
}

?>
