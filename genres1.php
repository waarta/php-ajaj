<?php
require_once "webpage.class.php";
require_once "myPDO.include.php";

$p = new WebPage('listes');

$p->appendJsUrl('request.js');

$p->appendContent(<<<HTML
    <form name="f">
        <select name="genres" size="5">
        <option value="">Style...</option>
HTML
);

$pdo = myPDO::getInstance();
$stmt = $pdo->prepare(<<<SQL
    SELECT DISTINCT id,name
    FROM genre
    ORDER BY name
SQL
);
$stmt->execute();
$stmt->setFetchMode($pdo::FETCH_ASSOC);
$tab = $stmt->fetchAll();
//header('Content-Type: application/json');
//json_encode($tab);

for ($i = 0; $i < sizeof($tab); $i++) {
    $id = $tab[$i]['id'];
    $name = $tab[$i]['name'];
    $p->appendContent(<<<HTML
        <option value= '{$id}'>{$name}</option>
HTML
    );
}

$p->appendContent(<<<HTML
        </select>
        <select name="artists" size="5"> <option value="">Artistes...</option>  </select>
        <select name="albums" size="5">  <option value="">Albums...</option>  </select>
    </form>

    <div id="res"></div>
HTML
);

$p->appendJs(<<<JAVASCRIPT

    // Fonction appelée au chargement complet de la page
    window.onload = function () {
        var rqAj;
        var selectGenres = document.forms['f'].elements['genres'];
        var selectArtists = document.forms['f'].elements['artists'];
        var selectAlbums = document.forms['f'].elements['albums'];
        // Désactivation de l'envoi du formulaire
        document.forms['f'].onsubmit = function () { return false ; }

        viderSelect = function (sel) {
            var select = sel.options;
            var child1 = sel.options[0];
            while(sel.options[1]) {
                sel.removeChild(sel.options[1]);
            }
        }

        // Fonction appelée pour afficher les artistes
        selectGenres.onclick = function() {
            viderSelect(selectArtists);
            // Création de la requête AJAX
            if(rqAj) rqAj.cancel();
            rqAj = new AjaxRequest(
                {
                    url        : "artists.php",
                    method     : 'get',
                    handleAs   : 'json',
                    parameters : { q : selectGenres.value, wait: true },
                    onSuccess  : function(res) {
                        for(var i=0;i<res.length;i++)
                            selectArtists.options.add(new Option(res[i]['txt'], res[i]['id']));
                        },
                    onError    : function(status, message) {
                            window.alert('Error ' + status + ': ' + message) ;
                        }
                }) ;
        }
        // Fonction appelée pour afficher les albums
        selectArtists.onclick = function() {
            viderSelect(selectAlbums);
            // Création de la requête AJAX
            if(rqAj) rqAj.cancel();
            rqAj = new AjaxRequest(
                {
                    url        : "albums.php",
                    method     : 'get',
                    handleAs   : 'json',
                    parameters : { q : selectArtists.value, wait: true },
                    onSuccess  : function(res) {
                        console.log(res)
                        for(var i=0;i<res.length;i++)
                            selectAlbums.options.add(new Option(res[i]['txt'], res[i]['id']));
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
