<form id="<?php echo $id ?>" class="form-inline" method="<?php echo $method; ?>" <?php if($action) echo 'action="' . $action . '"'; ?> enctype="<?php echo $enctype; ?>">
  <?php echo $content; ?>
</form>