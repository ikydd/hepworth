<div id="<?php echo $id ?>-box" class="control-group<?php if($error) echo ' error';?>">
  <label class="control-label" for="<?php echo $name; ?>"><?php echo $title; if($required) echo $this->show('form/required'); ?></label>
  <div class="controls">
    <textarea id="<?php echo $id ?>" class="input-xxlarge" rows="<?php echo $rows; ?>" cols="<?php echo $cols; ?>" name="<?php echo $name; ?>" id="<?php echo $id; ?>" placeholder="<?php echo $placeholder; ?>"><?php echo $value; ?></textarea>
    <?php if($description): ?>
    <span class="help-block"><?php echo $description; ?></span>
    <?php endif; ?>
  </div>
</div>