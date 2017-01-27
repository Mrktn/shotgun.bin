// n est le numéro de la question cette fonction génère le script HTML associée à la n-eme question
function htmlQ(n) {
    return ('<div id="question' + n + '" class="fontgrisContour text-center">'
            +'<br/>'
            +'<p class = "titreQuestion" id = "titreQ'+n+'"> Question n°'+n+'</p>'
            +'<img src="http://www.levivat.net/media/cache/vivat_thumbnail_view/media/vivat/12-croix-blanche-fond-noir-1.jpg" alt="SupprimerQuestion" class="enleve_boutonQ tailleQuestion" > '
            +'<br/>'
            + '<textarea class="form-control" name = "intitule[]" placeholder="Poser votre question"></textarea> <br/>'
            + '<div class="form-group" id="type_reponse' + n + '">'
            + "<label for='typeReponse"+n+"' class=' control-label'>Quel type de réponse attendez-vous?</label>"
            + '<div class="col-sm-12" id="Choix_Multiple_REPU' + n + '">'
            + '<input type="radio" id = "run' + n + '" name="typeReponse'+n+'" value= 1 ' + "onclick='$(" + '"#choix' + n + '").show();' + "'  required>   Choix multiple à une réponse <br/>"
            + '</div>'
            + '<div class="col-sm-12" id="Choix_Multiple_REPM' + n + '">'
            + '<input type="radio" id ="rdeux' + n + '" name="typeReponse'+n+'" value= 0 ' + "onclick='$(" + '"#choix' + n + '").show();' + "' >   Choix multiple à plusieurs réponses"
            + '</div>'
            + '<div class="col-sm-12" id="Reponse_libre' + n + '">'
            + '<input type="radio"  id ="rtrois' + n + '" name="typeReponse'+n+'" value= 2 ' + "onclick='$(" + '"#choix' + n + '").hide();' + "'  >   Réponse libre"
            + '</div>'
            + '<div class="form-group cache choix input_fields_wrap input_fields_wrap' + n + '" id ="choix' + n + '" style="display: none;">' // choixn refere ici à la question n
            + "<input type='button' id='ajouteChoix" + n + "' value='Ajouter un choix' class='btn btn-default ajout_bouton ' onclick='(ajout(this.id))'/> "
            + "<br/>"
            + '<div id ="question'+n+'Choix1">'
            + '<input type="text"  name="qcmrep' + n + '[]" placeholder="Choix 1"> <img src="http://www.invitedemarc.com/wp-content/uploads/2013/01/path_choices-300x225.jpg" alt="Decoration" class="tailledec" > '
            + '</div>'
            + '<div id ="question'+n+'Choix2">'
            + '<input type="text"  name="qcmrep' + n + '[]" placeholder="Choix 2"> <img src="http://www.invitedemarc.com/wp-content/uploads/2013/01/path_choices-300x225.jpg" alt="Decoration" class="tailledec" >' 
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

    //Ceci sert à traiter l'ajout/ retrait de questions
    var wrapperQ = $(".input_fields_wrapQ"); //Fields wrapper
    var ajout_boutonQ = $(".ajout_boutonQ"); //Ajoute bouton ID
    var xQ = 0; //Nombre de questions initial
    $(ajout_boutonQ).click(function (e) { //Si on clique sur un ajout_bouton
        e.preventDefault(); // empeche l'action par défaut du bouton
        if (xQ < max_fields) { //vérifie qu'on a le droit de rajouter un champ
            xQ++; //text box increment
            $(wrapperQ).append(htmlQ(xQ)); //Ajoute le HTML associé à la question xQ
            $(wrapperQ).append("<br/>");
        }
    });
    $(wrapperQ).on("click", ".enleve_boutonQ", function (e) { //Bouton de suppression de question
        e.preventDefault();
        var numQ = parseInt($(this).parent('div').attr('id').match(/\d+/));// Savoir quelle question on retire
        $(this).parent('div').remove();
        // Actualisation des id
        for ( var i = (numQ + 1); i< (xQ+2); i++){ // Traitons la question numéro i s'il elle n'existe pas, rien n'est fait
                if ((typeof ($('#question'+i).attr('id'))) !== 'undefined'){
                refresh_attr('run'+i, 'name',i-1); // Actualiser les radios
                refresh_attr('run'+i, 'onclick',i-1);
                refresh_attr('run'+i, 'id',i-1);
 
                refresh_attr('rdeux'+i, 'name',i-1); 
                refresh_attr('rdeux'+i, 'onclick',i-1);
                refresh_attr('rdeux'+i, 'id',i-1);
            
                refresh_attr('rtrois'+i, 'name',i-1);
                refresh_attr('rtrois'+i, 'onclick',i-1);
                refresh_attr('rtrois'+i, 'id',i-1);
                
                refresh_attr('choix'+i,'class',i-1);
            
                refresh_attr($('#choix'+i).children('input').attr("id"),'id',i-1);
                
                $('#choix'+i).children('div').each(function(){ // actualiser les numéros de question dans les choix
                    $(this).children('input').attr('name',$(this).children('input').attr('name').replace(/[0-9]+/gi,i-1));      
                    $(this).attr("id",$(this).attr("id").replace(/[0-9]+(?=Choix)/gi,i-1)); // On renomme la question sans toucher au numéro du choix
            }); // Actualiser tous les choix
                        
            $('#type_reponse'+i).children('div').each(function(){ // Actualiser le reste
                refresh_attr($(this).attr("id"),"id",i-1);
            });
            refresh_attr('type_reponse'+i,'id',i-1);
            $('#titreQ'+i).replaceWith('<p class = "titreQuestion" id = "titreQ'+(i-1)+'"> Question n°'+(i-1)+'</p>');
            refresh_attr('question'+i,'id',i-1);
        }}
        xQ--; // Il faut actualiser le nombre de choix pour chaque question
        for (var k = (numQ-1); k <9 ; k++) {
            x[k] = x[k+1];
        }
        x[9] = 0;
    });
    //
});

