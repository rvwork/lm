<?php
namespace Globa\Api\Model\ResourceModel;

class Feature extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb{

protected function _construct(){
$this->_init('custom_global_api_product_feature', 'feature_id');
}
}