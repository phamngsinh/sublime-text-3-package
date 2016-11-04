<?php

if (file_exists(dirname(__FILE__) . '/../lib/vendor/symfony-1.4.11/lib/autoload/sfCoreAutoload.class.php')) {
    require_once dirname(__FILE__) . '/../lib/vendor/symfony-1.4.11/lib/autoload/sfCoreAutoload.class.php';
}
else {
    require_once dirname(__FILE__) . '/../../phpLibrary/symfony-1.4/lib/autoload/sfCoreAutoload.class.php';
}

sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration {

    static protected $zendLoaded = false;

    public function setup() {
        $this->enablePlugins(array(
            'sfDoctrinePlugin',
            'sfFormExtraPlugin',
            'sfJqueryFormValidationPlugin',
            'zsI18nExtractTranslatePlugin',
            'ckWebServicePlugin'
        ));

				$this->enablePlugins('sfErrorNotifierPlugin');

        //ADD OUR CUSTOM CONFIGURATION FILE
        $configuration = self::getActive();
        if ($configuration && ($configuration instanceof sfApplicationConfiguration)) {
            $configCache = new sfConfigCache($configuration);
            include( $configCache->checkConfig('config/etconfig.yml') );
        }

        sfConfig::add(array(
                    'sf_upload_dir_name' => $sf_upload_dir_name = 'uploads',
                    'sf_upload_dir' => sfConfig::get('sf_web_dir') . DIRECTORY_SEPARATOR . $sf_upload_dir_name,
                ));


        $this->config_ET_agency();


        $this->getDbConf();
        //$this->dispatcher->connect('mailer.configure', array($this, 'configureMailer'));
  }

    public function configureDoctrine(Doctrine_Manager $manager) {
        sfConfig::set('doctrine_model_builder_options', array_merge(sfConfig::get('doctrine_model_builder_options', array()), array('pearStyle' => true)));
        $manager->setAttribute(Doctrine_Core::ATTR_RESULT_CACHE, new Doctrine_Cache_Array());
        $manager->setAttribute(Doctrine::ATTR_USE_DQL_CALLBACKS, TRUE);
    }

    public function configureMailer(sfEvent $event) {
        $mailer = $event->getSubject();

        $plugin = new Swift_Plugins_DecoratorPlugin(array());

        $mailer->registerPlugin($plugin);
    }

    private function getDbConf() {
        $conf = sfYaml::load(dirname(__FILE__) . '/databases.yml');

        if (isset($conf['all'])) {
            $all = $conf['all'];
        } else {
            $all = array();
        }

        if (isset($conf[sfConfig::get('sf_environment')])) {
            $all = array_merge($all, $conf[sfConfig::get('sf_environment')]);
        }

        foreach ($all as $connName => $conf) {
            $db = substr($conf['param']['dsn'], strpos($conf['param']['dsn'], 'dbname=') + strlen('dbname='));
            sfConfig::set("et_databaseName_$connName", $db);
        }
    }

    private function config_ET_agency() {

        if (!isset($_SERVER['SERVER_NAME']) || !$_SERVER['SERVER_NAME']) {
            return;
        }

        $agencyCurrent = sfConfig::get('et_agency_default', 'ETIta');

        $agencyConfigs = sfConfig::get('et_agencies');

        foreach($agencyConfigs as $agency => $config) {
            if ( isset($config['domini']) && in_array(str_replace(array('www.'), '', $_SERVER['SERVER_NAME']), $config['domini'])) {
               $agencyCurrent = $agency;
               continue;
            }
        }

        sfConfig::set('et_agency', $agencyCurrent);
        sfConfig::set('et_agency_current', $agencyConfigs[$agencyCurrent]);
    }

    static public function registerZend() {
        if (self::$zendLoaded) { return; }

        set_include_path( dirname(__FILE__) . '/../../phpLibrary/ZendFramework-1.11/library' . PATH_SEPARATOR . get_include_path());
        require_once dirname(__FILE__) . '/../../phpLibrary/ZendFramework-1.11/library/Zend/Loader/Autoloader.php';
        Zend_Loader_Autoloader::getInstance();
        self::$zendLoaded = true;
    }

}
