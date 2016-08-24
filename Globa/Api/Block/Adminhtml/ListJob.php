<?php
namespace Globa\Api\Block;
class ListJob extends \Magento\Framework\View\Element\Template
{
 
    protected $_data;
 
    protected $_feature;
 	
	protected $_specs;
	
    protected $_resource;
 	
    protected $_dataCollection = null;
 
    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Maxime\Jobs\Model\Job $job
     * @param \Maxime\Jobs\Model\Department $department
     * @param \Magento\Framework\App\ResourceConnection $resource
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Globa\Api\Model\Data $data,
        \Globa\Api\Model\Feature $feature,
		\Globa\Api\Model\Specs $specs,
        \Magento\Framework\App\ResourceConnection $resource,
        array $data = []
    ) {
        $this->_data = $data;
        $this->_feature = $feature;
		$this->_specs = $specs;
        $this->_resource = $resource;
 
        parent::__construct(
            $context,
            $data
        );
    }
 
    /**
     * @return $this
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
 
 
        // You can put these informations editable on BO
        $title = __('We are hiring');
        $description = __('Look at the jobs we have got for you');
        $keywords = __('job,hiring');
 
        $this->getLayout()->createBlock('Magento\Catalog\Block\Breadcrumbs');
 
        if ($breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs')) {
            $breadcrumbsBlock->addCrumb(
                'jobs',
                [
                    'label' => $title,
                    'title' => $title,
                    'link' => false // No link for the last element
                ]
            );
        }
 
        $this->pageConfig->getTitle()->set($title);
        $this->pageConfig->setDescription($description);
        $this->pageConfig->setKeywords($keywords);
 
 
        $pageMainTitle = $this->getLayout()->getBlock('page.main.title');
        if ($pageMainTitle) {
            $pageMainTitle->setPageTitle($title);
        }
 
        return $this;
    }
 
    protected function _getDataCollection()
    {
        if ($this->_dataCollection === null) {
 
            $jobCollection = $this->_data->getCollection()
                ->addFieldToSelect('*')
                ->join(
                    array('department' => $this->_feature->getResource()->getMainTable()),
                    'main_table.item_code = department.'.$this->_data->getIdFieldName(),
                    array('*')
                );
 
            $this->_dataCollection = $dataCollection;
        }
        return $this->_dataCollection;
    }
 
 
    public function getLoadedJobCollection()
    {
        return $this->_getDataCollection();
    }
 
   
}