// Le code qui permet de rafraîchir les progressbars
var progressbarRefresher = function(){
    var bars = $(".progress-shotgun");

    bars.each(function(){
        $(this).load('/shotgun.bin/api/progressbar.php?idShotgun=' + $(this).attr('idShotgun'));
    });
};

setInterval(progressbarRefresher, 5000);


// Le code qui permet de supprimer / remplacer tout ce qui doit l'être quand un shotgun déborde
var shotgunButtonRefresher = function(){
    // Ça c'est le bouton "Shotgun !" sur la page shotgunRecord
    var buttonShotgun = $("#buttonShotgun");
    buttonShotgun.each(function(){
        var idshot = $(this).attr('idShotgun');
        
        $.get("/shotgun.bin/api/shotgunable.php?idShotgun="+idshot, function(data) {
            if(data === '1')
            {
                buttonShotgun.removeAttr('disabled');
                buttonShotgun.attr('value', "Shoootgun !");
            }
            
            else
            {
                buttonShotgun.attr('disabled', 'disabled');
                buttonShotgun.attr('value', "Pas de place :(");
            }
        });
    });
    
    // Celui-ci, c'est le bouton "Envoyer" de la page où on répond aux questions
    var sendShotgunButton = $("#sendShotgunButton");
    sendShotgunButton.each(function(){
        var idshot = $(this).attr('idShotgun');
        
        $.get("/shotgun.bin/api/shotgunable.php?idShotgun="+idshot, function(data) {
            
            if(data === '1')
            {
                sendShotgunButton.removeAttr('disabled');
                sendShotgunButton.html("Envoyer");
            }
            
            else
            {
                sendShotgunButton.attr('disabled', 'disabled');
                sendShotgunButton.html("Trop tard :'(");
            }
        });
    });
};

setInterval(shotgunButtonRefresher, 5000);