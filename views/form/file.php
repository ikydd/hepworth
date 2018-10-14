<div id="<?php echo $id ?>-box" class="control-group<?php if($error) echo ' error';?>">
  <label class="control-label" for="<?php echo $name; ?>"><?php echo $title; if($required) echo $this->show('form/required'); ?></label>
  <div class="controls">
    <input id="<?php echo $id ?>" type="file" class="input-medium" name="<?php echo $name; ?>" id="<?php echo $id; ?>">
    <?php if($description): ?>
    <span class="help-block"><?php echo $description; ?></span>
    <?php endif; ?>
  </div>
</div>