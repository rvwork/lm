<?php

namespace Globa\Api\Model\ResourceModel\Specs;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection{

protected function _construct(){
$this->_init('Globa\Api\Model\Specs', 'Globa\Api\Model\ResourceModel\Specs');
}
}