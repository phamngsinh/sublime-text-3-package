<?php
/**
 * @package     etPropelMigrationPlugin
 * @subpackage  task
 * @author      Sinh Pham <smagic39@gmail.com>
 */
class etPropelMigrationTask extends sfPropelBaseTask
{
  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('application', sfCommandArgument::REQUIRED, 'The application name'),
      new sfCommandArgument('schema-version', sfCommandArgument::OPTIONAL, 'The target schema version'),
    ));

    $this->addOptions(array(
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
    ));

    $this->aliases = array('migrate');
    $this->namespace = 'et';
    $this->name = 'migrate';
    $this->briefDescription = 'Migrates the database schema to another version';
  }


}
