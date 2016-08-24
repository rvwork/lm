<?php
 
namespace Globa\Api\Controller\Index;

use Magento\Framework\App\Action;

class Index extends Action\Action
{
	
	 protected $parser;
	 protected $store;
	 protected $key = 'key';
	 protected $item_id = 'ItemId';
	 protected $_scopeConfig;
	 protected $equal = '=';
	 protected $json_encode;
	 protected $decode;
	 protected $sbdid;
	 protected $img;
	 protected $name;
	 protected $pdate;
	 protected $arrData;
	 protected $dataFactory;
	 protected $featureFactory;
	 protected $specsFactory;
	 
	 protected $changingUrl;
	 
	 protected $_desc;
	 
	 protected $_uniqueCollection;
	 
	 protected $moduleDirReader;
	 
	 public function __construct(
	    Action\Context $context,
	   \Magento\Framework\Xml\Parser $parser,
	   \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfigInterface,
	   \Magento\Framework\Json\Encoder $json_encode,
	   \Magento\Framework\Json\Decoder $decode,
	   \Globa\Api\Model\DataFactory $data,
		\Globa\Api\Model\FeatureFactory $featureFactory,
		\Globa\Api\Model\SpecsFactory $specsFactory,
		\Globa\Api\Model\UniqueFactory $uniqueFactory,
	  \Magento\Store\Model\StoreManagerInterface $store
	  )
	  {
	  parent::__construct($context);
	  $this->parser = $parser;
	  $this->store = $store;
	  $this->dataFactory = $data;
	  $this->_scopeConfig = $scopeConfigInterface;
	  $this->json_encode = $json_encode;
	  $this->decode = $decode;
	  $this->featureFactory = $featureFactory;
	  $this->specsFactory = $specsFactory;
	   $this->_dataCollection = $data;
	  $this->_featureCollection = $featureFactory;
	  $this->_specsCollection = $specsFactory;
	  $this->_uniqueCollection = $uniqueFactory;
	  }
	 public function execute()
		{
	
			$itemUrl = $this->_scopeConfig->getValue('Global/api_key/global_api_item', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
			$productEnglishUrl = $this->_scopeConfig->getValue('Global/api_setting/global_api_Product', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
			$productFrenchUrl = $this->_scopeConfig->getValue('Global/api_setting_french/global_api_Product_french', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
			$key = $this->_scopeConfig->getValue('Global/api_key/global_api_key', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
			$baseUrlOfApi = $this->_scopeConfig->getValue('Global/api_key/global_api_base', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
			$stage = $this->_scopeConfig->getValue('Global/api_key/product_demo_status', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
			$service_url = $filePath = $this->moduleDirReader->getModuleDir('etc', 'GlobalWeb_Api') 
               . '/paymentschema.xml';//$baseUrlOfApi.$itemUrl.'?'.$this->key.$this->equal.$key;
			$parsedArray = $this->parser->load($service_url)->xmlToArray();
			
			
			$data = $parsedArray['output']['structure_groups']['_value']['structure_groups'][1]['_value']['structure_groups'][0]['_value']['structure_groups'];
			for($i=0;$i<=count($data);$i++)
			{
				$itemId = 282764; //$data[$i]['_value']['product']['item_id'];
				$productDetailsUrl = $baseUrlOfApi.$productEnglishUrl.'?'.$this->key.$this->equal.$key.'&'.$this->item_id.$this->equal.$itemId;
				$productDetails = $this->parser->load($productDetailsUrl)->xmlToArray();
				$jsonData = $this->json_encode->encode($productDetails,true);
				$decodData = $this->decode->decode($jsonData);
				$this->arrData = $decodData['output']['contents'];
				$this->sbdid =$this->arrData['sdb_id'];
				$this->name = $this->arrData['name'];
				$this->pdate = $this->arrData['published_date'];
				$this->img = $this->arrData['main_images']['main_image']['url'];
				if($stage==1)
				{
				$this->changingUrl = str_replace("http://ca.yamaha.com",$baseUrlOfApi,$this->img);
				}
				else
				{
					$this->changingUrl = $this->arrData['main_images']['main_image']['url'];
				}
			}
				if(!empty($this->arrData['features']))
				{
					if(is_array($this->arrData['features']))
					{
						for($k=0;$k<count($this->arrData['features']['feature']);$k++)
						{
						  $head = $this->arrData['features']['feature'][$k]['heading'];
						  $dec = $this->arrData['features']['feature'][$k]['descriptions']['description'];
						  // $featureModel = $this->_objectManager->create('Globa\Api\Model\Feature');
						  // $featureModel->setItemId($itemId);
						  // $featureModel->setHeading($head);
						  // $featureModel->setDescription($dec);
						  // $featureModel->save();
						  
						}
					}
				}
						
				if(!empty($this->arrData['specs']))
				{		
						if(is_array($this->arrData['specs']))
						{
							$spe = $this->arrData['specs']['spec_details']['spec_detail'];
							for($j=0;$j<count($spe);$j++)
							{
								$specHowner = $spe[$j]['spec_node']['spec_node_owner'];
								$spec1 = $spe[$j]['spec_node']['spec_l1'];
								$spec2 = $spe[$j]['spec_node']['spec_l2'];
								$spec3 = $spe[$j]['spec_node']['spec_l3'];
								$specValue = $spe[$j]['spec_value'];
								$specsModel = $this->_objectManager->create('Globa\Api\Model\Specs');
								// $specsModel->setItemId($itemId);
								// $specsModel->setSpecsNodeOnwer($specHowner);
								// $specsModel->setSpecsOne($spec1);
								// $specsModel->setSpecsTwo($spec2);
								// $specsModel->setSpecsThree($spec3);
								// $specsModel->setSpecsValue($specValue);
								// $specsModel->save();
							}
						}
				}
			
				
				$model = $this->_objectManager->create('Globa\Api\Model\Unique');
				// $model->setItemId($itemId);
				// $model->setSbdId($this->sbdid);
				// $model->setName($this->name);
				// $model->setDate($this->pdate);
				// $model->setImage($this->changingUrl);
				// $model->save();
				$collection = $this->_uniqueCollection->create()->getCollection();
			$dataModel = $collection->join(['w' => 'custom_global_api_product_feature'], 'main_table.item_id=w.item_id');
			
			
			foreach($dataModel->getData() as $data){
				$this->_desc[] = $data['heading'];
				$this->_desc[] = $data['description'];
			}			
			$descr[] = implode(" ", $this->_desc);
			$finalData = implode("",$descr);
			//print_r($descr);die;

			$collectionData = $this->_uniqueCollection->create()->getCollection();
			$dataSpec = $collectionData->join(['h' => 'custom_global_api_product_specs'], 'main_table.item_id=h.item_id');
			//echo "<pre>";
			$spec[] = ;
			$i=1;
			foreach($dataSpec->getData() as $data1){
				
				if($i==1){
					$spec[] = $data1['specs_two'];
				}
				$spec[] = $data1['specs_three'];
				$spec[] = $data1['specs_value'];
				$j = $i++;
				if($j==3){
					$i=1;
				}

			}
			//$spec[] = "</table>";
			$specif[] = implode(" ", $spec);
			$finalSpecs = implode(" ", $specif);
			$datModel = $this->_objectManager->create('Globa\Api\Model\Data');
			$datModel->setItemCode($itemId);
			$datModel->setSbdId($this->sbdid);
			$datModel->setName($this->name);
			$datModel->setDate($this->pdate);
			$datModel->setImg($this->changingUrl);
			$datModel->setFeatures($finalData);
			$datModel->setSpecification($finalSpecs);
			try{
			$datModel->save();
			}
			catch(\Exception $e)
				{
				  echo "saving error to data table".$e->getMessage();
				}
		   echo "----------------------------end of loop----------------------------------------------";
		}
	
	

}