<?php

/* Author: Gianluca Giacometti */

$labels = array();
$labels['forward'] = 'Továbbítás';
$labels['forwardforwards'] = 'Továbbítási címek (elválaszthatja "," vesszővel, ";" pontosvesszővel vagy új sorokba írva) \n Pl:: email@email.com,email2@email2.com\n Ha a továbbítás autoreply-vel kezdődő \ncímet tartalmaz \'autoreply\' ne módosítsa vagy törölje';
$labels['forwardkeepcopies'] = 'Másolat megtartása a helyi fiókba';

$messages = array();
$messages['forwardinvalidforwards'] = 'Nem valós e-mail cím';
$messages['forwardnovalidforwards'] = 'Nem valós továbbítási cím';
$messages['forwardsuccessfullysaved'] = 'A továbbítási cím sikeresen beállítva.';
$messages['forwarddriverconnecterror'] = 'Adatbázis csatlakozási probléma';
$messages['forwarddriverprocesserror'] = 'Adatbázis futtatási probléma';
$messages['forwardinternalerror'] = 'Adatbázis olvasási probléma';

?>
