<table<?php if(isset($attributes)) echo $this->show('standard/attributes', array('attributes' => $attributes)); ?>>
  <?php if($caption) echo '<caption>' . $caption. ' </caption>'; ?>
  <?php echo $content; ?>
</table>