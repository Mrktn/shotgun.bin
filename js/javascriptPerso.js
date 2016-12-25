$(document).ready(function () {
    $("div.shotgunPanel").css('cursor', 'pointer');
    $("div.shotgunPanel").click(function () {
        window.location.href = "index.php?activePage=shotgunRecord&idShotgun=".concat($(this).attr('idShotgun'));
    });
});

function download_csv(dat) {
    var csv = 'Rang;Date shotgun;Mail\n';
    dat.forEach(function(row) {
            csv += row.join(';');
            csv += "\n";
    });
 
    console.log(csv);
    var hiddenElement = document.createElement('a');
    hiddenElement.href = 'data:text/csv;charset=utf-8,' + encodeURI(csv);
    hiddenElement.target = '_blank';
    hiddenElement.download = 'people.csv';
    hiddenElement.click();
}