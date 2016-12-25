<?php

echo <<<END
<div class="container">
<table id="oklm" class="table-fill">
  <thead>
    <tr>
      <th>Name</th>
      <th>Hobby</th>
      <th>Favorite Music</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>Fred</td>
      <td>Roller Skating</td>
      <td>Disco</td>
    </tr>
    <tr>
      <td>Helen</td>
      <td>Rock Climbing</td>
      <td>Alternative</td>
    </tr>
    <tr>
      <td>Glen</td>
      <td>Traveling</td>
      <td>Classical</td>
    </tr>
  </tbody>
</table></div>

<script type="text/javascript">$('#oklm').dynatable();</script> 
END;
