<h2>Questions</h2>
<div class="table">
  <?php
  foreach($controller->data['questions'] as $q)
  {
    ?>
    <div class="line head">
     <div class="cell" style="text-align:left;"><?php echo $q['q']->q_txt(); ?></div>
    </div>
    <div class="line">
      <div class="cell" style="width:7%"><button>Edit</button></div>
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
</div>
