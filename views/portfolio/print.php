<div class="portfolio-item">
  <div class="print">
    <div class="print-box">
      <a rel="lightbox[collection]" href="<?php echo $this->http->url($print->full); ?>" title="<?php echo $print->title; ?>">
        <img src="<?php echo $this->http->url($print->medium); ?>" />
      </a>
    </div>
    <div class="print-details">
      <h4 class="print-title"><?php echo $print->title; ?></h4>
      <div class="print-caption"><?php echo nl2br($print->details); ?></div>
      <p>&pound;<?php echo $print->price_formatted; ?></p>
      <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
        <input type="hidden" name="business" value="<?php echo $paypal; ?>">
        <input type="hidden" name="amount" value="<?php echo $print->price; ?>">
        <input type="hidden" name="item_name" value="Print of <?php echo $print->title; ?>">
        <input type="hidden" name="quantity" value="1">
        <input type="hidden" name="currency_code" value="GBP"> 
        <input type="hidden" name="cancel_return" value="<?php echo urlencode($this->http->url('prints', true)); ?>">
        <input type="hidden" name="return" value="<?php echo urlencode($this->http->url('prints/thanks', true)); ?>">
        <input type="hidden" name="cmd" value="_xclick">
        <input type="image" class="buy-print-button" name="submit" border="0" src="https://www.paypal.com/en_US/i/btn/x-click-but5.gif" alt="PayPal - The safer, easier way to pay online"> 
        <img alt="" border="0" width="1" height="1" src="https://www.paypal.com/en_US/i/scr/pixel.gif" > 
      </form>
    </div>
    <div style="clear:both;" />
  </div>
</div>