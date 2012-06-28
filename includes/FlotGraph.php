<?php
class FlotGraph {
    
    const BASIS_OPTIES = '
        grid: { hoverable: true, clickable: true},
        yaxis: { max: 100 },';
    
    const PLOT_PREFIX = 'plot_';
    const DATA_PREFIX = 'data_';
    const VAR_PREFIX = 'var_';
    const OPTION_PREFIX = 'option_';
    
    private $dManager;
    
    private $titel;
    private $type;
    private $jsonset;
    private $holder;
    private $bescrijving;
    private $tooltip;
    private $updatesHolder; //holdernaam van de graph die deze graph bij interactie moet updaten.

    function __construct($titel, $type, $dataset, $holder, $beschrijving, $tooltip, $updatesHolder) {
        $this->titel = $titel;
        $this->type = $type;
        $this->jsonset = $dataset;
        $this->bescrijving = $beschrijving;
        $this->tooltip = $tooltip;
        $this->holder = $holder;
        $this->updatesHolder = $updatesHolder;
        
        $this->dManager = new DataManager();
    }
    
    public function getHolderHTML(){
        return '<div id="' . $this->holder . '" class="' . $this->holder . '></div>';
    }
    
    public function getOptionScript(){
        return '
    var ' . self::OPTION_PREFIX . $this->titel . ' = {' . self::BASIS_OPTIES . '};';
    }
    
    public function getDataScript(){
        $this->jsonset = $this->dManager->getJSONset($this->type, null);
        return '
    var ' . self::DATA_PREFIX . $this->titel . ' = '. $this->jsonset .';';
    }
    
    public function getPlotScript(){
        return '
    var ' . self::PLOT_PREFIX . $this->titel . ' = $.plot($("#' . $this->holder . '"), ' . self::DATA_PREFIX . $this->title . ',' . self::OPTION_PREFIX . $this->title . ');';
    }
    
    public function getBindScript(){
        return '
    $("#' . $this->holder . '").bind("plotclick", function (event, pos, item) {
        plot_' . $this->holder . '.unhighlight();
        if (item) {
            alert("You clicked point " + item.datapoint + " in " + item.series.label + ".");
            plot.highlight(item.series, item.datapoint);
        };
    });
    ';
    }
    
    /*
     * ******************************************
     *              Setters en Getters
     *                AUTO-GENERATED
     * ******************************************
     */
    
    // <editor-fold desc="Auto-generated code">
    public function getTitel() {
        return $this->titel;
    }

    public function setTitel($titel) {
        $this->titel = $titel;
    }

    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function getJsonset() {
        return $this->jsonset;
    }

    public function setJsonset($jsonset) {
        $this->jsonset = $jsonset;
    }

    public function getHolder() {
        return $this->holder;
    }

    public function setHolder($holder) {
        $this->holder = $holder;
    }

    public function getBescrijving() {
        return $this->bescrijving;
    }

    public function setBescrijving($bescrijving) {
        $this->bescrijving = $bescrijving;
    }

    public function getTooltip() {
        return $this->tooltip;
    }

    public function setTooltip($tooltip) {
        $this->tooltip = $tooltip;
    }

    public function getUpdatesHolder() {
        return $this->updatesHolder;
    }

    public function setUpdatesHolder($updatesHolder) {
        $this->updatesHolder = $updatesHolder;
    }
    // </editor-fold>
    
}

?>
