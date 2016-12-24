<?php
echo '
<div class="alert alert-success">
  <strong>Succès</strong> ' . (isset($_GET['msg']) ? htmlentities($_GET['msg']) : "Rien à afficher !") .
'</div>';
?>