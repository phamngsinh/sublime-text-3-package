<?php
require_once sfConfig::get('sf_plugins_dir').'/plugins/sfPropelMigrationsLightPlugin/lib/task/sfPropelMigrateTask.class.php';
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
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      // add your own options here
    ));
  $this->addArgument('who', sfCommandArgument::OPTIONAL, 'Who to say hello to?', 'World');


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
    // initialize the database connection
    // $databaseManager = new sfDatabaseManager($this->configuration);
    // $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    // add your code here
  }


}
