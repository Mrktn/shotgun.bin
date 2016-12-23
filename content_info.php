<?php
echo '
<div class="alert alert-success">
  <strong>Succès</strong> ' . (isset($_GET['msg']) ? $_GET['msg'] : "Rien à afficher !") .
'</div>';
?>