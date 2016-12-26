<?php

// Ici on est chargé de vérifier que l'argument shotgunId qu'on nous fournit dans le get est bien exploitable.

/* Si je suis user simple, je ne peux voir que les shotguns dont la date de publi est dépassée, qui sont ouverts ET actifs.
 * 
 * Si je suis admin, je peux tout voir, mais j'ai assez d'infos pour m'y retrouver quand même.
 */


// Mais... pourquoi 2 fichiers ?
// Comme ça je ne fais pas un if-else démentiel et quitte à dupliquer du code commun, je m'y perds moins.

// Si je suis un admin
if(isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'])
{
    require('content_shotgunRecord_admin.php');
}

// Sinon comme j'ai déjà checké dans index.php que j'étais logué, c'est que je suis un prolétaire lambda (aka un user)
else
{
    // Vérifions que l'utilisateur peut le voir.
    require('content_shotgunRecord_user.php');
}
?>