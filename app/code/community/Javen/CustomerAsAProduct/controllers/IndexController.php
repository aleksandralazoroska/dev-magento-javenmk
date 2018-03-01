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

      if( !Mage::getSingleton( 'customer/session' )->isLoggedIn() ) {
        return $this->_redirect();
      }

      $params = $this->getRequest()->getParams();

      // Get logged in customer
      $customer = Mage::getSingleton('customer/session')->getCustomer();

      /*
      if($customer->getBusinessPage() == 1) {
        Mage::getSingleton('core/session')->addError($this->__('You have already created a business page'));
        return $this->_redirectUrl('/customer/account/index');
      }
      */

      Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

      $product = Mage::getModel('catalog/product');
      $productSku = strtolower(preg_replace('/\s+/', '-', $params['name']) . "-" .date("ymdhis"));

      // Used for the URL key
      $category = Mage::getModel('catalog/category')->load($params['category']);
      $categoryUrlHtml =  substr($category->getUrl(), strrpos($category->getUrl(), '/') + 1);
      $categoryUrl = explode(".", $categoryUrlHtml)[0];

      // Image upload
      $uploaddir = Mage::getBaseDir('media') . DS . 'uploads' . DS ;
      $uploadfile = $uploaddir . basename($_FILES['cover-image']['name']);

      if (move_uploaded_file($_FILES['cover-image']['tmp_name'], $uploadfile)) {
        echo "File is valid, and was successfully uploaded.\n";
      } else {
        echo "Upload failed";
      }

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
        ->setCityAttr($params['city'])

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

        ->setPrice(1.00)

        //->setMetaTitle('test meta title 2')
        //->setMetaKeyword('test meta keyword 2')
        //->setMetaDescription('test meta description 2')

        ->setDescription($params['about-us'])
        ->setShortDescription($params['about-us'])

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

        $productId = Mage::getModel('catalog/product')->getIdBySku($productSku);

        $customer->setBusinessPage($productId);
        $customer->save();

      } catch(Exception $e){

        Mage::log($e->getMessage());

      }

      return $this->_redirectUrl('/' . $categoryUrl . '/' . $productSku . '.html');

    }

    public function updateAction() {

      $params = $this->getRequest()->getParams();

      $category = Mage::getModel('catalog/category')->load($params['category']);
      $categoryUrlHtml =  substr($category->getUrl(), strrpos($category->getUrl(), '/') + 1);
      $categoryUrl = explode(".", $categoryUrlHtml)[0];

      // Important for Product save and Product update
      Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

      $customer = Mage::getSingleton('customer/session')->getCustomer();
      $product = Mage::getModel('catalog/product')->load($customer->getBusinessPage());

      $productUrlKey = $product->getUrlKey();

      Mage::log('development.log', null, $params['sunday-radio']);

      try {

        $product->setName($params['name'])

                //Custom Informations
                ->setPhone($params['telephone'])
                ->setPhone2($params['telephone-2'])
                ->setAddress($params['address'])
                ->setCityAttr($params['city'])

                //Custom Social
                ->setWebpage($params['webpage'])
                ->setFacebook($params['facebook'])
                ->setTwitter($params['twitter'])
                ->setInstagram($params['instagram'])

                //Custom Working Hours
                ->setMonday($params['monday'])
                ->setTuesday($params['tuesday'])
                ->setWednesday($params['wednesday'])
                ->setThursday($params['thursday'])
                ->setFriday($params['friday'])
                ->setSaturday($params['saturday'])
                ->setSunday($params['sunday'])

                // Description
                ->setDescription($params['about-us'])
                ->setShortDescription($params['about-us']);

                //->setMediaGallery (array('images'=>array (), 'values'=>array ())) //media gallery initialization
                //->addImageToMediaGallery('media/catalog/product/1/0/10243-1.png', array('image','thumbnail','small_image'), false, false) //assigning image, thumb and small image to media gallery

                //->setCategoryIds(array($params['category'])) //assign product to categories
                //->addImageToMediaGallery($uploadfile, 'cover_image', false);

        $product->save();

      }
      catch(Exception $e) {

        Mage::log($e->getMessage());

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
