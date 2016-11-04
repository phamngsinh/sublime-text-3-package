<?php

class tabpreventivonewNewLayoutComponent extends sfComponent
{
  public function execute($request)
  {
    $this->form = $this->getContext()->getUser()->getFlash("PreventivoProdottoForm", new PreventivoProdottoForm());

	$email = ($this->getContext()->getUser()->getGuardUser()) ? $this->getContext()->getUser()->getGuardUser()->getUsername() : '';
    if(sfContext::getInstance()->getUser()->getCulture() != 'it_IT' && $this->getLocationInfoByIp() != null) {
      $codes = json_decode(et_get_country_list(), true);
      $this->form->setDefault('country',$codes[$this->getLocationInfoByIp()]);
    }
    sfContext::getInstance()->getRouting()->setDefaultParameter('sf_culture',$request->getParameter('sf_culture'));
    $this->form->setDefault('email',$email);
	$this->form->setDefault('confermaemail',$email);

  }
  protected function getLocationInfoByIp(){
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = @$_SERVER['REMOTE_ADDR'];
    $result  = null;
    if(filter_var($client, FILTER_VALIDATE_IP)){
      $ip = $client;
    }elseif(filter_var($forward, FILTER_VALIDATE_IP)){
      $ip = $forward;
    }else{
      $ip = $remote;
    }
    $ip_data = @json_decode
    (file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));
    if($ip_data && $ip_data->geoplugin_countryName != null){
      $result = $ip_data->geoplugin_countryCode;
    }
    return $result;
  }
}
