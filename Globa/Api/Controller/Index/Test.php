<?php
 
namespace Globa\Api\Controller\Index;

use Magento\Framework\App\Action;

class Test extends Action\Action
{
	protected $_productFactory;

	public function __construct(
		Action\Context $context,
		\Magento\Catalog\Model\ProductFactory $productFactory
		)
	{
		parent::__construct($context);
		$this->_productFactory = $productFactory;
	}
	public function execute()
	{
      $product = $this->_productFactory->create()->load(1);
      echo "<pre>";
      print_r($product->getApiUrl());
	  print_r($product->getSpces());
      echo "</pre>";

	}
}