function ajout(s) {
    var n = s.match(/\d+/);  // On récupère le numéro de la question
    if (x[n] < max_fields) { //vérifie qu'on a le droit de rajouter un champ
        x[n]++; //text box increment
        $($(wrapper + n)).append('<div id="question'+n+'Choix' +x[n]+ '"><input type="text" name="qcmrep' + n + '[]" placeholder="Choix ' + x[n] + ' "/><img src="http://t2.gstatic.com/images?q=tbn:ANd9GcRvyAqQ5-XKMHWROUQ120PRMzIHW3uTj_ixh_3qHdZobwiTmo6Y-VI6chA" alt="Supprimer" class="enleve_bouton taille" onclick="suppr(this.id,this)" id="suppr'+x[n]+'"></div>');  
    }
}

function suppr(s,p) {
    var nChoix = s.match(/\d+/); // Numéro du choix
    var nQuestion = $(p).parent('div').parent('div').attr('id').match(/\d+/); // Numéro de la question
    $(p).parent('div').remove();
    //alert (typeof nQuestion);
    var choix = parseInt(nChoix); // nChoix est de type str
    //alert(p);
    x[nQuestion]--;
    //On test si le choix i existe le cas échéant on le décrémente, actualisation des numéros de choix
    for ( var i = (choix+1); i < (11); i++) {
        var choixi = $('#question'+nQuestion+'Choix'+i);
        if (typeof choixi.attr('id') !== 'undefined'){
            $(choixi).children('.enleve_bouton').attr("id","suppr"+(i-1));
            $(choixi).children('input').attr("placeholder","Choix "+(i-1));
            $(choixi).attr("id","question"+nQuestion+"Choix"+(i-1));
    }}
}


function refresh_attr(id,attribut,i) { // Modifie un attribut numéroté d'un objet en changeant son numéro
    reg = /[0-9]+/gi;
    change_attr(id,attribut,$('#'+id).attr(attribut).replace(reg,i));
}



function change_attr(id,attribut,newAttribut) { // Change l'attribut d'un objet donné par son id
    $('#'+id).attr(attribut,newAttribut);
}