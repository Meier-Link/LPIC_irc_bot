<h2>Administration</h2>
<form method="POST" action="">
  <input style="width:99.4%;" type="text" name="query"
    value="<?php if(isset($_POST['query'])) { echo $_POST['query']; } ?>" />
  <input type="submit" value="Send query" /><br/>
  Result: <?php
    if (isset($controller->data['res']))
    {
      $res = $controller->data['res'];
      echo '<div class="table">';
      $w = 0;
      for($i = 0; $i < count($res); $i++)
      {
        echo '<div class="line">';
        if ($i == 0)
        {
          $keys = array_keys($res[$i]);
          $w = floor(99 / (count($keys) + 1)) - 1;
          echo '<div class="cell" style="width:' . $w . '%">&nbsp;</div>';
          foreach($keys as $k)
          {
            echo '<div class="cell head" style="width:' . $w . '%">' . $k . '</div>';
          }
          echo '</div><div class="line">';
        }
        echo '<div class="cell" style="width:' . $w . '%">Ligne n° ' . $i . '</div>';
        foreach($res[$i] as $v)
        {
          echo '<div class="cell" style="width:' . $w . '%">';
          if ($v != '') echo $v;
          else          echo "&nbsp;";
          echo '</div>';
        }
        //echo "<br/>";
        echo "</div>";
      }
      echo "</div>";
    }
    
  ?>
</form>
