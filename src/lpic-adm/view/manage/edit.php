<h2>Edit</h2>
<?php
  if(!is_null($controller->data['q'])) { 
    $q = $controller->data['q'];
    $lvls = $controller->data['lvl'];
    $langs = $controller->data['lang'];
    $answers = $controller->data['a'];
  }
?>
<form method="POST" action="/manage/edit/<?php echo $q->q_id(); ?>">
<div class="table">
  <div class="line head"><div class="cell" style="width:99%">Question :</div></div>
  <div class="line">
    <div class="cell" style="height:3em">
      <textarea name="q[txt]" style="height: 3.4em;"><?php echo $q->q_txt(); ?></textarea>
    </div>
    <div class="cell" style="width:48%">
      Level: 
      <select name="q[lvl]">
        <?php //echo $lvl->le_name(); 
        foreach($lvls as $lvl)
        {
          echo '<option value="' . $lvl->le_id() . '"';
          if($lvl->le_id() == $q->q_lvl()) echo " selected";
          echo '>' . $lvl->le_name() . '</option>';
        }
        ?>
      </select>
    </div>
    <div class="cell" style="width:48%; border:none;">
      Language: 
      <select name="q[lang]">
      <?php //echo $lang->la_short();
      foreach($langs as $lang)
      {
          echo '<option value="' . $lang->la_id() . '"';
          if($lang->la_id() == $q->q_lang()) echo " selected";
          echo '>' . $lang->la_short() . '</option>';
      }
      ?>
      </select>
    </div>
  </div>
  <div class="line head"><div class="cell">Answers</div></div>
  <?php foreach($answers as $a) { ?>
  <div class="line">
    <div class="cell" style="width: 8%;">
      <input type="checkbox" name="a[<?php echo $a->a_id(); ?>][is_right]"
        value="1" <?php if($a->a_is_right() == 1) echo "checked"; ?>/>
      &nbsp;Is valid
    </div>
    <div class="cell" style="width: 81%;">
      <input type="text" style="width: 99%;" name="a[<?php echo $a->a_id(); ?>][txt]"
        value="<?php echo $a->a_txt(); ?>" />
    </div>
    <div class="cell" style="width: 7%;">
      <button id="rm_answer_<?php echo $a->a_id(); ?>">Remove</button>
    </div>
  </div>
  <?php } ?>
</div>
<div class="table">
  <div class="cell"><button id="add_answer">Add answer</button></div>
</div>
</form>
<script type="text/javascript">
// Manage add/rm buttons
</script>
