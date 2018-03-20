<?php

$installer = $this;
$installer->startSetup();

//$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$setup = Mage::getModel('customer/entity_setup' , 'core_setup');

$entityTypeId = $setup->getEntityTypeId('customer');
$attributeSetId = $setup->getDefaultAttributeSetId($entityTypeId);
$attributeGroupId = $setup->getDefaultAttributeGroupId($entityTypeId, $attributeSetId);

$setup->addAttribute("customer", "company",  array(
    "type" => "varchar",
    "label" => "Compamy",
    "input" => "text",
    "global" => 1,
    "user_defined" => false,
    "searchable" => false,
    "comparable" => false,
    "visible_on_front" => true,
    "required" => false,
    "unique"  => false,
    "default" => 0,
));

$attribute = Mage::getSingleton("eav/config")->getAttribute("customer", "company");

$setup->addAttributeToGroup(
    $entityTypeId,
    $attributeSetId,
    $attributeGroupId,
    'company',
    '999'  //sort_order
);

$used_in_forms=array();

$used_in_forms[]="adminhtml_customer";
//$used_in_forms[]="checkout_register";
$used_in_forms[]="customer_account_create";
$used_in_forms[]="customer_account_edit";
//$used_in_forms[]="adminhtml_checkout";

$attribute->setData("used_in_forms", $used_in_forms)
          ->setData("is_used_for_customer_segment", true)
          ->setData("is_system", 0)
          ->setData("is_user_defined", 1)
          ->setData("is_visible", 1)
          ->setData("sort_order", 100);

$attribute->save();

$installer->endSetup();
