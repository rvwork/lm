<?php
namespace Globa\Api\Model\ResourceModel;

class Specs extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb{

protected function _construct(){
$this->_init('custom_global_api_product_specs', 'specs_id');
}
}