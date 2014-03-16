<h2>Manage users</h2>
<div class="table">
<div class="line head">
  <div class="cell" style="width:30%;">Pseudo</div>
  <div class="cell" style="width:30%;">Is manager ?</div>
  <div class="cell" style="width:30%;">Reset Password</div>
  <div class="cell" style="width:5%;">&nbsp;</div>
</div>
<?php
  foreach($controller->data['users'] as $u)
  {
    ?>
    <div class="line">
      <div class="cell" style="width:30%;">
        <?php echo $u->u_pseudo(); ?>
      </div>
      <div class="cell" style="width:30%;">
        <input type="radio" id="is_not_manager" name="user[<?php echo $u->u_id(); ?>][is_manager]" value="0" 
        <?php if($u->u_is_manager() == 0) { echo "checked"; } ?> />
        &nbsp;<label for="is_not_manager">False</label>
        <input type="radio" id="is_manager" name="user[<?php echo $u->u_id(); ?>][is_manager]" value="1" 
        <?php if($u->u_is_manager() == 1) { echo "checked"; } ?> />
        &nbsp;<label for="is_manager">True</label>
      </div>
      <div class="cell" style="width:30%;">
        <input type="password" name="user[<?php echo $u->u_id(); ?>][pwd]" value="" />
      </div>
      <div class="cell" style="width:5%;"><input type="submit" value="save" /></div>
    </div>
    <?php
  }
?>
</div>
