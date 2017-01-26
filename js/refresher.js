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
    var buttons = $("#buttonShotgun");
    
    buttons.each(function(){
        var idshot = $(this).attr('idShotgun');
        
        $.get("/shotgun.bin/api/shotgunable.php?idShotgun="+idshot, function(data) {
            if(data === '1')
            {
                buttons.removeAttr('disabled');
                buttons.attr('value', "Shoootgun !");
            }
            
            else
            {
                buttons.attr('disabled', 'disabled');
                buttons.attr('value', "Pas de place :(");
            }
        });
    });
};

setInterval(shotgunButtonRefresher, 5000);