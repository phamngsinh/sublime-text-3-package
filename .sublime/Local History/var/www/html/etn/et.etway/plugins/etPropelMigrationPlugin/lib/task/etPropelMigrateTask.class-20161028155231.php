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
    $this->namespace = 'propel';
    $this->name = 'migrate';
    $this->briefDescription = 'Migrates the database schema to another version';
  }

  protected function execute($arguments = array(), $options = array())
  {
    $autoloader = sfSimpleAutoload::getInstance();
    $autoloader->addDirectory(sfConfig::get('sf_plugins_dir').'/sfPropelMigrationsLightPlugin/lib');

    $configuration = ProjectConfiguration::getApplicationConfiguration($arguments['application'], $options['env'], true);

    $databaseManager = new sfDatabaseManager($configuration);

    $migrator = new sfMigrator;

    if (isset($arguments['schema-version']) && ctype_digit($arguments['schema-version']))
    {
      $runMigrationsCount = $migrator->migrate((int) $arguments['schema-version']);
    }
    else
    {
      $runMigrationsCount = $migrator->migrate();
    }

    $currentVersion = $migrator->getCurrentVersion();

    $this->logSection('migrations', 'migrated '.$runMigrationsCount.' step(s)');
    $this->logSection('migrations', 'current database version: '.$currentVersion);
  }
}
