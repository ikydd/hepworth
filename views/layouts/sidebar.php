<div class="container">
  <div class="row">
    <div class="span12" id="header">
    <?php echo $header; ?>
    </div>
  </div>
  <div class="row">
    <div class="span2" id="sidebar">
      <?php echo $menu; ?>
    </div>
    <div class="span10">

      <?php echo $this->show('standard/feedback'); ?>
      <?php echo $title; ?>
      <?php echo $content; ?>
    </div>
  </div>
</div>