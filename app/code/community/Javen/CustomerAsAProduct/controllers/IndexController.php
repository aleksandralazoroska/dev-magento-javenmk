<?php

class Javen_CustomerAsAProduct_IndexController extends Mage_Core_Controller_Front_Action {

    public function IndexAction() {

      if( !Mage::getSingleton( 'customer/session' )->isLoggedIn() ) {
        return $this->_redirect();
      }

      $this->loadLayout();
      $this->renderLayout();

    }

    public function postAction() {

      Mage::log(date("h:i:sa"), null, 'developer.log');

      if( !Mage::getSingleton( 'customer/session' )->isLoggedIn() ) {
        return $this->_redirect();
      }

      // Important for Product save and Product update
      Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

      $params = $this->getRequest()->getParams();
      $customer = Mage::getSingleton('customer/session')->getCustomer();

      // Used for the URL key
      $category = Mage::getModel('catalog/category')->load($params['category']);
      $categoryUrlHtml = substr($category->getUrl(), strrpos($category->getUrl(), '/') + 1);
      $categoryUrl = explode(".", $categoryUrlHtml)[0];

      // Loading product
      $product = Mage::getModel('catalog/product');
      $productSku = strtolower(preg_replace('/\s+/', '-', $params['name']) . "-" .date("ymdhis"));

      $imagesArray = Mage::helper('customerasaproduct')->coverImageUpload($_FILES);

      // Search Keywords for City
      $cityTranslatedArray = '';
      $cityTranslatedArray = Mage::helper('customerasaproduct')->trasnlateSearchKwCity($params['city']);

      // Search Keywords for Description
      $descTranslatedArray = '';
      $descTranslatedArray = Mage::helper('customerasaproduct')->trasnlateSearchKwDesc($params['name'], $categoryUrl);


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
          ->setUrlKey($productSku)

          //Custom Informations
          ->setPhone($params['telephone'])
          ->setPhone2($params['telephone-2'])
          ->setEmail($customer->getEmail())
          ->setCoverPicture()
          ->setAddress($params['address'])
          ->setMapLat($params['map-lat'])
          ->setMapLng($params['map-lng'])
          ->setCityAttr($cityTranslatedArray)

          //Custom Social
          ->setWebpage($params['webpage'])
          ->setFacebook($params['facebook'])
          ->setTwitter($params['twitter'])
          ->setInstagram($params['instagram'])

          //Custom Working Hours
          ->setMonday($params['monday-from'])
          ->setMondayTo($params['monday-to'])
          ->setTuesday($params['tuesday-from'])
          ->setTuesdayTo($params['tuesday-to'])
          ->setWednesday($params['wednesday-from'])
          ->setWednesdayTo($params['wednesday-to'])
          ->setThursday($params['thursday-from'])
          ->setThursdayTo($params['thursday-to'])
          ->setFriday($params['friday-from'])
          ->setFridayTo($params['friday-to'])
          ->setSaturday($params['saturday-from'])
          ->setSaturdayTo($params['saturday-to'])
          ->setSunday($params['sunday-from'])
          ->setSundayTo($params['sunday-to'])

          //Actions and Promotions
          ->setCompanyPromotions($params['company-promotions'])

          ->setPrice(1.00)

          ->setDescription($descTranslatedArray)
          ->setShortDescription($params['about-us'])

          ->setStockData(array(
                         'is_in_stock' => 1, //Stock Availability
                         'qty' => 9999999 //qty
                     )
          )

          ->setCategoryIds(array($params['category'])); //assign product to categories

        $product->save();

        $productId = Mage::getModel('catalog/product')->getIdBySku($productSku);

        $customer->setBusinessPage($productId);
        $customer->save();

      } catch(Exception $e){

        Mage::log($e->getMessage());

      }

      $product = Mage::getModel('catalog/product')->load($customer->getBusinessPage());

      $product->addImageToMediaGallery($imagesArray['cover-image'], array('cover_image'), false, false);

      // Remove the image from the temp directory and removing the cover image from the array
      //unlink($imagesArray['cover-image']);
      unset($imagesArray['cover-image']);

      try {

        // Adding all media images
        foreach ($imagesArray as $key => $value) {

          // Uploading media images
          $product->addImageToMediaGallery($value, array('image','thumbnail','small_image'), false, false);

          // Remove the image from the temp directory
          unlink($value);

        }

        $product->save();

      } catch(Exception $e){
        Mage::getSingleton('core/session')->addError('Invalid image type.');
        Mage::log($e->getMessage());
      }

