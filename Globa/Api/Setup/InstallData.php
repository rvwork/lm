<?php
namespace Globa\Api\Setup;
 
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
 
class InstallData implements InstallDataInterface
{
    /**
     * EAV setup factory
     *
     * @var EavSetupFactory
     */
    private $eavSetupFactory;
 
    /**
     * Init
     *
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }
 
    /**
     * {@inheritdoc}
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
 
        /** @var \Magento\Eav\Setup\EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
 
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'api_url',
            [
                'type' => 'varchar',
                'input' => 'text',
                'label' => 'Api Url',
                'required' => false,
                'user_defined' => true,
                'global' => 1,
                'group' => 'General',
            ]			
        );
		$eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'spces',
            [
                'type' => 'text',
                'input' => 'text',
                'label' => 'Specs',
                'required' => false,
                'user_defined' => true,
                'global' => 1,
                'group' => 'General',
            ]
			
        );
        $setup->endSetup();
    }
}