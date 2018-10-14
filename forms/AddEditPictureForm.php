<?php

class AddEditPictureForm extends Form
{
  public $enctype = 'multipart/form-data';
  
  public function __construct(Picture $picture = null)
  {
    if($picture){
      $this->picture = $picture;
    } else {
      $this->picture = new Picture();
    }
    
    $model = new CollectionModel;
    $this->collections = $model->findWhere(null, array('title' => 'ASC'));
    
    parent::__construct();
  }
  
  public function elements()
  {
    $id = new FormInputHidden('id');
    $id->value = $this->picture->id;
    
    $title = new FormInputText('title');
    $title->title = 'Title';
    $title->description = 'The name of your picture';
    $title->defaultValue = $this->picture->title;
    $title->required = true;
    
    $file = new FormInputFile('image');
    $file->title = 'Image';
    $file->description = 'The image file, preferably bigger than 1024x768, but no bigger than 8Mb please!';
    
    $cap = new FormInputTextarea('caption');
    $cap->title = 'Caption';
    $cap->description = 'A little bit of blurb about the picture that will appear below it';
    $cap->defaultValue = $this->picture->caption;
    
    $options = array('' => 'None');
    foreach($this->collections as $col){
      $options[$col->id] = $col->title;
    }
    
    $col = new FormInputSelect('collection');
    $col->title = 'Collection';
    $col->description = 'Which collection do you want this to be part of?';
    $col->options = $options;
    $col->defaultValue = $this->picture->collection_id;
    
    $sale = new FormInputCheckbox('available');
    $sale->title = 'Prints Available?';
    $sale->description = 'Are print of this picture actually for sale at all?';
    $sale->defaultValue = $this->picture->available;
    
    $price = new FormInputText('price');
    $price->title = 'Price';
    $price->description = 'The price of a print';
    $price->defaultValue = $this->picture->price;
    
    $det = new FormInputTextarea('details');
    $det->title = 'Print Text';
    $det->description = 'Things like size of the print and how many in the print run';
    $det->defaultValue = $this->picture->details;
    
    $submit = new FormInputSubmit('submit');
    $submit->value = $this->picture->id ? 'Edit' : 'Add';
    
    $actions = new FormSectionActions;
    $actions->addElement($submit);
    
    return array($id, $title, $file, $cap, $col, $sale, $price, $det, $actions);
  }
  
  public function validate($values)
  {
    if($values['price'] && !is_numeric($values['price'])){
      $this->error('price', 'Price should be a number');
    }

    if ($_FILES['image']['tmp_name']) {
      try {      
        $img = new Image($_FILES['image']['tmp_name']);
      } catch (ImageException $e) {
        $this->error('image', $e->getMessage());
      }
    }
  }
  
  public function submit($values)
  {
    $pic = new stdClass;
    $pic->id = $values['id'];
    $pic->title = $values['title'];
    $pic->caption = $values['caption'];
    $pic->available = (bool) $values['available'];
    $pic->price = $values['price'];
    $pic->details = $values['details'];
    $pic->collection_id = $values['collection'];
    
    if($_FILES['image']['tmp_name']){
      
      try {      
        // save mini
        $img = new Image($_FILES['image']['tmp_name']);
        $img->scale(48, 32)->save('public/uploads/mini/' . $_FILES['image']['name']);
        
        $pic->mini = 'public/uploads/mini/' . $_FILES['image']['name'];
      
        // save thumbnail
        $img = new Image($_FILES['image']['tmp_name']);
        $img->scale(160, 120)->save('public/uploads/thumb/' . $_FILES['image']['name']);
        
        $pic->thumb = 'public/uploads/thumb/' . $_FILES['image']['name'];

        // save medium
        $img = new Image($_FILES['image']['tmp_name']);
        $img->scale(640, 480)->save('public/uploads/medium/' . $_FILES['image']['name']);
        
        $pic->medium = 'public/uploads/medium/' . $_FILES['image']['name'];
     
        // save full
        $img = new Image($_FILES['image']['tmp_name']);
        $img->scale(920, 690)->save('public/uploads/full/' . $_FILES['image']['name']);
      
        $pic->full = 'public/uploads/full/' . $_FILES['image']['name'];
      
      } catch (ImageException $e) {
        $this->error('image', $e->getMessage());
        
        return;
      }
    
    }
    
    $model = new PictureModel();
    $model->save($pic);
    
    if(!$values['id']){
      $this->message('Picture added');
      $http = new Http;
      $http->redirect('pictures');
    } else {
      $this->message('Picture saved');
    }
  }
}