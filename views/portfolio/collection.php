<div class="portfolio">
  <?php if($collection->description): ?>
  <div class="collection-description"><p><?php echo nl2br($collection->description); ?></p></div>
  <?php endif; ?>
  <?php foreach($pictures as $picture): ?>
    <?php echo $this->show('portfolio/picture', array('picture' => $picture)); ?>
  <?php endforeach; ?>
</div>