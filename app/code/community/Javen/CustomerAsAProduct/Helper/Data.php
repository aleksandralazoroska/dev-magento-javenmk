<?php

class Javen_CustomerAsAProduct_Helper_Data extends Mage_Core_Helper_Abstract {

  // A method for uploading all images from the B
  public function coverImageUpload($images) {

    $imagesArray = array();
    $uploaddir = Mage::getBaseDir('media') . DS . 'uploads' . DS ;
    $imageExtensions = array('image/jpg','image/jpe','image/jpeg','image/png','image/bmp','image/gif');

    try {

      foreach ($images as $key => $value) {

        if ($key != '') {

          $image = $uploaddir . basename($images[$key]['name']);

          // Checking the size and the type of the image
          if ($images[$key]['size'] < 4194304 && in_array($images[$key]['type'], $imageExtensions)) {
            if (move_uploaded_file($images[$key]['tmp_name'], $image)) {
              // nothing
            } else {
              Mage::log('Image ' . $image . ' cannot be uploaded');
            }
            $imagesArray[$key] = $image;
          } else {
            Mage::log('Image ' . $image . ' cannot be uploaded');
          }
        }

      }

    } catch(Exception $e){
      Mage::getSingleton('core/session')->addError('Please select different image type, and the image needs to be less then 4MB');
      Mage::log($e->getMessage());
    }

    return $imagesArray;

  }

  // A method for translating city keywords
  public function trasnlateSearchKwCity($city) {

    return $city . ' ' . Mage::helper('customerasaproduct')->__($city);

  }

  // A method for translating description keywords
  public function trasnlateSearchKwDesc($name, $category) {

    return $name . ' ' . $category . ' ' . Mage::helper('customerasaproduct')->__($category);

  }

}
