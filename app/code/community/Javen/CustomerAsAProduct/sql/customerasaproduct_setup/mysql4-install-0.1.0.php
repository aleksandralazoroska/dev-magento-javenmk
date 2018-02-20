<?php

$installer = $this;
$installer->startSetup();

//$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$setup = Mage::getModel('customer/entity_setup' , 'core_setup');

$entityTypeId = $setup->getEntityTypeId('customer');
$attributeSetId = $setup->getDefaultAttributeSetId($entityTypeId);
$attributeGroupId = $setup->getDefaultAttributeGroupId($entityTypeId, $attributeSetId);

$setup->addAttribute("customer", "business_page",  array(
    "type" => "int",
    "label" => "Business Page",
    "input" => "text",
    "global" => 1,
    "user_defined" => false,
    "searchable" => false,
    "comparable" => false,
    "visible_on_front" => false,
    "required" => false,
    "unique"  => false,
    "default" => 0,
));

$attribute = Mage::getSingleton("eav/config")->getAttribute("customer", "business_page");

$setup->addAttributeToGroup(
    $entityTypeId,
    $attributeSetId,
    $attributeGroupId,
    'business_page',
    '999'  //sort_order
);

$used_in_forms=array();

$used_in_forms[]="adminhtml_customer";
//$used_in_forms[]="checkout_register";
//$used_in_forms[]="customer_account_create";
//$used_in_forms[]="customer_account_edit";
//$used_in_forms[]="adminhtml_checkout";
$attribute->setData("used_in_forms", $used_in_forms)
          ->setData("is_used_for_customer_segment", true)
          ->setData("is_system", 0)
          ->setData("is_user_defined", 1)
          ->setData("is_visible", 1)
          ->setData("sort_order", 100);

$attribute->save();

$installer->endSetup();
