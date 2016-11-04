<?php
/**
 * Extend sfDoctrineGenerateMigrationTask with a new option specified db connections
 * So a new file will be stored in dir lib/migration/doctrine/[connection_name]/
 * This is particularly useful since our system has multiple connections to
 * different databases
 *
 * Command example:
 *      php symfony et:generate-migration AddNewColumnToServizi --connection=idetus
 *
 * @author steven.nguyen
 */
class etDoctrineGenerateMigrationTask extends sfDoctrineGenerateMigrationTask
{

    protected $defaultConnection = 'idetus';

    /**
     * @see sfDoctrineGenerateMigrationTask
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
     * @see sfDoctrineGenerateMigrationTask
     */
    protected function execute($arguments = array(), $options = array())
    {
        $databaseManager = new sfDatabaseManager($this->configuration);
        $config = $this->getCliConfig();

        $this->logSection('doctrine', sprintf('generating migration class named "%s"', $arguments['name']));

        // We modified the execution here, set a migration path based on specified connection
        $migrationPath = $config['migrations_path'] . '/' . $options['connection'];
        if (!file_exists($migrationPath))
        {
            $this->getFilesystem()->mkdirs($migrationPath);
        }
        // add path of file migration stored
        $this->callDoctrineCli('generate-migration', array('name' => $arguments['name'],'migrations_path'=>$migrationPath));
        $finder = sfFinder::type('file')->sort_by_name()->name('*.php');
        if ($files = $finder->in($migrationPath))
        {
            $file = array_pop($files);

            $contents = file_get_contents($file);
            $contents = strtr(sfToolkit::stripComments($contents), array(
                "{\n\n" => "{\n",
                "\n}"   => "\n}\n",
                '    '  => '  ',
            ));
            file_put_contents($file, $contents);

            if (isset($options['editor-cmd']))
            {
                $this->getFilesystem()->execute($options['editor-cmd'].' '.escapeshellarg($file));
            }
        }
    }
}
