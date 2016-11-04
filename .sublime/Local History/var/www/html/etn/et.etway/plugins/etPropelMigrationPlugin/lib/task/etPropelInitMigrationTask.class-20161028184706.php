<?php

/**l
 * @package     etPropelMigrationPlugin
 * @subpackage  task
 * @author      Sinh Pham <smagic39@gmail.com>
 */
class etPropelInitMigrationTask extends sfPropelInitMigrationTask
{
  protected function configure()
  {
     // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      // add your own options here
    ));

    $this->namespace        = 'et';
    $this->name             = 'init-migration';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [DoNothing|INFO] task does things.
Call it with:

  [php symfony DoNothing|INFO]
EOF;
  }
   protected function execute($arguments = array(), $options = array())
  {
  	print(__CLASS__);
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    // add your code here
  }
 
}
