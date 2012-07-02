<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PersonalCompareGraph
 *
 * @author Coen
 */
class PersonalCompareGraph extends FlotGraph {
    
    function __construct($titel, $type, $holder, $beschrijving, $tooltip, $updatesHolder) {
        parent::__construct($titel, $type, "", $holder, $beschrijving, $tooltip, $updatesHolder);
    }

}

?>
