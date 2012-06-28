<?php
class FlotGraph {
    // <editor-fold desc="constants (public statics)">
    const BASIS_OPTIES = '
        grid: { hoverable: true, clickable: true},
        yaxis: { max: 100 },';
    
    const PLOT_PREFIX = 'plot_';
    const DATA_PREFIX = 'data_';
    const VAR_PREFIX = 'var_';
    const OPTION_PREFIX = 'option_';
    // </editor-fold>
    // <editor-fold desc="private Velden">
    private $dManager;
    
    private $titel; //titel weergegeven van grafiek
    private $type; //type bekend in includes/DataManager.php
    private $jsonset; //JSONset voor referentie
    private $holder; //de naam class die gegenereert wordt (voor CSS-gebruik)
    private $bescrijving; //Beschrijving/Uitleg van grafiek
    private $tooltip; //Tooltip wanneer over een punt in de grafiek 'gehoverd' wordt
    private $updatesHolder; //holdernaam van de graph die deze graph bij interactie moet updaten.
    // </editor-fold>
    
    /**
     * Constructor
     * @param String $titel titel weergegeven van grafiek
     * @param DataManager::const $type type bekend in includes/DataManager.php
     * @param JSON $dataset JSONset voor referentie (wordt ge-update bij getDataScript() )
     * @param String $holder Beschrijving/Uitleg van grafiek
     * @param String $beschrijving Beschrijving/Uitleg van grafiek
     * @param String $tooltip Tooltip wanneer over een punt in de grafiek 'gehoverd' wordt
     * @param String $updatesHolder holdernaam van de graph die deze graph bij interactie moet updaten.
     */
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
    
    /**
     * Genereert HTML-element waarin grafiek wordt weergegeven
     * @return String div-element van grafiek
     */
    public function getHolderHTML(){
        return '<div id="' . $this->holder . '" class="' . $this->holder . '></div>';
    }
    
    /**
     * Genereert javaScript met optievariabele gedefineerd met opgegeven holder.
     * Voorbeeld:
     *  opgevenen holder: content
     *  gegenereerde var: option_content
     * @return String/JavaScript optievariabele 
     */
    public function getOptionScript(){
        return '
    var ' . self::OPTION_PREFIX . $this->holder . ' = {' . self::BASIS_OPTIES . '};';
    }
    
    /**
     * Genereert javaScript met dataset gedefineerd door opgegeven holder.
     * Type moet 
     * Voorbeeld:
     *  opgevenen holder: content
     *  gegenereerde var: data_content
     * @return String/JavaScript optievariabele 
     */
    public function getDataScript(){
        $this->jsonset = $this->dManager->getJSONset($this->type, null);
        return '
    var ' . self::DATA_PREFIX . $this->holder . ' = '. $this->jsonset .';';
    }
    
    /**
     * Genereert javaScript met optievariabele gedefineerd met opgegeven holder.
     * Voorbeeld:
     *  opgevenen holder: content
     *  gegenereerde var: plot_content
     * @return String/JavaScript optievariabele 
     */
    public function getPlotScript(){
        return '
    var ' . self::PLOT_PREFIX . $this->holder . ' = $.plot($("#' . $this->holder . '"), ' . self::DATA_PREFIX . $this->title . ',' . self::OPTION_PREFIX . $this->title . ');';
    }
    
    /**
     * Genereert javaScript voor het gebruik het klikken van een plot en de interactie tussen plots.
     * @return String/JavaScript 
     */
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
    
    // <editor-fold desc="Auto-generated code">
    /*
     * ******************************************
     *              Setters en Getters
     *                AUTO-GENERATED
     * ******************************************
     */
   
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
