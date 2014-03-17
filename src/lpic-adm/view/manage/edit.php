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
<div class="table" id="qna_form">
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
  <div class="line" id="a_<?php echo $a->a_id(); ?>">
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
      <button class="rm_answer" id="rm_answer_<?php echo $a->a_id(); ?>">Remove</button>
    </div>
  </div>
  <?php } ?>
</div>
<div class="table">
  <div class="cell">
    <button id="add_answer">Add answer</button>&nbsp;
    <input type="submit" value="Send" />
  </div>
</div>
</form>
<script type="text/javascript">
  function set_rm_action(button)
  {
    button.addEventListener('click', function(evt)
    {
      evt.preventDefault();
      var id = this.id.split('_')[2];
      document.getElementById('a_' + id).outerHTML = "";
    });
  }
  // Manage add/rm buttons
  document.getElementById('add_answer').addEventListener('click', function(evt)
  {
    evt.preventDefault();
    console.log(this);
    // Firstly, get all available answers
    var answers = document.querySelectorAll('#qna_form .line');
    var cnt = 0;
    for(i in answers)
      if(answers.item(i).id != "")
        cnt = answers.item(i).id.split('_')[1];
    cnt++;
    var new_an = '<div class="line" id="a_' + cnt + '">'
      + ' <div class="cell" style="width: 8%;">'
      + '   <input type="checkbox" name="a[new][' + cnt + '][is_right]" value="1" />&nbsp;Is valid'
      + '</div>'
      + '<div class="cell" style="width: 81%;">'
      + '  <input type="text" style="width: 99%;" name="a[new][' + cnt + '][txt]" value="" />'
      + '</div>'
      + '<div class="cell" style="width: 7%;">'
      + '  <button  class="rm_answer"id="rm_answer_' + cnt + '">Remove</button>'
      + '</div>'
      + '</div>';
    document.getElementById('qna_form').insertAdjacentHTML('beforeend', new_an);
    set_rm_action(document.getElementById('rm_answer_' + cnt));
  });
  var rms = document.querySelectorAll('.rm_answer');
  for(i in rms) set_rm_action(rms.item(i));
</script>

