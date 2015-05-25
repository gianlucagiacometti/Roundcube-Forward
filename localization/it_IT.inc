<?php

/* Author: Gianluca Giacometti */

$labels = array();
$labels['forward'] = 'Inoltro';
$labels['forwardforwards'] = 'Indirizzi di inoltro (uno per riga o separati da ; oppure ,)\nNel caso sia impostato un autorisponditore comparirà\nautomaticamente un indirizzo contenente \'autoreply\'\nche si prega di non modificare o cancellare';
$labels['forwardkeepcopies'] = 'Mantieni copia dei messaggi originali';

$messages = array();
$messages['forwardinvalidforwards'] = 'Indirizzo e-mail non valido';
$messages['forwardnovalidforwards'] = 'Nessun indirizzo di inoltro valido';
$messages['forwardsuccessfullysaved'] = 'Indirizzi di inoltro salvati con successo';
$messages['forwarddriverconnecterror'] = 'Errore di connessione del driver';
$messages['forwarddriverprocesserror'] = 'Errore di esecuzione del driver';
$messages['forwardinternalerror'] = 'Errore di lettura del driver';

?>
