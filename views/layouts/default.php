<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <meta http-equiv="Content-Script-Type" content="text/javascript" />
	<link rel="canonical" href="http://tomwhitty.co.uk<?php echo $canonical ?>" />
    <title>Tom Whitty | Artist</title>
    <?php echo $this->show('standard/includes'); ?>
  </head>
  <body id="<?php echo $id; ?>" class="<?php echo $class; ?>">
    <!-- this is the main body content that gets injected in the index.php -->
    <?php echo $content; ?>
  </body>
</html>