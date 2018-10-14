<form id="<?php echo $id ?>" class="form-horizontal" method="<?php echo $method; ?>" <?php if($action) echo 'action="' . $action . '"'; ?> enctype="<?php echo $enctype; ?>">
  <?php echo $content; ?>
</form>