<?php

class JoinMailingListForm extends Form
{
  // protected $inline = true;
  
  public function elements()
  {
    $mail = new FormInputText('mail');
    $mail->title = 'Email Address';
    $mail->required = true;
    // $mail->description = 'Or just sign up to the mailing list.';
    // $mail->inline = true;
    
    $submit = new FormInputSubmit('submit');
    $submit->value = 'Join Mailing List';
    // $submit->inline = true;
    
    $actions = new FormSectionActions;
    $actions->addElement($submit);
    
    return array($mail, $actions);
  }
  
  public function validate($values)
  {
    if($values['mail'] && !filter_var($values['mail'],  FILTER_VALIDATE_EMAIL)){
      $this->error('mail', 'Invalid email address');
    }
  }
  
  public function submit($values)
  {
    $model = new SettingModel;
    $address = $model->get('contact-address');
    $fix = $model->get('server-mail-fix');
    
    $to      = $address;
    $subject = 'Mailing list reqeuest from tomwhitty.co.uk';
    $message = $values['mail'] . ' would like to join the mailing list';
    $headers = 'From: mailgoblin@tomwhitty.co.uk';
    
    $sent = mail($to, $subject, $message, $headers, $fix);
    
    if($sent){
      $this->message('Thanks for joining the mailing list!');
    } else {
      $this->error('There was a problem signing you up - please try again');
    }
  }
}