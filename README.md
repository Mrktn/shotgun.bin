# shotgun.bin

TODO
----
* Protéger les pages contre les accès directs : on n'accède pas directement à content_register.php par exemple. FIX: vérifier qu'une variable qui n'existerait que dans index.php est bien set en haut des pages de contenu ?
* Changer complètement le système d'info / error. Passer ça dans des POSTs et ne pas rediriger vers une page dédiée à chaque fois, il suffit de l'ajouter en haut de la page courante.
* Tester que updateShotgun renvoie true partout où on l'utilise
* Mettre un message de confirmation inscription shotgun , création shotgun, suppression shotgun...
* [DONE] Coder la vérification de non dépassement des inscriptions... !
* [DONE] Changer la progressbar en quelque chose de joli (et qui ne divise pas par 0) quand les inscriptions sont illimitées !
* Partout où on fait un listing des shotguns, mettre des petites icônes pour dire si je suis le créateur ou si j'ai shotguné
* Juste avant l'insertion (après la form-submission) d'un nouveau shotgun, vérifier que ce qui doit être vérifié (la forme des adresses mails, le fait le thumbnail_url pointe vers une image etc...) soit correctement rempli. Par ailleurs, vérifier que les questions reçoivent bien un nombre suffisant de réponses.