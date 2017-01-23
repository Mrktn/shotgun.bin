var barRefresher = function(){
    var bars = $(".progress-shotgun");

    bars.each(function(){
        $(this).load('progressbar.php?idShotgun=' + $(this).attr('idShotgun'));
    });
};

setInterval(barRefresher, 5000);