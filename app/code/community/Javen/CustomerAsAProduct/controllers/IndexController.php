<?php

class Javen_CustomerAsAProduct_IndexController extends Mage_Core_Controller_Front_Action {

    public function IndexAction() {

	    $this->loadLayout();
      $this->renderLayout();

    }

    public function postAction() {

      $params = $this->getRequest()->getParams();

      Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
      $product = Mage::getModel('catalog/product');

      $productSku = "123321123";
      $productUrlKey = "123321123";

      $category = Mage::getModel('catalog/category')->load($params['category']);
      $categoryUrlHtml =  substr($category->getUrl(), strrpos($category->getUrl(), '/') + 1);
      $categoryUrl = explode(".", $categoryUrlHtml)[0];

      //Image upload
      $uploaddir = Mage::getBaseDir('media') . DS . 'uploads' . DS ;
      $uploadfile = $uploaddir . basename($_FILES['cover-image']['name']);

      if (move_uploaded_file($_FILES['cover-image']['tmp_name'], $uploadfile)) {
        echo "File is valid, and was successfully uploaded.\n";
      } else {
        echo "Upload failed";
      }

      /*
      echo "</p>";
      echo '<pre>';
      echo 'Here is some more debugging info:';
      print_r($_FILES);
      print "</pre>";
      */

      try {

        $product->setWebsiteIds(array(1)) //website ID the product is assigned to, as an array
        ->setAttributeSetId(10) //'Companies' attribute set
        ->setTypeId('virtual') //product type
        ->setCreatedAt(strtotime('now')) //product creation time

        ->setSku($productSku) //SKU
        ->setName($params['name'])
        ->setWeight(1.0000)
        ->setStatus(1)
        ->setTaxClassId(0)
        ->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH) //catalog and search visibility
        ->setUrlKey($productUrlKey)

        //Custom Informations
        ->setPhone($params['phone'])
        ->setEmail($params['email'])
        ->setCoverPicture()
        ->setAddress($params['address'])
        ->setCityAttr($params['city'])

        //Custom Social
        ->setWebpage($params['webpage'])
        ->setFacebook($params['facebook'])
        ->setTwitter($params['twitter'])
        ->setInstagram($params['instagram'])

        //Custom Working Hours
        ->setMonday($params['monday_from'] . " - " . $params['monday_to'])
        ->setTuesday($params['tuesday_from'] . " - " . $params['tuesday_to'])
        ->setWednesday($params['wednesday_from'] . " - " . $params['wednesday_to'])
        ->setThursday($params['thursday_from'] . " - " . $params['thursday_to'])
        ->setFriday($params['friday_from'] . " - " . $params['friday_to'])
        ->setSaturday($params['saturday_from'] . " - " . $params['saturday_to'])
        ->setSunday($params['sunday_from'] . " - " . $params['sunday_to'])

        ->setPrice(1.00)

        //->setMetaTitle('test meta title 2')
        //->setMetaKeyword('test meta keyword 2')
        //->setMetaDescription('test meta description 2')

        ->setDescription($params['name'])
        ->setShortDescription($params['name'])

        //->setMediaGallery (array('images'=>array (), 'values'=>array ())) //media gallery initialization
        //->addImageToMediaGallery('media/catalog/product/1/0/10243-1.png', array('image','thumbnail','small_image'), false, false) //assigning image, thumb and small image to media gallery

        ->setStockData(array(
                       'is_in_stock' => 1, //Stock Availability
                       'qty' => 9999999 //qty
                   )
        )

        ->setCategoryIds(array($params['category'])) //assign product to categories
        ->addImageToMediaGallery($uploadfile, 'cover_image', false);

        $product->save();


      } catch(Exception $e){

        Mage::log($e->getMessage());

      }

      return $this->_redirectUrl('/' . $categoryUrl . '/' . $productUrlKey . '.html');

    }

}
