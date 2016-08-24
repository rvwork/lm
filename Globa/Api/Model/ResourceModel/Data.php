<?php
namespace Globa\Api\Model\ResourceModel;

class Data extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb{

protected function _construct(){
$this->_init('custom_global_api_items', 'item_id');
}
}