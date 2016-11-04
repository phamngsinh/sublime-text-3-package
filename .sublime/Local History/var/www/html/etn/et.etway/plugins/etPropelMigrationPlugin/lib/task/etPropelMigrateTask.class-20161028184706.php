<?php
/**
 * @package     etPropelMigrationPlugin
 * @subpackage  task
 * @author      Sinh Pham <smagic39@gmail.com>
 */
class etPropelMigrationTask extends sfPropelMigrateTask
{
  protected function configure()
  {
     // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','backoffice'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      new sfCommandOption('feature',null,sfCommandOption::PARAMETER_REQUIRED, 'Feature of git')

      // add your own options here
    ));


    $this->namespace        = 'et';
    $this->name             = 'migrate';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [etPropel|INFO] task does things.
Call it with:

  [php symfony etPropel|INFO]
EOF;
  }
   protected function execute($arguments = array(), $options = array())
  {
     $this->logSection('say', 'Hello, '.$options['feature'].'!');
    // initialize the database connection
    // $databaseManager = new sfDatabaseManager($this->configuration);
    // $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    // add your code here
  }


}
