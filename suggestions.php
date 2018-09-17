<?php
require_once "webpage.class.php";

$p = new WebPage('Suggestions');

$p->appendJsUrl('request.js');

$p->appendContent(<<<HTML
    <form name="f">
        partie du nom de l'artiste : <input id="name" type="text">
    </form>
    <span id="liste"></span>
HTML
);

$p->appendJs(<<<JAVASCRIPT
    // Fonction appelée au chargement complet de la page
    window.onload = function () {
        var rqAj;
        // Désactivation de l'envoi du formulaire
        document.forms['f'].onsubmit = function () { return false ; }

        // Fonction appelée lors d'une modification de la saisie
        document.forms['f'].elements['name'].onkeyup = function() {
            //console.log(document.forms['f'].elements['name'].value)
            // Création de la requête AJAX
            if(rqAj) rqAj.cancel();
            rqAj = new AjaxRequest(
                {
                    url        : "liste_artistes.php",
                    method     : 'get',
                    handleAs   : 'text',
                    parameters : { q : document.forms['f'].elements['name'].value, wait: true },
                    onSuccess  : function(res) {
                            document.getElementById('liste').innerHTML = res ;
                        },
                    onError    : function(status, message) {
                            window.alert('Error ' + status + ': ' + message) ;
                        }
                }) ;
        }
    }
JAVASCRIPT
);

echo $p->toHTML();
