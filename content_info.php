<?php
echo '<div class="container">
<div class="alert alert-success center-block">
  <strong>[Succès]</strong> '. (isset($_GET['msg']) ? htmlentities($_GET['msg']) : "Opération réalisée avec succès !") .
'</div></div>';
?>