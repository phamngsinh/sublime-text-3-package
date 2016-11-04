<?php

class consulenteNewLayoutComponent extends sfComponent {

    public function execute($request) {

        $this->promotore = sfConfig::get('app_promoter_object');
        $this->promoterinfo = new PromoterBox($this->promotore, sfConfig::get('app_agenzia_object'), sfConfig::get('app_language_object'));
        $this->photo = $this->promoterinfo->getImmagine();

        $this->is_istituionale = ($this->promotore->getId() == sfConfig::get('app_mainpromoter_id'));
        if(!$this->is_istituionale && !strpos(!$this->photo,'promotore.jpg') !== false) {
            $oldLink   =  str_replace('uploads/promotore/logo-'.$this->promotore->getId(),'thumb/promotore/x120/y92/logo-'. $this->promotore->getId() . '-new' ,$this->photo);
            $newLink = explode('thumb',$oldLink);
            $this->photo = ETCdn::addCdnToLink('/thumb'.$newLink[1]);
        }
    }

}
