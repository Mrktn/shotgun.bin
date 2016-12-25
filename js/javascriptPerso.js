$(document).ready(function () {
    $("div.shotgunPanel").css('cursor', 'pointer');
    $("div.shotgunPanel").click(function() {
        window.location.href = "index.php?activePage=shotgunRecord&idShotgun=".concat($(this).attr('idShotgun'));
    });
});