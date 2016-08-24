<?php
namespace Globa\Api\Controller\Adminhtml\Index;

class Index extends \Magento\Backend\App\Action
{
	protected $resultPageFactory = false;
	public function __construct(
		\Magento\Backend\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $resultPageFactory
	) {
		parent::__construct($context);
		$this->resultPageFactory = $resultPageFactory;
	}

	public function execute()
	{
		//Call page factory to render layout and page content
		$resultPage = $this->resultPageFactory->create();

		//Set the menu which will be active for this page
		$resultPage->setActiveMenu('Globa_Api::hello_manage_items');
		
		//Set the header title of grid
		$resultPage->getConfig()->getTitle()->prepend(__('Manage Items'));

		//Add bread crumb
		$resultPage->addBreadcrumb(__('GlobalApi'), __('GlobalApi'));
		$resultPage->addBreadcrumb(__('Hello World'), __('GlobalApi'));

		return $resultPage;
	}

	/*
	 * Check permission via ACL resource
	 */
	protected function _isAllowed()
	{
		return $this->_authorization->isAllowed('Globa_Api::hello_manage_items');
	}
}