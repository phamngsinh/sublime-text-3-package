<?php

class etSendMessageTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'etgest'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'idetus'),
      // add your own options here
    ));

    $this->namespace        = 'et';
    $this->name             = 'sendMessage';
    $this->briefDescription = 'Send message for Docuementi and Servizi';
    $this->detailedDescription = <<<EOF
The [et:sendMessage|INFO] task does things.
Call it with:

  [php symfony et:sendMessage|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    sfContext::createInstance($this->configuration);

    $documenti = Doctrine::getTable('Documento')->findByDql('tipoInvio IN ("insert", "update", "save")');
    $messageCount = array();
    foreach( $documenti as $documento ) {
        
        $v = $documento->Viaggio;
        $c = $documento->Viaggio->Cliente;
        
        echo get_class($documento) . ' ' . $documento->id . ' ' . $documento->tipoInvio . ' V ' . $v->id_viaggio . ' C ' . $c->id_anagrafica . "\r\n";
        
        if ( ($result = $documento->sendMessage()) && $result === true ) {
           $messageCount[ $documento->getOid() ] = 'Sended';
        } else {
           $messageCount[ $documento->getOid() ] = $result;
        }
        
        echo $result === true ? 'result = true' : 'result <> true';
        
        echo print_r($result, true) . "\r\n";
        
    }
  }
}
