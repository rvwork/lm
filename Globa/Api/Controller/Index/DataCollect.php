<?php
 
namespace Globa\Api\Controller\Index;

use Magento\Framework\App\Action;

class DataCollect extends Action\Action
{
	
	protected $_dataCollection;
	
	protected $_featureCollection;
	
	protected $_specsCollection;
	
	protected $_product;
	
	protected $_productRepository;
	
	protected $imageUrl;
	
	protected $_productFactory;

	protected $_desc;
	
	public function __construct(
	 Action\Context $context,
	\Globa\Api\Model\DataFactory $data,
	\Globa\Api\Model\FeatureFactory $featureFactory,
	\Globa\Api\Model\SpecsFactory $specsFactory,
	\Magento\Catalog\Model\Product $product,
	\Magento\Catalog\Model\ProductFactory $productFactory,
	\Magento\Catalog\Api\ProductRepositoryInterface $productRepository
	)
	{
	  parent::__construct($context);
	  $this->_dataCollection = $data;
	  $this->_featureCollection = $featureFactory;
	  $this->_specsCollection = $specsFactory;
	  $this->_product = $product;
	  $this->_productRepository = $productRepository;
	  $this->_productFactory = $productFactory;
	}
	
		 public function execute()
		{
			$collection = $this->_dataCollection->create()->getCollection();
			$dataModel = $collection->join(['w' => 'custom_global_api_product_feature'], 'main_table.item_code=w.item_id');
			
			
			foreach($dataModel->getData() as $data){
				$this->_desc[] = "<h4>".$data['heading']."</h4>";
				$this->_desc[] = "<p>".$data['description']."</p>";
			}			
			$descr[] = implode(" ", $this->_desc);
			//print_r($descr);die;

			$collectionData = $this->_dataCollection->create()->getCollection();
			$dataSpec = $collectionData->join(['h' => 'custom_global_api_product_specs'], 'main_table.item_code=h.item_id');
			//echo "<pre>";
			$spec[] = "<table border='1px'>";
			$i=1;
			foreach($dataSpec->getData() as $data1){
				$spec[] = "<tr>";
				if($i==1){
					$spec[] = "<td rowspan=3>".$data1['specs_two']."</td>";
				}
				$spec[] = "<td>".$data1['specs_three']."</td>";
				$spec[] = "<td>".$data1['specs_value']."</td>";
				$spec[] = "</tr>";
				$j = $i++;
				if($j==3){
					$i=1;
				}

			}
			$spec[] = "</table>";
			$specif[] = implode(" ", $spec);
			//print_r($specif);die;
			foreach($dataModel->getData() as $data)
			{
				
				$name = $data['name'];
				$product = $this->_productFactory->create();
				$productData = $product->loadByAttribute('name','Joust Duffle Bag');
				$url = $data['img'];
				//$desc = $data['description'];
				$productData->setApiUrl($url)
							->setDescription($descr)
							->setSpces($specif);
				 //print_r($product->getSpces());die;
				try{
				  $productData->save();
				  $saved = $productData->getData('api_url');
				  echo $saved;
				  echo "-------------------data saved";
				  die;
				}
				catch(\Exception $e)
				{
				  echo $e->getMessage();
				}
				 
				 
				 
			}

			
			
		}
			
		
}