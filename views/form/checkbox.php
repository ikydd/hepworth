<div id="<?php echo $id ?>-box" class="control-group<?php if($error) echo ' error';?>">
  <div class="controls">
    <label for="<?php echo $name; ?>" class="checkbox">
      <input id="<?php echo $id ?>" name="<?php echo $name; ?>" type="checkbox"<?php if($value) echo ' checked="checked"'; ?>>
      <?php echo $title; if($required) echo $this->show('form/required'); ?>
      <span class="help-block"><?php echo $description; ?></span>
    </label>
  </div>
</div>