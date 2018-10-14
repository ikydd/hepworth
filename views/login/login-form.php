<form>
  <?php echo $this->show('form/text', array('name' => 'name', 'title' => 'Username')); ?>
  <?php echo $this->show('form/password', array('name' => 'pass', 'title' => 'Password')); ?>
  <?php echo $this->show('form/submit', array('name' => 'submit', 'value' => 'Login')); ?>
</form>