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
      $imageCover = $uploaddir . basename($_FILES['cover-image']['name']);

      if (move_uploaded_file($_FILES['cover-image']['tmp_name'], $imageCover)) {
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
        ->addImageToMediaGallery($imageCover, 'cover_image', false);

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

      $uploaddir = Mage::getBaseDir('media') . DS . 'uploads' . DS ;
      $imageCover = $uploaddir . basename($_FILES['cover-image']['name']);

      $imageMedia1 = $uploaddir . basename($_FILES['media-image-1']['name']);
      $imageMedia2 = $uploaddir . basename($_FILES['media-image-2']['name']);
      $imageMedia3 = $uploaddir . basename($_FILES['media-image-3']['name']);
      $imageMedia4 = $uploaddir . basename($_FILES['media-image-4']['name']);
      $imageMedia5 = $uploaddir . basename($_FILES['media-image-5']['name']);
      $imageMedia6 = $uploaddir . basename($_FILES['media-image-6']['name']);

      if (move_uploaded_file($_FILES['cover-image']['tmp_name'], $imageCover)) {
        echo "File is valid, and was successfully uploaded.\n";
      } else {
        echo "Upload failed";
      }

      move_uploaded_file($_FILES['media-image-1']['tmp_name'], $imageMedia1);

      if (move_uploaded_file($_FILES['media-image-2']['tmp_name'], $imageMedia2)) {
        echo "File is valid, and was successfully uploaded.\n";
      } else {
        echo "Upload failed";
      }

      move_uploaded_file($_FILES['media-image-3']['tmp_name'], $imageMedia3);
      move_uploaded_file($_FILES['media-image-4']['tmp_name'], $imageMedia4);
      move_uploaded_file($_FILES['media-image-5']['tmp_name'], $imageMedia5);
      move_uploaded_file($_FILES['media-image-6']['tmp_name'], $imageMedia6);

      $mediaImages = array($imageMedia1, $imageMedia2, $imageMedia3, $imageMedia4, $imageMedia5, $imageMedia6);

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

                // Description
                ->setDescription($params['about-us'])
                ->setShortDescription($params['about-us'])

                ->setCategoryIds(array($params['category'])) //assign product to categories

                //->setMediaGallery (array('images'=>array (), 'values'=>array ())) //media gallery initialization
                ->addImageToMediaGallery($imageCover, array('cover_image'), false);  //array('image','thumbnail','small_image'), false, false) //assigning image, thumb and small image to media gallery

        $product->save();

        foreach ($image as $mediaImages) {
          $product->addImageToMediaGallery($image, array('image',), false);
          $product->save();
        }

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
