<div class="container">
  <div class="row">
    <div class="span12" id="header">
    <?php echo $header; ?>
    </div>
  </div>
  <div class="row">
    <div class="span12">
      <?php echo $this->show('standard/feedback'); ?>
      <?php echo $title; ?>
      <!-- this is the main body content that gets injected in the index.php -->
      <?php echo $content; ?>
    </div>
  </div>
</div>