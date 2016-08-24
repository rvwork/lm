<?php

namespace Yamaha\Lm\Helper;

use Magento\Framework\App\Area;
use Magento\Framework\App\Helper\AbstractHelper;

/**
 * Import Ftp helper
 * @SuppressWarnings(PHPMD.TooManyFields)
 */
class Ftp extends AbstractHelper {

    protected $_connFtp;
    protected $_sftpConn;
    protected $_varPath;
	protected $_sftpConn2;
    const PATH_SEPARATOR = '/';

    // ftp actions
    

    // sftp actions
    public function sftpAction($type, $params = array()) {
				
        try {
			$this->connectToSftp();
            //$this->connectToSftp();
            $om = \Magento\Framework\App\ObjectManager::getInstance();
            $dir = $om->get('Magento\Framework\App\Filesystem\DirectoryList');
            $this->_varPath = $dir->getPath('var');
            // @todo following setting  might need to be done in magento common server setting itself and removed here
            ini_set('max_execution_time', 900);
            ini_set('memory_limit', '750M');
            if ($type == 'download') {
               echo  $remoteFolder = $this->scopeConfig->getValue('edinvoice/from/path', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
			    echo   $remoteFolder = (trim($remoteFolder) != '') ? $remoteFolder : '.';
                 $remoteFileLists = $this->_sftpConn->nlist($remoteFolder); //current directory
               echo  $searchStr = isset($params['searchStr']) ? $params['searchStr'] : '';
                if (is_array($remoteFileLists)) {
                    $fileExtFilterFlag = $this->scopeConfig->getValue('edinvoice/generalSetting/fileExtFilterFlag', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
                 echo   $localFolder = $this->scopeConfig->getValue('edinvoice/from/local', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
                    if ($searchStr != '') {
                        $matches = array_filter($remoteFileLists, function($var) use ($searchStr) {
                            return preg_match("/^$searchStr(.*?)/i", $var);
                        });
                        foreach ($matches as $fileName) {
                            $fileNameArr = explode('/', $fileName);
                            if (is_array($fileNameArr) && count($fileNameArr) > 0) {
                                $fileName = $fileNameArr[count($fileNameArr) - 1];
                            }
                            $fileExt = trim(substr(strrchr($fileName, '.'), 1));
                            $extCnt = explode('.', $fileName);
                            if (((!is_numeric($fileExt) || count($extCnt) > 2) && $fileExtFilterFlag)) {
                                continue;
                            }
							echo $fileName;
                            $this->_sftpConn->get($remoteFolder . self::PATH_SEPARATOR . $fileName, $this->_varPath.self::PATH_SEPARATOR  .$localFolder . self::PATH_SEPARATOR . $fileName); // remote => destination, local => source
                        }
                    } else {
                        foreach ($remoteFileLists as $fileName) {
                            $fileNameArr = explode('/', $fileName);
                            if (is_array($fileNameArr) && count($fileNameArr) > 0) {
                                $fileName = $fileNameArr[count($fileNameArr) - 1];
                            }
                            $fileExt = trim(substr(strrchr($fileName, '.'), 1));
                            $extCnt = explode('.', $fileName);
                            if ($fileName == '.' || $fileName == '..' || ((!is_numeric($fileExt) || count($extCnt) > 2) && $fileExtFilterFlag == 1)) {
                                continue;
                            }
                            $this->_sftpConn->get($remoteFolder . self::PATH_SEPARATOR . $fileName,  $localFolder . self::PATH_SEPARATOR . $fileName); // remote => destination, local => source
                        }
                    }
					
					echo "downloaded";
                          }
				
            } elseif ($type == 'upload') {
			
                  $remoteFolder = $this->scopeConfig->getValue('edinvoice/to/remote', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
                  $localFolder = $this->scopeConfig->getValue('edinvoice/to/path', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
				$remoteFolder = (trim($remoteFolder) != '') ? $remoteFolder : '.';
				
               $localPathWithFileName = $this->_varPath . self::PATH_SEPARATOR . $localFolder . self::PATH_SEPARATOR . $params['localFile'];
                 $remotePathWithFileName = $remoteFolder . self::PATH_SEPARATOR .$params['localFile'];				
                $this->_sftpConn->put($remotePathWithFileName, $localPathWithFileName, \NET_SFTP_LOCAL_FILE); // remote => destination, local => source
				echo "uploaded";
            } elseif ($type == 'delete') {
                //$this->_sftpConn->delete($params['remoteFile']);
            } elseif ($type == 'is_file_exists') {
                //$isExists = $this->_sftpConn->file_exists($params['remoteFile']);
                //return $isExists;
            } elseif ($type == 'move') {
                $exitingFileWithPath = $params['sourcePath'] . '/' . $params['existingFileName'];
                $newFileWithPath = $params['destinationPath'] . '/' . $params['newFileName'];
                $this->_sftpConn->rename($exitingFileWithPath, $newFileWithPath);
            }
        } catch (\Exception $e) {
            //$this->setErrors('sftp : ' . $e->getMessage());
        }
    }

    // connect to ftp
    public function connectToFtp() {
        try {
            if (is_object($this->_connFtp)) {
                return true;
            }
            $ftpHost = $this->scopeConfig->getValue('edinvoice/ftp/host', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            //$ftpPort = $this->scopeConfig->getValue('edinvoice/ftp/port', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            $ftpUsername = $this->scopeConfig->getValue('edinvoice/ftp/username', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            $ftpPassword = $this->scopeConfig->getValue('edinvoice/ftp/password', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            // set up basic connection
            $this->_connFtp = ftp_connect($ftpHost);
            // login with username and password

            ftp_login($this->_connFtp, $ftpUsername, $ftpPassword);
        } catch (\Exception $e) {
            //$this->setErrors('ftp : ' . $e->getMessage());
        }
    }

    // connects to sftp
    public function connectToSftp() {
        try {
            if (is_object($this->_sftpConn)) {
                return true;
            }
           echo $sftpHost = $this->scopeConfig->getValue('edinvoice/from/host', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            //$sftpPort = $this->scopeConfig->getValue('edinvoice/sftp/port', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
           echo $sftpUsername = $this->scopeConfig->getValue('edinvoice/from/username', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
           echo $sftpPassword = $this->scopeConfig->getValue('edinvoice/from/password', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
          echo  $sftpPrivateKeyFile = $this->scopeConfig->getValue('edinvoice/from/from_private_key', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
			
            if (trim($sftpPrivateKeyFile) != '') {
                $om = \Magento\Framework\App\ObjectManager::getInstance();
                $dir = $om->get('Magento\Framework\App\Filesystem\DirectoryList');
                $basePath = $dir->getPath('base');
                 $privateFileWithPath = ($sftpPrivateKeyFile[0] != self::PATH_SEPARATOR) ? $basePath . self::PATH_SEPARATOR . $sftpPrivateKeyFile : $sftpPrivateKeyFile;
                if (file_exists($privateFileWithPath)) {
				    $sftpPassword = new \Crypt_RSA();
                   $sftpPassword->loadKey(file_get_contents($privateFileWithPath));
					
                }
            }
			
            $this->_sftpConn = new \Net_SFTP($sftpHost);
            $this->_sftpConn->login($sftpUsername, $sftpPassword);
			echo "connectd photon dev";
        } catch (\Exception $e) {
            //$this->setErrors('sftp : ' . $e->getMessage());
        }
    }
	
	public function  dirToArray($dir) { 
   
   $result = array(); 

   $cdir = scandir($dir); 
   foreach ($cdir as $key => $value) 
   { 
      if (!in_array($value,array(".",".."))) 
      { 
         if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) 
         { 
            $result[$value] = dirToArray($dir . DIRECTORY_SEPARATOR . $value); 
         } 
         else 
         { 
            $result[] = $value; 
         } 
      } 
   } 
   
   return $result; 
} 
public function connectToSftp2()
{
	try {
            if (is_object($this->_sftpConn2)) 
			{
                return true;
            }
	 $sftptoHost = $this->scopeConfig->getValue('edinvoice/to/host', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
 
     $sftptoUsername = $this->scopeConfig->getValue('edinvoice/to/username', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    
     $sftptoPassword = $this->scopeConfig->getValue('edinvoice/to/password', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    
	 $sftptoPrivateKeyFile = $this->scopeConfig->getValue('edinvoice/to/to_private_key', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
			
            if (trim($sftptoPrivateKeyFile) != '') {
                $om = \Magento\Framework\App\ObjectManager::getInstance();
                $dir = $om->get('Magento\Framework\App\Filesystem\DirectoryList');
                $basePath = $dir->getPath('base');
                 $privateFileWithPath = ($sftptoPrivateKeyFile[0] != self::PATH_SEPARATOR) ? $basePath . self::PATH_SEPARATOR . $sftptoPrivateKeyFile : $sftptoPrivateKeyFile;
                if (file_exists($privateFileWithPath)) {
				    $sftptoPassword = new \Crypt_RSA();
                    $sftptoPassword->loadKey(file_get_contents($privateFileWithPath));
                }
            }
     $this->_sftpConn2 = new \Net_SFTP($sftptoHost);
     $this->_sftpConn2->login($sftptoUsername, $sftptoPassword);
	echo "connectd 2 aws";
	 } catch (\Exception $e) {
            //$this->setErrors('sftp : ' . $e->getMessage());
        }
}

public function upload($type, $params = array())
{

	if($type == 'upload')
	{	
	 $this->connectToSftp2();
     $remoteFolder = $this->scopeConfig->getValue('edinvoice/to/remote', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
     $localFolder = $this->scopeConfig->getValue('edinvoice/to/path', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
	 $remoteFolder = (trim($remoteFolder) != '') ? $remoteFolder : '.';
     $om = \Magento\Framework\App\ObjectManager::getInstance();
     $dir = $om->get('Magento\Framework\App\Filesystem\DirectoryList');
     $this->_varPath = $dir->getPath('var');
	 $localPathWithFileName = $this->_varPath . self::PATH_SEPARATOR . $localFolder . self::PATH_SEPARATOR . $params['localFile'];
	 $remotePathWithFileName = $remoteFolder . self::PATH_SEPARATOR .$params['localFile'];	
	 $this->_sftpConn2->put($remotePathWithFileName, $localPathWithFileName, \NET_SFTP_LOCAL_FILE); // remote => destination, local => source
	 echo "uploaded";
     }
}

				
}
