<h2>Questions</h2>
<div class="table">
  <?php
  foreach($controller->data['questions'] as $q)
  {
    ?>
    <div class="line head">
      <div class="cell" style="text-align:left; height:2.2em;">
        <?php echo $q['q']->q_txt(); ?>
      </div>
    </div>
    <div class="line">
      <div class="cell" style="width:12.4%">
        <a href="/manage/edit/<?php echo $q['q']->q_id(); ?>"><button>Edit</button></a>
        <button class="delete-answer" id="delete_<?php echo $q['q']->q_id(); ?>">Delete</button>
      </div>
      <div class="cell" style="width:10%">Level : <?php echo $q['lvl']->le_name(); ?></div>
      <div class="cell" style="width:10%">
        Language : <?php echo $q['lan']->la_short(); ?>
      </div>
      <div class="cell" style="width:15%">
        <?php echo count($q['a']); ?> answers available.
      </div>
    </div>
    <?php
  }
  ?>
  <div class="line">
    <div class="cell"><a href="/manage/edit/0"><button>New</button></a></div>
  </div>
</div>
<script type="text/javascript">
  function question_delete()
  {
    button = this;
    //evt.preventDefault();
    var q_id = this.id.split('_')[1];
    if(confirm('Delete this question and its answers ?'))
    {
      get_server_data('/manage/delete', function(res)
      {
        button.removeEventListener('click', question_delete, false);
        setTimeout("button.addEventListener('click', question_delete)", 1000);
        // May be nothing to do :o
      }, [{k: 'q_id', v: q_id}]);
    }
  }
  
  var dels = document.querySelectorAll('.delete-answer');
  for(i in dels)
  {
    dels.item(i).addEventListener('click', question_delete); 
    /*dels.item(i).addEventListener('click', function(evt)
    {
      evt.preventDefault();
      var q_id = this.id.split('_')[1];
      get_server_data('manage/delete', function(res)
      {
        // May be nothing to do :o
      }, [{k: 'q_id', v: q_id}]);
    });*/
  }
</script>
