# shotgun.bin

TODO
----
* Protéger les pages contre les accès directs : on n'accède pas directement à content_register.php par exemple. FIX: vérifier qu'une variable qui n'existerait que dans index.php est bien set en haut des pages de contenu ?
* Changer complètement le système d'info / error. Passer ça dans des POSTs et ne pas rediriger vers une page dédiée à chaque fois, il suffit de l'ajouter en haut de la page courante.
* Tester que updateShotgun renvoie true partout où on l'utilise
* Coder la vérification de non dépassement des inscriptions... !
* Mettre un message de confirmation inscription shotgun , création shotgun, suppression shotgun...
* [DONE] Coder la vérification de non dépassement des inscriptions... !
* [DONE] Changer la progressbar en quelque chose de joli (et qui ne divise pas par 0) quand les inscriptions sont illimitées !
* couleur possibles titre #2a679e #13395b
*echo "<div class ='container titlepage' > <h1>Shotguns postés</h1><hr />
 </div>"; Pour comparaison de style