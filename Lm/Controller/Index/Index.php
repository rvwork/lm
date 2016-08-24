<?php

namespace Yamaha\Lm\Controller\Index;

use Magento\Framework\App\Action;
use Magento\Framework\Exception\LocalizedException;
use Symfony\Component\Config\Definition\Exception\Exception;

class Index extends Action\Action
{

	 protected $separator = '/';
	 protected $fileUpload = 'EDI_INV_';
	 protected $_scopeConfig;
	
	  public function __construct(
	    Action\Context $context,	  
	  \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfigInterface
	  
	  )
	  {
	  parent::__construct($context);
	  $this->_scopeConfig = $scopeConfigInterface;
	  }
	  
	 public function execute()
		{
			
			$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
			$ftpHelper = $objectManager->create('Yamaha\Lm\Helper\Ftp');
                        $loggerHelper = $objectManager->create('Yamaha\Util\Helper\Logger');
                        $mailSubject = 'SAP | LM';
                        //$loggerHelper->logInfo('LM Cron - START', array('subject' => $mailSubject), true);
                        try {
			$params = array('searchStr' => 'EDI_'); 
			$ftpHelper->sftpAction('download', $params);
			$dir = $this->_objectManager->get('Magento\Framework\App\Filesystem\DirectoryList');
            $this->_varPath = $dir->getPath('var');
			$localFolder = $this->_scopeConfig->getValue('edinvoice/from/local', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
			$final_dir = $this->_varPath.$this->separator.$localFolder;
			$filesArr = $ftpHelper->dirToArray($final_dir);
			print_r($filesArr);
			for($i=0;$i<count($filesArr);$i++)
			{
                            try {
				$arrOfFile[] = $filesArr[$i];
				$fileName = implode(" ",$arrOfFile);
				unset($arrOfFile);
				$subString  = substr($fileName,0,8);
				if($subString == $this->fileUpload)
				{
					$params = array('localFile'=> $fileName);
					print_r($params);
					$ftpHelper->upload('upload',$params);
				}
                            } catch (Exception $e) {
                                $errMessage = $e->getMessage() . ' ' . $e->getTraceAsString();
                                $loggerHelper->logException($errMessage, array('subject' => $mailSubject));
                            }
			}
                        } catch (Exception $e) {
                            $errMessage = $e->getMessage() . ' ' . $e->getTraceAsString();
                            $loggerHelper->logException($errMessage, array('subject' => $mailSubject));
                        }
                        //$loggerHelper->logInfo('LM Cron - END', array('subject' => $mailSubject), true);
	}



}

set_error_handler(array('\Yamaha\Util\Helper\Logger', 'commonLog'));