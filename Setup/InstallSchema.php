<?php

namespace Appstractsoftware\MagentoAdapter\Setup;

use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{
  public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
  {
    $setup->startSetup();
    $conn = $setup->getConnection();
    $tableName = $setup->getTable('appstract_contact_form');
    if ($conn->isTableExists($tableName) != true) {
      $table = $conn->newTable($tableName)
        ->addColumn(
          'id',
          Table::TYPE_INTEGER,
          null,
          ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true]
        )
        ->addColumn(
          'topic',
          Table::TYPE_TEXT,
          255,
          ['nullable' => false, 'default' => '']
        )
        ->addColumn(
          'email',
          Table::TYPE_TEXT,
          255,
          ['nullbale' => false, 'default' => '']
        )
        ->addColumn(
          'name',
          Table::TYPE_TEXT,
          255,
          ['nullbale' => false, 'default' => '']
        )
        ->addColumn(
          'message',
          Table::TYPE_TEXT,
          '2M',
          ['nullbale' => false, 'default' => '']
        )
        ->addColumn(
          'orderId',
          Table::TYPE_TEXT,
          255,
          ['nullbale' => false, 'default' => '']
        )
        ->addColumn(
          'date',
          Table::TYPE_TIMESTAMP,
          255,
          ['nullbale' => false, 'default' => '']
        )
        ->addColumn(
          'status',
          Table::TYPE_TEXT,
          255,
          ['nullbale' => false, 'default' => 'OPEN']
        )
        ->addColumn(
          'ip',
          Table::TYPE_TEXT,
          255,
          ['nullbale' => false, 'default' => '']
        )
        ->setOption('charset', 'utf8');
      $conn->createTable($table);
    }
    $setup->endSetup();
  }
}
