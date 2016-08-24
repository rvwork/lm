<?php

namespace Yamaha\Lm\Cron;

use Magento\Framework\Exception\LocalizedException;
use Symfony\Component\Config\Definition\Exception\Exception;
use \Yamaha\Lm\Controller\Index\Index as LmCtrl;
use Magento\Framework\Stdlib\DateTime\DateTime;

class Lm {

    protected $_logger;
    protected $_lm;
    protected $dateTime;

    public function __construct(
    LmCtrl $lm, \Psr\Log\LoggerInterface $logger, DateTime $dateTime
    ) {
        $this->_logger = $logger;
        $this->_lm = $lm;
        $this->dateTime = $dateTime;
    }

    // Execute LM cron
    public function execute() {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $loggerHelper = $objectManager->create('Yamaha\Util\Helper\Logger');
        try {
            $mailSubject = 'SAP | LM';
            $loggerHelper->logInfo('LM CRON START', array('subject' => $mailSubject, 'name' => 'SAP - LM CRON START'), true);
            $this->_logger->info("LM cron started");
            //log starting time
            $this->_logger->info("Start time = " . date("h:i:sa")); //$this->dateTime->gmtDate());
            //run cron to LM
            try {
                $loggerHelper->logInfo('LM START', array('subject' => $mailSubject, 'name' => 'LM START'), true);
                $this->_lm->execute();
                $loggerHelper->logInfo('LM END', array('subject' => $mailSubject, 'name' => 'LM END'), true);
            } catch (LocalizedException $lex) {
                $errMessage = $lex->getMessage() . ' ' . $lex->getTraceAsString();
                $loggerHelper->logException($errMessage, array('subject' => $mailSubject, 'name' => 'SAP LM Cron'));
                $this->_logger->error($lex->getMessage());
            } catch (Exception $ex) {
                $errMessage = $ex->getMessage() . ' ' . $ex->getTraceAsString();
                $loggerHelper->logException($errMessage, array('subject' => $mailSubject, 'name' => 'SAP LM Cron'));
                $this->_logger->error($ex->getMessage());
            }
            $this->_logger->info("LM cron end");
            $this->_logger->info("sap LM file cron finished");
            $this->_logger->info("End time = " . date("h:i:sa")); //$this->dateTime->gmtDate());
        } catch (LocalizedException $lex) {
            $errMessage = $lex->getMessage() . ' ' . $lex->getTraceAsString();
            $loggerHelper->logException($errMessage, array('subject' => $mailSubject, 'name' => 'SAP LM Cron'));
            $this->_logger->error($lex->getMessage());
        } catch (Exception $ex) {
            $errMessage = $ex->getMessage() . ' ' . $ex->getTraceAsString();
            $loggerHelper->logException($errMessage, array('subject' => $mailSubject, 'name' => 'SAP LM Cron'));
            $this->_logger->error($ex->getMessage());
        }
        $loggerHelper->logInfo('LM Cron END', array('subject' => $mailSubject, 'name' => 'SAP LM Cron END'), true);
    }

}

set_error_handler(array('\Yamaha\Util\Helper\Logger', 'commonLog'));
