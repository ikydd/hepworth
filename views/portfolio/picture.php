<div class="portfolio-item">
  <h4 class="picture-title"><?php echo $picture->title; ?></h4>
  <div class="picture-box">
    <a rel="lightbox[collection]" href="<?php echo $this->http->url($picture->full); ?>" title="<?php echo $picture->title; ?>">
      <img src="<?php echo $this->http->url($picture->medium); ?>" />
    </a>
  </div>
  <div class="caption"><?php echo nl2br($picture->caption); ?></div>
</div>