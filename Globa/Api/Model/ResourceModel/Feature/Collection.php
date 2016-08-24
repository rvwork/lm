<?php

namespace Globa\Api\Model\ResourceModel\Feature;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection{

protected function _construct(){
$this->_init('Globa\Api\Model\Feature', 'Globa\Api\Model\ResourceModel\Feature');
}
}