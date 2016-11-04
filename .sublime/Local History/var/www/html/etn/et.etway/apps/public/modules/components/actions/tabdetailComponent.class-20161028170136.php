<?php
 
class tabdetailComponent extends sfComponent
{
  public function execute($request)
  {
    $this->tab = $this->Tab;
    $this->langPrefix = commonTools::getDescrInLanguage();
    $this->periodoPrefix = commonTools::getPeriodoInLanguage();
    
    $c = new Criteria();
    $c->addJoin(ProdGalleryPeer::MULTIMEDIA_ID, MultimediaPeer::ID);
    $c->add(MultimediaPeer::TIPO, array('image','video'), Criteria::IN);
    $c->add(ProdGalleryPeer::PROD_ID, $this->tab->getProdIdPadre());
    $c->addAscendingOrderByColumn(MultimediaPeer::TIPO);
    $c->addAscendingOrderByColumn(ProdGalleryPeer::ORDINALE);
    $gallery_tmp = ProdGalleryPeer::doSelectJoinMultimedia($c);
    
    $this->gallerymain = null;
    $this->gallery_img = array();
    $this->gallery_video = array();
    foreach($gallery_tmp as $g)
    {
        switch($g->getMultimedia()->getTipo())
        {
            case 'image':
                if($this->gallerymain == null) $this->gallerymain = $g;
                $this->gallery_img[] = $g;
                break;
            case 'video':
                $this->gallery_video[] = $g;
                break;
        }
    }
    
    if(count($this->gallery_img) == 0)$this->gallery_img = null;
    if(count($this->gallery_video) == 0)$this->gallery_video = null;
    
    //start log valuta AA283-ETN/ISS6459 + AA283-ETN/ISS6569 xmas
    
    //valuta dell'utente
    $currency = $this->getContext()->getUser()->getAttribute('currency');
    $valuta = ValutaPeer::retrieveByPK($currency);
    
    //valuta di pagina 
    $c = new Criteria();
    $c->addJoin(ValutaPaesePeer::VALUTA_ID, ValutaPeer::ID);
    $c->add(ValutaPaesePeer::PAESE_ID, sfConfig::get('app_paese_object')->getId());
    $valuta_pag = ValutaPeer::doSelectOne($c);
    
    //codice da impostare quando si cambia la valuta
    $currency_s = $this->getContext()->getUser()->getAttribute('currency_setted_code');     
     
    //se sono diverse loggo
    //$url = $_SERVER['REQUEST_URI'];
    //$url_sessionid = $url . '|' . session_id();
    //exit("xcva".$url_sessionid);
    if($valuta && $valuta_pag && $valuta->getId() != $valuta_pag->getId()){
        logTools::write(
                'valuta log|'.$valuta->getCodice().'|'.$valuta_pag->getCodice().'|'.$currency_s,
                'prod', 
                $this->tab->getProdIdPadre(), 
                $_SERVER['REQUEST_URI'].'|'.session_id()
        );
    }
    //end log valuta AA283-ETN/ISS6459 + AA283-ETN/ISS6569 xmas
  }
}