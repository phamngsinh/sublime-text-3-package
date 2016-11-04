<?php
/**
 * Extend sfDoctrineMigrateTask with a new option specified db connections
 * This is particularly useful since our system has multiple connections to
 * different databases
 * Command example:
 *      php symfony et:migrate --connection=idetus
 *
 * @author steven.nguyen
 */
class etDoctrineMigrateTask extends sfDoctrineMigrateTask
{

    protected $defaultConnection = 'idetus';

    /**
     * @see sfDoctrineMigrateTask
     */
    protected function configure()
    {
        parent::configure();
        // Change namespace so we can run this command as 'symfony et:migrate'
        $this->namespace = 'et';
        // Add connection option so we can migrate in different databases
        $this->addOptions(array(
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_OPTIONAL, 'Database connection.', $this->defaultConnection)
        ));
    }

    /**
     * @see sfDoctrineMigrateTask
     */
    protected function execute($arguments = array(), $options = array())
    {
        $databaseManager = new sfDatabaseManager($this->configuration);
        $config = $this->getCliConfig();

        // We modify the execution here, migration file will be loaded from the new
        // path format, e.g. lib/migration/doctrine/[connection_name]/[file_name].php
        $migrationPath = $config['migrations_path'] . '/' . $options['connection'];
        $migration = new Doctrine_Migration($migrationPath, $options['connection']);

        $from = $migration->getCurrentVersion();

        if (is_numeric($arguments['version']))
        {
          $version = $arguments['version'];
        }
        else if ($options['up'])
        {
          $version = $from + 1;
        }
        else if ($options['down'])
        {
          $version = $from - 1;
        }
        else
        {
          $version = $migration->getLatestVersion();
        }

        if ($from == $version)
        {
          $this->logSection('doctrine', sprintf('Already at migration version %s', $version));
          return;
        }

        $this->logSection('doctrine', sprintf('Migrating from version %s to %s%s', $from, $version, $options['dry-run'] ? ' (dry run)' : ''));
        try
        {
          $migration->migrate($version, $options['dry-run']);
        }
        catch (Exception $e)
        {
        }

        // render errors
        if ($migration->hasErrors())
        {
          if ($this->commandApplication && $this->commandApplication->withTrace())
          {
            $this->logSection('doctrine', 'The following errors occurred:');
            foreach ($migration->getErrors() as $error)
            {
              $this->commandApplication->renderException($error);
            }
          }
          else
          {
            $this->logBlock(array_merge(
              array('The following errors occurred:', ''),
              array_map(create_function('$e', 'return \' - \'.$e->getMessage();'), $migration->getErrors())
            ), 'ERROR_LARGE');
          }

          return 1;
        }

        $this->logSection('doctrine', 'Migration complete');
    }

}
