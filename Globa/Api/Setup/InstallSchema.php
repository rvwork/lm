<?php
 
namespace Globa\Api\Setup;
 
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;
 
class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
 
        // Get tutorial_simplenews table
        $tableName = $installer->getTable('custom_global_api_items');
        // Check if the table already exists
        if ($installer->getConnection()->isTableExists($tableName) != true) {
            // Create tutorial_simplenews table
            $table = $installer->getConnection()
                ->newTable($tableName)
                ->addColumn(
                    'item_id',
                    Table::TYPE_INTEGER,
                    32,
                    [
                        'nullable' => false,
                        'primary' => true
                    ],
                    'Item ID'
                )
                ->addColumn(
                    'item_code',
                    Table::TYPE_TEXT,
                    128,
                    ['nullable' => false],
                    'Item Code'
                )
                ->addColumn(
                    'sbd_id',
                    Table::TYPE_TEXT,
                    128,
                    ['nullable' => false],
                    'Sbd Id'
                )
				
				 ->addColumn(
                    'name',
                    Table::TYPE_TEXT,
                    128,
                    ['nullable' => false],
                    'Name'
                )
				
				->addColumn(
                    'date',
                    Table::TYPE_TEXT,
                    128,
                    ['nullable' => false],
                    'Date'
                )
				->addColumn(
                    'img',
                    Table::TYPE_TEXT,
                    128,
                    ['nullable' => false],
                    'Image'
                )
				->addColumn(
				'features',
                    Table::TYPE_TEXT,
                    128,
                    ['nullable' => false],
                    'Features'
				)
				->addColumn(
				'specification',
                    Table::TYPE_TEXT,
                    128,
                    ['nullable' => false],
                    'Specifications'
				)
				// ->addIndex(
					// $installer->getIdxName('custom_global_api_items', ['item_code']),
					// ['item_code']
				// )
			    ->setComment('News Table')
                ->setOption('type', 'InnoDB')
                ->setOption('charset', 'utf8');
            $installer->getConnection()->createTable($table);
        }
		
		$tableName = $installer->getTable('custom_global_api_product_feature');
        // Check if the table already exists
        if ($installer->getConnection()->isTableExists($tableName) != true) {
            // Create tutorial_simplenews table
            $table = $installer->getConnection()
                ->newTable($tableName)
                ->addColumn(
                    'feature_id',
                    Table::TYPE_INTEGER,
                    32,
                    [
                        'nullable' => false,
                        'primary' => true
                    ],
                    'Feature ID'
                )
                ->addColumn(
                    'item_id',
                    Table::TYPE_INTEGER,
                    32,
                    ['nullable' => false],
                    'Item Id'
                )
                ->addColumn(
                    'heading',
                    Table::TYPE_TEXT,
                    128,
                    ['nullable' => false],
                    'Heading'
                )
				->addColumn(
                    'description',
                    Table::TYPE_TEXT,
                    128,
                    ['nullable' => false],
                    'Description'
                )
		// ->addForeignKey(
            // $installer->getFkName('custom_global_api_product_feature', 'item_id', 'custom_global_api_items', 'item_id'),
            // 'item_id',
            // $installer->getTable('custom_global_api_items'),
            // 'item_id',
            // Table::ACTION_CASCADE
			// )
                ->setComment('Feature Table')
                ->setOption('type', 'InnoDB')
                ->setOption('charset', 'utf8');
            $installer->getConnection()->createTable($table);
        }
 
 
		$tableName = $installer->getTable('custom_global_api_product_specs');
        // Check if the table already exists
        if ($installer->getConnection()->isTableExists($tableName) != true) {
            // Create tutorial_simplenews table
            $table = $installer->getConnection()
                ->newTable($tableName)
                ->addColumn(
                    'specs_id',
                    Table::TYPE_INTEGER,
                    32,
                    [
                        'nullable' => false,
                        'primary' => true
                    ],
                    'Specs ID'
                )
                ->addColumn(
                    'item_id',
                    Table::TYPE_INTEGER,
                    32,
                    ['nullable' => false],
                    'Item Id'
                )
                ->addColumn(
                    'specs_node_onwer',
                    Table::TYPE_TEXT,
                    128,
                    ['nullable' => false],
                    'Specs Node Owner'
                )
				->addColumn(
                    'specs_one',
                    Table::TYPE_TEXT,
                    128,
                    ['nullable' => false],
                    'Specs L1'
                )
				->addColumn(
                    'specs_two',
                    Table::TYPE_TEXT,
                    128,
                    ['nullable' => false],
                    'Specs L2'
                )
				->addColumn(
                    'specs_three',
                    Table::TYPE_TEXT,
                    128,
                    ['nullable' => false],
                    'Specs L3'
                )
				->addColumn(
                    'specs_value',
                    Table::TYPE_TEXT,
                    128,
                    ['nullable' => false],
                    'Specs Value'
                )
			// ->addForeignKey(
				// $installer->getFkName('custom_global_api_product_specs', 'item_id', 'custom_global_api_items', 'item_id'),
				// 'item_id',
				// $installer->getTable('custom_global_api_items'),
				// 'item_id',
				// Table::ACTION_CASCADE
			// )
                ->setComment('spec Table')
                ->setOption('type', 'InnoDB')
                ->setOption('charset', 'utf8');
            $installer->getConnection()->createTable($table);
        }
		
		
		$tableName = $installer->getTable('custom_global_api_unique');
        // Check if the table already exists
        if ($installer->getConnection()->isTableExists($tableName) != true) {
            // Create tutorial_simplenews table
            $table = $installer->getConnection()
                ->newTable($tableName)
                ->addColumn(
                    'id',
                    Table::TYPE_INTEGER,
                    32,
                    [
                        'nullable' => false,
                        'primary' => true
                    ],
                    'ID'
                )
                ->addColumn(
                    'item_id',
                Table::TYPE_INTEGER,
                    32,
                    ['nullable' => false],
                    'Item Id'
                )
                ->addColumn(
                    'sbd_id',
                    Table::TYPE_TEXT,
                    128,
                    ['nullable' => false],
                    'Sbd Id'
                )
				->addColumn(
                    'name',
                    Table::TYPE_TEXT,
                    128,
                    ['nullable' => false],
                    'Name'
                )
				->addColumn(
                    'date',
                    Table::TYPE_TEXT,
                    128,
                    ['nullable' => false],
                    'Date'
                )
				->addColumn(
                    'image',
                    Table::TYPE_TEXT,
                    128,
                    ['nullable' => false],
                    'Image'
                )
				
			
                ->setComment('unique Table')
                ->setOption('type', 'InnoDB')
                ->setOption('charset', 'utf8');
            $installer->getConnection()->createTable($table);
        }
		
        $installer->endSetup();
    }
}
 