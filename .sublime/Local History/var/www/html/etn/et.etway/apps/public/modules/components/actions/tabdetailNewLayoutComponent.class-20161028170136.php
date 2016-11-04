<?php

class tabdetailNewLayoutComponent extends sfComponent
{
  public function execute($request)
  {
    $this->tab = $this->Tab;
    $this->langPrefix = commonTools::getDescrInLanguage();
    $this->periodoPrefix = commonTools::getPeriodoInLanguage();

    $c = new Criteria();
    $c->addJoin(ProdGalleryPeer::MULTIMEDIA_ID, MultimediaPeer::ID);
    $c->add(MultimediaPeer::TIPO, array('image','video'), Criteria::IN);
    $c->add(MultimediaPeer::VERSION, 2);
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
  }
}
