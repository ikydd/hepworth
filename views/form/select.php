<div id="<?php echo $id ?>-box" class="control-group<?php if($error) echo ' error';?>">
  <label class="control-label" for="<?php echo $name; ?>"><?php echo $title; if($required) echo $this->show('form/required'); ?></label>
  <div class="controls">
    <select id="<?php echo $id ?>" class="input-medium" name="<?php echo $name; ?>" id="<?php echo $id; ?>">
    <?php foreach($options as $val => $name): ?>
      <option value="<?php echo $val; ?>"<?php if($value == $val) echo ' selected="selected"'; ?>><?php echo $name; ?></option>
    <?php endforeach; ?>
    </select>
    <?php if($description): ?>
    <span class="help-block"><?php echo $description; ?></span>
    <?php endif; ?>
  </div>
</div>