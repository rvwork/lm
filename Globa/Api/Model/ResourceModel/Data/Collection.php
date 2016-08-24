<?php

namespace Globa\Api\Model\ResourceModel\Data;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection{

protected function _construct(){
$this->_init('Globa\Api\Model\Data', 'Globa\Api\Model\ResourceModel\Data');
}


}

