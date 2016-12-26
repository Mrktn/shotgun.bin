<?php

// antoine.balestrat@polytechnique.edu -> antoine.balestrat
function stripTheMail($mail)
{
    return explode("@", $mail)[0];
}

function labelFromPercentage($r)
{
    if($r < 0.5)
        return "progress-bar-success";
    if($r < 0.8)
        return "progress-bar-warning";
    
    return "progress-bar-danger";
    
}
function generateProgressBar($k, $n)
{
    $perc = $k / (float)$n;

    return '
    <div class="progress" >
      <div class="progress-bar active ' . labelFromPercentage($perc) . '" role="progressbar" aria-valuenow="' . floor(100*$perc) . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . floor(100*$perc) . '%">
        <span><strong>' . $k . ' / ' . $n . '</strong></span>
      </div>
    </div>'; 
}

function isValidPolytechniqueEmail($mail)
{
    return preg_match("/.+@polytechnique\.edu/", $mail);
}


        
?>