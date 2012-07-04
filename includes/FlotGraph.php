<?php
class FlotGraph {
    // <editor-fold desc="constants (public statics)">
    const BASIS_OPTIES = '
        grid: {
            hoverable: true, 
            clickable: true,
            markings: markers,
            mouseActiveRadius: 100,
        },
        yaxis: {
            min: 0,
            max: 100,
        },
        ';
    
    const FLOT_REQUEST_URL = 'http://localhost/afeedsys/handlers/flotrequest.php';
    
    const PLOT_PREFIX = 'plot_';
    const DATA_PREFIX = 'data_';
    const VAR_PREFIX = 'var_';
    const OPTION_PREFIX = 'option_';
    const AJAX_PREFIX = 'ajax_';
    // </editor-fold>
    // <editor-fold desc="private Velden">
    private $dManager;
    
    private $titel; //titel weergegeven van grafiek
    private $type; //type bekend in includes/DataManager.php
    private $jsonSet; //JSONset voor referentie
    private $holder; //de naam class die gegenereert wordt (voor CSS-gebruik)
    private $bescrijving; //Beschrijving/Uitleg van grafiek
    private $tooltip; //Tooltip wanneer over een punt in de grafiek 'gehoverd' wordt
    private $updatesHolder; //holdernaam van de graph die deze graph bij interactie moet updaten.
    private $updateType; //type van de holder de deze grafiek update
    // </editor-fold>
    
    /**
     * Constructor
     * @param String $titel titel weergegeven van grafiek
     * @param DataManager::const $type type bekend in includes/DataManager.php
     * @param JSON $dataset JSONset voor referentie (wordt opgevraagd wanneer $dataset == '')
     * @param String $holder Beschrijving/Uitleg van grafiek
     * @param String $beschrijving Beschrijving/Uitleg van grafiek
     * @param String $tooltip Tooltip wanneer over een punt in de grafiek 'gehoverd' wordt
     * @param String $updatesHolder holdernaam van de graph die deze graph bij interactie moet updaten.
     * @param DataManager::const $updateType type van de holder de deze grafiek update
     */
    function __construct($titel, $type, $dataset, $holder, $beschrijving, $tooltip, $updatesHolder, $updateType) {
        $this->titel = $titel;
        $this->type = $type;
        $this->jsonSet = $dataset;
        $this->bescrijving = $beschrijving;
        $this->tooltip = $tooltip;
        $this->holder = $holder;
        $this->updatesHolder = $updatesHolder;
        $this->updateType = $updateType;
        
        $this->dManager = new DataManager();
        
        if($this->jsonSet == '' || is_null($this->jsonSet)){
            $this->jsonSet = $this->dManager->getJSONset($this->type, null);
        }
    }
    
    /**
     * Genereert HTML-element waarin grafiek wordt weergegeven
     * @return String div-element van grafiek
     */
    public function getHolderHTML(){
        return '
            <h3>' . $this->titel . '</h3> <span>' . $this->tooltip . '</span>
            <div id="' . $this->holder . '" class="graph"></div>';
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
    var ' . $this->getJSVarNaam(self::OPTION_PREFIX) . ' = {' . self::BASIS_OPTIES . '};';
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
        return '
    var ' . $this->getJSVarNaam(self::DATA_PREFIX) . ' = '. $this->jsonSet .';';
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
    var ' . $this->getJSVarNaam(self::PLOT_PREFIX) . ' = $.plot($("#' . $this->holder . '"), [' . $this->getJSVarNaam(self::DATA_PREFIX) . '], ' . $this->getJSVarNaam(self::OPTION_PREFIX) . ');';
    }
    
    /**
     * Genereert javaScript voor het gebruik het klikken van een plot en de interactie tussen plots.
     * @return String/JavaScript 
     */
    public function getBindScripts($beforeAjax='', $inAjax='', $afterAjax='', $customHoverMessage=''){
        
        $varName = $this->getJSVarNaam(self::AJAX_PREFIX);
        $updatePlot = self::PLOT_PREFIX . $this->updatesHolder;
        $updateSet = self::DATA_PREFIX . $this->updatesHolder;
        $updateOption = self::OPTION_PREFIX . $this->updatesHolder;
        $output = '
    var ' . $varName . ' = null;
    ' . $this->getJQuerySelector() . '.bind("plotclick", function (event, pos, item) {
        // Highlight geselecteerde items
        if (item) {
            '. $this->getJSVarNaam(self::PLOT_PREFIX) . '.unhighlight();
            '. $this->getJSVarNaam(self::PLOT_PREFIX) . '.highlight(item.series, item.datapoint);
            
            // Check of er al een ajax - verzoek bezig is.
            if(' . $varName . ') {
                if (' . $varName . '.readyState != 0){
                    ' . $varName . '.abort();
                }
            }
            
            //$("#' . $this->updatesHolder . '").hide("slow");
            
            // Extract value om verder te gebruiken.
            var clickValue = item.datapoint[0];
            '. $beforeAjax . '
            // Invoke Ajax
            ' . $varName . ' = $.ajax({
                url: "' . self::FLOT_REQUEST_URL . '",
                dataType: "json",
                method: "GET",
                data: {
                  target : "' . $this->updateType . '",
                  option : clickValue
                },
                error: function(xhr, textStatus, thrownError){
                    if(textStatus != "abort") {
                        alert(textStatus + "\n" + thrownError);
                        $("#message").html(xhr.responseText);
                    }
                },
                success: function ( response ) {
                    ' . $updateSet . '= [];
                    ' . $updateSet . '.push( response );
                    ' . $inAjax . '
                    ' . $updatePlot . ' = $.plot($("#' . $this->updatesHolder . '").show("slow"), ' . $updateSet . ', ' . $updateOption . ');
                     anoteGraphs();
                },
            });
            ' . $afterAjax . '
        }
    });
    ' . $this->getJQuerySelector() . '.bind("plothover", function (event, pos, item) {
            if (item) {
                if (previousPoint != item.dataIndex) {
                    previousPoint = item.dataIndex;
                    
                    
                    $(".tooltip").remove();
                    
                    var x = item.datapoint[0],
                        y = item.datapoint[1].toFixed(1);
                    
                    var dt = new Date(x);
                    
                    showTooltip(item.pageX, item.pageY,';
        if($customHoverMessage == '') {
            $output .= 'x + ": " + y';
        } else {
            $output .= $customHoverMessage;
        }
                                
                $output .= ')
                }
            } else {
                previousPoint = null;
            }
    });
    ';
                
    return $output;
}
    
    /**
     * Genereert de variabele-adres naar aanleiding van een gegeven prefix.
     * @param DataManager::const $prefix
     * @return String 
     */
    public function getJSVarNaam($prefix){
        return $prefix . $this->holder;
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
        return $this->jsonSet;
    }

    public function setJsonset($jsonset) {
        $this->jsonSet = $jsonset;
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
    
    public function getUpdateType() {
        return $this->updateType;
    }

    public function setUpdateType($updateType) {
        $this->updateType = $updateType;
    }

    public function getDManager() {
        return $this->dManager;
    }
    
    public function getDataSet(){
        return json_decode($this->jsonSet)->data;
    }
    
    public function getJQuerySelector($sub=''){
        return '$("#' . $this->holder . '"'.$sub.')';
    }
        // </editor-fold>
    
}

?>
