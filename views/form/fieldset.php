<fieldset id="<?php echo $id ?>">
  <?php if($title): ?>
  <legend><?php echo $title; ?></legend>
  <?php endif; ?>
  <?php if($description): ?>
  <p><?php echo $description; ?></p>
  <?php endif; ?>
  <?php echo $content; ?>
</fieldset>