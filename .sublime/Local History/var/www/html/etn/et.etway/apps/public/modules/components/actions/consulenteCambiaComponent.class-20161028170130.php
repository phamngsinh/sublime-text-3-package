<?php
 
class consulenteCambiaComponent extends sfComponent
{
  public function execute($request)
  {
        $this->form = $this->getContext()->getUser()->getFlash("CambioConsulenteForm", new CambioConsulenteForm());
  }
}