      // Redirecting to select package page
      $this->_redirect('customerasaproduct/packages/selectpackage');
      return;

    }

    public function updateAction() {

      if( !Mage::getSingleton( 'customer/session' )->isLoggedIn() ) {
        return $this->_redirect();
      }

      // Important for Product save and Product update
      Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

      $params = $this->getRequest()->getParams();
      $customer = Mage::getSingleton('customer/session')->getCustomer();

      // Used for the URL key
      $category = Mage::getModel('catalog/category')->load($params['category']);
      $categoryUrlHtml = substr($category->getUrl(), strrpos($category->getUrl(), '/') + 1);
      $categoryUrl = explode(".", $categoryUrlHtml)[0];

      // Loading existing product
      $product = Mage::getModel('catalog/product')->load($customer->getBusinessPage());
      $productUrlKey = $product->getUrlKey();

      // Search Keywords for City
      $cityTranslatedArray = '';
      $cityTranslatedArray = Mage::helper('customerasaproduct')->trasnlateSearchKwCity($params['city']);

      // Search Keywords for Description
      $descTranslatedArray = '';
      $descTranslatedArray = Mage::helper('customerasaproduct')->trasnlateSearchKwDesc($params['name'], $categoryUrl);

      try {

        $product->setName($params['name'])

                //Custom Informations
                ->setPhone($params['telephone'])
                ->setPhone2($params['telephone-2'])
                ->setAddress($params['address'])
                ->setCityAttr($cityTranslatedArray)

                //Custom Social
                ->setWebpage($params['webpage'])
                ->setFacebook($params['facebook'])
                ->setTwitter($params['twitter'])
                ->setInstagram($params['instagram'])

                //Custom Working Hours
                ->setMonday($params['monday-from'])
                ->setMondayTo($params['monday-to'])
                ->setTuesday($params['tuesday-from'])
                ->setTuesdayTo($params['tuesday-to'])
                ->setWednesday($params['wednesday-from'])
                ->setWednesdayTo($params['wednesday-to'])
                ->setThursday($params['thursday-from'])
                ->setThursdayTo($params['thursday-to'])
                ->setFriday($params['friday-from'])
                ->setFridayTo($params['friday-to'])
                ->setSaturday($params['saturday-from'])
                ->setSaturdayTo($params['saturday-to'])
                ->setSunday($params['sunday-from'])
                ->setSundayTo($params['sunday-to'])

                //Actions and Promotions
                ->setCompanyPromotions($params['company-promotions'])

                // Description
                ->setDescription($descTranslatedArray)
                ->setShortDescription($params['about-us'])

                ->setCategoryIds(array($params['category'])); //assign product to categories

        $product->save();


      }
      catch(Exception $e) {

        Mage::log($e->getMessage());

      }

      // Adding all media images
      $imagesArray = Mage::helper('customerasaproduct')->coverImageUpload($_FILES);

      try {

        if (!empty($imagesArray)) {

          // Adding all media images
          foreach ($imagesArray as $key => $value) {

            // Uploading media images
            $product->addImageToMediaGallery($value, array('image','thumbnail','small_image'), false, false);

            // Remove the image from the temp directory
            unlink($value);

          }

          $product->save();

        }

      } catch(Exception $e){
        Mage::getSingleton('core/session')->addError('Invalid image type!');
        Mage::log($e->getMessage());
      }

      // Check if the business already bought some package
      if ($product->getIsActive() == false) {
        $this->_redirect('customerasaproduct/packages/selectpackage');
        return;
      }

      return $this->_redirectUrl('/' . $productUrlKey . '.html');

    }

    // Set popularity on every product page open for every product
    public function popularityAction() {

      $params = $this->getRequest()->getParams();
      $productId = $params['product_id'];

      Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

      $product = Mage::getModel('catalog/product')->load($productId);
      $currentPopularity = $product->getData('popularity');

      if ($currentPopularity == '') { $currentPopularity = 0; };

      $newPopularity = $currentPopularity + 1;

      try {

        $product->setWebsiteIds(array(1))
                ->setData('popularity', $newPopularity);

        $product->save();

      }
      catch(Exception $e) {

        Mage::log($e->getMessage());

      }

    }

}
