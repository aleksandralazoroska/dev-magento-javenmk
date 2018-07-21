<?php

class Javen_CustomerAsAProduct_DeleteController extends Mage_Core_Controller_Front_Action {

    public function IndexAction() {

    }

    // A method for sending email reminder for the basic packages
    public function NotifyPackagesAction() {

    }

    // A method for sending email reminder for the promotions
    public function NotifyPromotionsAction() {

    }

    // A method for removing images from the Edit Page
    public function RemoveImageAction() {

      Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

      $params = $this->getRequest()->getParams();
      $productId = $params['product_id'];
      $imagePath = $params['image_path'];

      $product = Mage::getModel('catalog/product')->load($productId);

      $entityTypeId = Mage::getModel('eav/entity')->setType('catalog_product')->getTypeId();
      $mediaGalleryAttribute = Mage::getModel('catalog/resource_eav_attribute')->loadByCode($entityTypeId, 'media_gallery');

      $productGallery = $product->getMediaGalleryImages();

      try {

        $mediaGalleryAttribute->getBackend()->removeImage($product, $imagePath);
        $product->save();

        $json_response = array('success' => true);
        $this->getResponse()->setBody(Zend_Json::encode($json_response));

      } catch(Exception $e){

        Mage::log($e->getMessage());

      }


    }

}
