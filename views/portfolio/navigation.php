<div class="navbar">
  <div class="navbar-inner">
    <ul class="nav">
    <?php foreach($links as $link): ?>
      <li<?php if($link->active) echo ' class="active"'; ?>><a href="<?php echo $link->url; ?>" class="nav-link"><?php echo $link->title; ?></a></li>
    <?php endforeach; ?>
    </ul>
  </div>
</div>