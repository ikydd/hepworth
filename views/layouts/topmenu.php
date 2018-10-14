<div class="container">
  <div class="row">
    <div class="span12" id="header">
    <?php echo $header; ?>
    </div>
  </div>
  <div class="row">
    <?php echo $navigation; ?>
    <?php echo $this->show('standard/feedback'); ?>
    <?php echo $title; ?>
  </div>
  <div class="row">
    <div class="span12">
    <!-- this is the main body content that gets injected in the index.php -->
      <?php echo $content; ?>
    </div>
  </div>
</div>