<?php
if (isset($_POST['q'])) {
    echo "Reçu '{$_POST['q']}' en POST, contenu retourné en texte";
    return;
}

if (isset($_GET['q'])) {
    echo "Reçu '{$_GET['q']}' en GET, contenu retourné en texte";
    return;
}

if (isset($_GET['json'])) {
    header('Content-Type: application/json');
    echo json_encode(array('value' => "Reçu '{$_GET['json']}' en GET, contenu retourné en JSON"));
    return;
}

if (isset($_GET['xml'])) {
    header('Content-Type: text/xml');
    echo <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<racine>
        <result>Reçu '{$_GET['xml']}' en GET, contenu retourné en XML</result>
</racine>
XML;
    return;
}

if (isset($_GET['error'])) {
    header('HTTP/1.1 400 Bad Request', true, 400);
    echo "Code erreur OK après réception de '{$_GET['error']}'";
    return;
}

require_once "webpage.class.php";

$p = new WebPage('Suggestions de noms en AJAX');

$p->appendCss(<<<CSS
body {
    background-color : white ;
    min-height : 230px;
}
CSS
);

$p->appendJsUrl('request.js');

$p->appendJs(<<<JAVASCRIPT
    window.onload = function () {
        // Création de la requête AJAX
        new AjaxRequest(
            {
                url        : "{$_SERVER['PHP_SELF']}",
                method     : 'post',
                handleAs   : 'text',
                parameters : { q : 'test&a=12' },
                onSuccess  : function(res) {
                        var li = document.createElement('li') ;
                        li.appendChild(document.createTextNode(res)) ;
                        document.getElementById('test_results').appendChild(li) ;
                    },
                onError    : function(status, message) {
                        window.alert('Error ' + status + ': ' + message) ;
                    }
            }) ;
        // Création de la requête AJAX
        new AjaxRequest(
            {
                url        : "{$_SERVER['PHP_SELF']}",
                method     : 'get',
                handleAs   : 'text',
                parameters : { q : 'test&a=12' },
                onSuccess  : function(res) {
                        var li = document.createElement('li') ;
                        li.appendChild(document.createTextNode(res)) ;
                        document.getElementById('test_results').appendChild(li) ;
                    },
                onError    : function(status, message) {
                        window.alert('Error ' + status + ': ' + message) ;
                    }
            }) ;
        // Création de la requête AJAX
        new AjaxRequest(
            {
                url        : "{$_SERVER['PHP_SELF']}",
                method     : 'get',
                handleAs   : 'json',
                parameters : { 'json' : 'json' },
                onSuccess  : function(res) {
                        var li = document.createElement('li') ;
                        li.appendChild(document.createTextNode(res.value)) ;
                        document.getElementById('test_results').appendChild(li) ;
                    },
                onError    : function(status, message) {
                        window.alert('Error ' + status + ': ' + message) ;
                    }
            }) ;
        // Création de la requête AJAX
        new AjaxRequest(
            {
                url        : "{$_SERVER['PHP_SELF']}",
                method     : 'get',
                handleAs   : 'xml',
                parameters : { 'xml' : 'xml' },
                onSuccess  : function(res) {
                        var li = document.createElement('li') ;
                        li.appendChild(document.createTextNode(res.getElementsByTagName('result')[0].firstChild.nodeValue)) ;
                        document.getElementById('test_results').appendChild(li) ;
                    },
                onError    : function(status, message) {
                        window.alert('Error ' + status + ': ' + message) ;
                    }
            }) ;
        // Création de la requête AJAX
        new AjaxRequest(
            {
                url        : "{$_SERVER['PHP_SELF']}",
                method     : 'get',
                handleAs   : 'text',
                parameters : { error : 'test&a=12' },
                onSuccess  : function(res) {
                    },
                onError    : function(status, message) {
                        var li = document.createElement('li') ;
                        li.appendChild(document.createTextNode('Erreur ' + status + ': ' + message)) ;
                        document.getElementById('test_results').appendChild(li) ;
                    }
            }) ;
    }
JAVASCRIPT
);

$p->appendContent(<<<HTML
<h1>Résultats des tests automatiques</h1>
<ul id='test_results'></ul>
HTML
);

echo $p->toHTML();
