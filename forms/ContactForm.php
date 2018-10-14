<?php

class ContactForm extends Form
{
  public function elements()
  {
    $name = new FormInputText('name');
    $name->title = 'Name';
    $name->required = true;
    
    $email = new FormInputText('email');
    $email->title = 'Email';
    $email->required = true;
    
    $message = new FormInputTextarea('message');
    $message->title = 'Message';
    $message->required = true;
    
    $span = new FormInputTextarea('span');
    $span->title = 'Message';
    $span->required = true;
    
    $submit = new FormInputSubmit('submit');
    $submit->value = 'Send Message';
    $submit->id = 'contact-send-button';
    
    $actions = new FormSectionActions();
    $actions->addElement($submit);
    
    return array($name, $email, $message, $actions);
  }
  
  public function validate($values)
  {
    if($values['email'] && !filter_var($values['email'], FILTER_VALIDATE_EMAIL)){
      $this->error('email', 'Invalid email address');
    }
  }
  
  public function submit($values)
  {
    $model = new SettingModel;
    $address = $model->get('contact-address');
    $fix = $model->get('server-mail-fix');
    
    $to      = $address;
    $subject = 'Contact message from tomwhitty.co.uk';
    $message = 'From: ' . htmlspecialchars($values['name'], ENT_QUOTES, 'UTF-8') 
      . ' : ' . htmlspecialchars($values['email'], ENT_QUOTES, 'UTF-8') . "\r\n\r\n" . 'Message: ' 
      . htmlspecialchars($values['message'], ENT_QUOTES, 'UTF-8');
    $headers = 'From: mailgoblin@tomwhitty.co.uk' . "\r\n" .
        'Reply-To: ' . htmlspecialchars($values['email'], ENT_QUOTES, 'UTF-8');

    $sent = mail($to, $subject, $message, $headers, $fix);
    if($sent){
      $http = new Http;
      $http->redirect('contact/thanks');
    } else {
      $this->error('There was a problem and the email failed to send');
    }
  }
}