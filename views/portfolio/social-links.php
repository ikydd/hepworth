<div id="social-links">
  <?php foreach($links as $service => $link): ?>
    <?php if($link): ?><a href="<?php echo $link; ?>"><i class="fa fa-<?php echo $service; ?>"></i></a><?php endif; ?>
  <?php endforeach; ?>
</div>