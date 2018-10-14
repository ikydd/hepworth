<div class="prints">
  <div class="print-blurb">
    <p><?php echo $intro; ?></p>
    <p><?php echo $link; ?></p>
  </div>
  <?php foreach($prints as $print): ?>
    <?php echo $this->show('portfolio/print', array('print' => $print, 'paypal' => $paypal)); ?>
  <?php endforeach; ?>
</div>