<?php
/**
 * @package     etPropelMigrationPlugin
 * @subpackage  task
 * @author      Sinh Pham <smagic39@gmail.com>
 */
class etPropelInitMigrationTask extends sfPropelBaseTask
{
  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('name', sfCommandArgument::REQUIRED, 'The name of the migration'),
    ));

    $this->namespace = 'et';
    $this->name = 'init-migration';
    $this->briefDescription = 'Creates a new migration class file';
  }

 
}
