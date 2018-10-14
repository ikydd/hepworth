<ul class="nav nav-tabs">
  <?php foreach($tabs as $tab): ?>
    <li<?php if($tab->active) echo ' class="active"';?>><a href="<?php echo $tab->url; ?>"><?php echo $tab->title; ?></a></li>
  <?php endforeach; ?>
</ul>