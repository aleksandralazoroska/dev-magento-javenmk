<?php

class Javen_CustomerAsAProduct_Helper_Data extends Mage_Core_Helper_Abstract {

  // A method for uploading all images from the B
  public function coverImageUpload($images) {

    $imagesArray = array();
    $uploaddir = Mage::getBaseDir('media') . DS . 'uploads' . DS ;
    $imageExtensions = array('image/jpg','image/jpe','image/jpeg','image/png','image/bmp','image/gif');

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

    return $imagesArray;

  }

}
