<div class="row">
  <?php foreach($actions as $action): ?>
  <a class="btn" href="<?php echo $action->url; ?>"><?php echo $action->title; ?></a>
  <?php endforeach;?>
</div>