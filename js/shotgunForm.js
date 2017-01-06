// n est le numéro de la question cette fonction génère le script HTML associée à la j-eme question
function htmlQ(n) {
    return ('<div class="question' + n + '">'
            + '<textarea class="form-control" name = "questions' + n + '[]" placeholder="Poser votre question"></textarea> <img src="http://t2.gstatic.com/images?q=tbn:ANd9GcRvyAqQ5-XKMHWROUQ120PRMzIHW3uTj_ixh_3qHdZobwiTmo6Y-VI6chA" alt="Supprimer" class="enleve_boutonQ taille" > <br/>'
            + '<div class="form-group" id="type_reponse' + n + '">'
            + "<label for='typeReponse' class='col-sm-2 control-label'>Quel type de réponse attendez-vous?</label>"
            + '<div class="col-sm-10" id="Choix_Multiple_REPU' + n + '">'
            + '<input type="radio" id = "run' + n + '" name="typeReponse" value="Choix multiple à une réponse" ' + "onclick='$(" + '"#choix' + n + '").show();' + "'  required>   Choix multiple à une réponse <br/>"
            + '</div>'
            + '<div class="col-sm-10" id="Choix_Multiple_REPM' + n + '">'
            + '<input type="radio"   id ="rdeux' + n + '" name="typeReponse" value="Choix multiple à plusieurs réponses" ' + "onclick='$(" + '"#choix' + n + '").show();' + "' >   Choix multiple à plusieurs réponses"
            + '</div>'
            + '<div class="col-sm-10" id="Reponse_libre' + n + '">'
            + '<input type="radio" id ="rtrois' + n + '" name="typeReponse" value="Réponse libre" ' + "onclick='$(" + '"#choix' + n + '").hide();' + "'  >   Réponse libre"
            + '</div>'
            + '<div class="form-group cache choix input_fields_wrap input_fields_wrap' + n + '" id ="choix' + n + '">' // choixn refere ici à la question n
            + "<input type='button' id='ajouteChoix" + n + "' value='Ajouter un choix' class='btn btn-default ajout_bouton ' onclick='(ajout(this.id))'/> "
            + "<div>"
            + "<br/>"
            + '<input type="text" name="qcmrep' + n + '[]" placeholder="Choix 1"> '
            + '<br/>'
            + '</div>'
            + '<div>'
            + '<br/>'
            + '<input type="text" name="qcmrep' + n + '[]" placeholder="Choix 2">'
            + '<br/>'
            + '</div>'
            + '</div>'
            + '</div>'
            + '</div>');
}
;
var max_fields = 10; //Nombre de champs maximum
var wrapper = ".input_fields_wrap"; //Fields wrapper
var ajout_bouton = $(".ajout_bouton"); //Ajoute bouton ID
var enleve_bouton = $(".enleve_bouton"); // Enleve Bouton
var x = [2, 2, 2, 2, 2, 2, 2, 2, 2, 2]; //Nombre de champs initiaux x[i] donne le nombre de choix pour la question i

$(document).ready(function () {
    // On cache tout ce qui doit être caché à mettre dans un tableau des variables à cacher...
    $(".cache").hide();
    // On ajoute le bouton de question
    //$("#question").append("<input type='button' id='ajouteQuestion' value='Ajouter une question' class='btn btn-default' /><br/>");
    //$("#r1").click('$("#choix").show();');
    //$("#r2").click('$("#choix").show();');
    //$("#r3").click('$("#choix").hide();');

    // Ceci sert a gérer les ajout/ retrait de choix pour une question donnée
    //var max_fields = 10; //Nombre de champs maximum
    //var wrapper = ".input_fields_wrap"; //Fields wrapper
    //var ajout_bouton = $(".ajout_bouton"); //Ajoute bouton ID
    //var enleve_bouton = $(".enleve_bouton"); // Enleve Bouton
    //var x = [2, 2, 2, 2, 2, 2, 2, 2, 2, 2]; //Nombre de champs initiaux
    //$(ajout_bouton).click(function (e) { //Si on clique sur un ajout_bouton
    // var n = this.id.match(/\d+/);                   // On choppe son numéro
    //e.preventDefault(); // empeche l'action par défaut du bouton
    //if (x[n] < max_fields) { //vérifie qu'on a le droit de rajouter un champ
    //  x[n]++; //text box increment
    //  $($(wrapper + n)).append('<div><input type="text" name="qcmrep[]" placeholder="Choix ' + x[n] + ' "/><img src="http://t2.gstatic.com/images?q=tbn:ANd9GcRvyAqQ5-XKMHWROUQ120PRMzIHW3uTj_ixh_3qHdZobwiTmo6Y-VI6chA" alt="Supprimer" class="enleve_bouton taille"></div>'); //ajoute un champ <input type="text" name="qcmrepU[]" placeholder="Choix2">
    //}
    //});
    //$($(wrapper)).on("click", ".enleve_bouton", function (e) {//user click on remove text
       // var n = this.id.match(/\d+/);
        //e.preventDefault();
       // $(this).parent('div').remove();
      //  x[n]--;
    //});
    //

    //Ceci sert à traiter l'ajout/ retrait de questions
    var wrapperQ = $(".input_fields_wrapQ"); //Fields wrapper
    var ajout_boutonQ = $(".ajout_boutonQ"); //Ajoute bouton ID
    var enleve_boutonQ = $(".enleve_boutonQ"); // Enleve Bouton
    var xQ = 0; //Nombre de questions initial
    $(ajout_boutonQ).click(function (e) { //Si on clique sur un ajout_bouton
        e.preventDefault(); // empeche l'action par défaut du bouton
        if (xQ < max_fields) { //vérifie qu'on a le droit de rajouter un champ
            xQ++; //text box increment
            $(wrapperQ).append(htmlQ(xQ)); //Ajoute le HTML associé à la question xQ
        }
    });
    $(wrapperQ).on("click", ".enleve_boutonQ", function (e) { //user click on remove text
        e.preventDefault();
            alert("Avant suppression: "+ xQ);
        $(this).parent('div').remove();
        xQ--;
    });
    //
});
// je vais cliquer sur un ajout_bouton je dois chopper le numéro de référence : 2classes ajout_bouton et ajout_bouton_n

function ajout(s) {
    var n = s.match(/\d+/);  // On choppe le numéro de la question
    alert("Avant ajout Choix: "+ n + " " + x[n] );
    if (x[n] < max_fields) { //vérifie qu'on a le droit de rajouter un champ
        x[n]++; //text box increment
        $($(wrapper + n)).append('<div id="choixNQAsNOMMER"><input type="text" name="qcmrep[]" placeholder="Choix ' + x[n] + ' "/><img src="http://t2.gstatic.com/images?q=tbn:ANd9GcRvyAqQ5-XKMHWROUQ120PRMzIHW3uTj_ixh_3qHdZobwiTmo6Y-VI6chA" alt="Supprimer" class="enleve_bouton taille" onclick="suppr(this.id,this)" id="suppr'+x[n]+'"></div>');  
    }
    alert("Après ajout: "+ n + " " + x[n]);
}

function suppr(s,p) {
    var nChoix = s.match(/\d+/); // Numéro du choix
    var nQuestion = $(p).parent('div').parent('div').attr('id').match(/\d+/); // Numéro de la question
    alert("Avant suppression: Question: "+ nQuestion + "Nombre de Choix : " + x[nQuestion] + "Choix n: "+nChoix);
    $(p).parent('div').remove();
    alert(p);
    x[nQuestion]--;
    alert("Après suppression: "+ nQuestion + " " + x[nQuestion] + nChoix);    
}
