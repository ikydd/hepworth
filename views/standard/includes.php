<?php foreach($this->includes->getCSS() as $stylesheet): ?>
 <link rel="stylesheet" type="text/css" href="<?php echo $stylesheet; ?>" />
<?php endforeach; ?>

<script type="text/javascript">var Settings = <?php echo json_encode($this->includes->getJSsettings()); ?></script>

<?php foreach($this->includes->getJS() as $script): ?>
 <script type="text/javascript" language="javascript" src="<?php echo $script; ?>"></script>
<?php endforeach; ?>