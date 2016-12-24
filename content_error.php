<?php
echo '
<div class="alert alert-danger">
  <strong>Erreur</strong> '. (isset($_GET['msg']) ? htmlentities($_GET['msg']) : "Une erreur est survenue !") .
'</div>';
?>