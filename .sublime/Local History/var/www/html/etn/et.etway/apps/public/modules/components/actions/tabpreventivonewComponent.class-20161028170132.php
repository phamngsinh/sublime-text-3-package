<?php
 
class tabpreventivonewComponent extends sfComponent
{
  public function execute($request)
  {
    $this->form = $this->getContext()->getUser()->getFlash("PreventivoProdottoForm", new PreventivoProdottoForm());
	
	$email = ($this->getContext()->getUser()->getGuardUser()) ? $this->getContext()->getUser()->getGuardUser()->getUsername() : '';
	
	$this->form->setDefault('email',$email);
	$this->form->setDefault('confermaemail',$email);
	
  }
}