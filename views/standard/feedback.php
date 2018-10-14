<?php $msgs = $this->feedback->getMessages(); ?>
<?php $errs = $this->feedback->getErrors(); ?>

<?php if(!empty($msgs) || !empty($errs)): ?>
<div id="standard-feedback">
  <?php if(!empty($msgs)): ?>
  <div id="standard-messages" class="alert alert-block alert-success">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <?php foreach($msgs as $msg): ?>
    <div class="standard-message"><?php echo $msg; ?></div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
  <?php if(!empty($errs)): ?>
  <div id="standard-errors" class="alert alert-block alert-error">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <?php foreach($errs as $err): ?>
    <div class="standard-error"><?php echo $err; ?></div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>
<?php endif; ?>