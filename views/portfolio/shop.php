<div id="<?php if(isset($id)) echo $id; ?>" class="normal-page">
  <?php if($url && $url_text): ?>
    <div class="shop-link"><a href="<?php echo $url; ?>"><?php echo $url_text; ?></a></div>
  <?php endif; ?>
  <div class="page-content"><?php echo $content; ?></div>
</div>