<?php

class SettingsForm extends Form
{
  public $settings = array(
    'contact-address' => '',
    'paypal-account' => '',
    'facebook-page' => '',
    'twitter-page' => '',
    'tumblr-page' => '',
    'instagram-page' => '',
  );
  
  public function __construct($settings = array())
  {
    $this->settings = $settings + $this->settings;
  
    parent::__construct();
  }
  public function elements()
  {
    $contact = new FormInputText('contact');
    $contact->title = 'Contact Email';
    $contact->description = 'This is the email address that contact submissions will go to for the site as a whole
      as well as other alerts and emails';
    $contact->defaultValue = $this->settings['contact-address'];
    $contact->required = true;
    
    $paypal = new FormInputText('paypal');
    $paypal->title = 'PayPal Account';
    $paypal->description = 'The email address that your PayPal account is tied to.';
    $paypal->defaultValue = $this->settings['paypal-account'];
    
    $facebook = new FormInputText('facebook');
    $facebook->title = 'Facebook';
    $facebook->defaultValue = $this->settings['facebook-page'];
    
    $twitter = new FormInputText('twitter');
    $twitter->title = 'Twitter';
    $twitter->defaultValue = $this->settings['twitter-page'];
    
    $tumblr = new FormInputText('tumblr');
    $tumblr->title = 'Tumblr';
    $tumblr->defaultValue = $this->settings['tumblr-page'];
    
    $instagram = new FormInputText('instagram');
    $instagram->title = 'Instagram';
    $instagram->defaultValue = $this->settings['instagram-page'];
    
    $social = new FormSectionFieldset('social');
    $social->title = 'Social Links';
    $social->description = "Don't forget to put http or whatever in front otherwise they won't validate. 
      Usually if you just copy them out of the browser it will be fine.";
    $social->addElements(array($facebook, $twitter, $tumblr, $instagram));
    
    $submit = new FormInputSubmit('submit');
    $submit->value = 'Save';
    
    $actions = new FormSectionActions;
    $actions->addElement($submit);
    
    return array($contact, $paypal, $social, $actions);
  }
  
  public function validate($values)
  {
    if($values['contact'] && !filter_var($values['contact'],  FILTER_VALIDATE_EMAIL)){
      $this->error('contact', 'Invalid contact address');
    }
    if($values['paypal'] && !filter_var($values['paypal'],  FILTER_VALIDATE_EMAIL)){
      $this->error('paypal', 'Invalid paypal address');
    }
    if($values['facebook'] && !filter_var($values['facebook'],  FILTER_VALIDATE_URL)){
      $this->error('facebook', 'Invalid facebook page');
    }
    if($values['twitter'] && !filter_var($values['twitter'],  FILTER_VALIDATE_URL)){
      $this->error('twitter', 'Invalid twitter page');
    }
    if($values['tumblr'] && !filter_var($values['tumblr'],  FILTER_VALIDATE_URL)){
      $this->error('tumblr', 'Invalid tumblr page');
    }
    if($values['instagram'] && !filter_var($values['instagram'],  FILTER_VALIDATE_URL)){
      $this->error('instagram', 'Invalid instagram page');
    }
  }
  
  public function submit($values)
  {
    $model = new SettingModel();
    $model->save((object) array('key' => 'contact-address', 'value' => $values['contact']));
    $model->save((object) array('key' => 'paypal-account', 'value' => $values['paypal']));
    $model->save((object) array('key' => 'facebook-page', 'value' => $values['facebook']));
    $model->save((object) array('key' => 'twitter-page', 'value' => $values['twitter']));
    $model->save((object) array('key' => 'tumblr-page', 'value' => $values['tumblr']));
    $model->save((object) array('key' => 'instagram-page', 'value' => $values['instagram']));
    
    $this->message('Settings saved');
    
  }
}