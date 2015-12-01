<?php

/* Author: Valentin Samir */

$labels = array();
$labels['forward'] = 'Faire suivre';
$labels['forwardforwards'] = 'Adresses de réexpédition :\n(séparées par des virgures, des points\nvirgules ou des nouvelles lines)';
$labels['forwardkeepcopies'] = 'Garder une copie du message original';

$messages = array();
$messages['forwardinvalidforwards'] = 'Adresse email non valide';
$messages['forwardnovalidforwards'] = 'Aucune adresse de réexpédition valide';
$messages['forwardsuccessfullysaved'] = 'Adresses de réexpédition sauvegardées avec succès';
$messages['forwarddriverconnecterror'] = 'Erreur de connexion à la base de donnée';
$messages['forwarddriverprocesserror'] = 'Erreur lors de la mise a jour de la base de donnée';
$messages['forwardinternalerror'] = 'Erreur lors de la lecture du pilote sql';

?>
