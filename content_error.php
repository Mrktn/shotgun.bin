<?php
echo '<div class="container">
<div class="alert alert-danger center-block">
  <strong>[Erreur]</strong> '. (isset($_GET['msg']) ? htmlentities($_GET['msg']) : "Une erreur est survenue !") .
'</div></div>';
?>