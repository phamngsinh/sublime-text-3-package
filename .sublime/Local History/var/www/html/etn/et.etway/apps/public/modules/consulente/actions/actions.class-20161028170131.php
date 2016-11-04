<?php

/**
 * consulente actions.
 *
 * @package    evolutiontravel
 * @subpackage consulente
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php,v 1.8 2013/02/07 10:39:31 alessandro.romanzin Exp $
 */
class consulenteActions extends etActions {

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeIndex(sfWebRequest $request) {

        $user = $this->getContext()->getUser();
        
        if ($request->isMethod('post')) {
            $this->form = new PreventivoConsulenteForm();
            $user->setFlash("PreventivoConsulenteForm", $this->form);
            $this->form->bind($request->getParameter('preventivo'));

            if ($this->form->isValid()) {
                
                if ($user->getAttribute('formuid', null) != $request->getParameter('formuid')) {
                    
                    $user->setAttribute('formuid', $request->getParameter('formuid'));

                    $sendvalues = ticketTools::createTicket($this->form->getValues(), null, $user, $request);
                }

                $user->setFlash('result', '1');

                $this->redirect('resultprom/index');
            }
        }

        return '';
    }

    public function executeCambio(sfWebRequest $request) {

        if ($request->isMethod('post') && $this->getContext()->getUser()->getGuardUser()->getPromoterRelatedByUserpromoterId()) {
            $this->cambioform = new CambioConsulenteForm();
            $this->getContext()->getUser()->setFlash("CambioConsulenteForm", $this->cambioform);

            $this->cambioform->bind($request->getParameter('cambioconsulente'));
            if ($this->cambioform->isValid()) {
                $values = $this->cambioform->getValues();

                logTools::write('request change consulente', 'sf_guard_user', $this->getContext()->getUser()->getId());

                mailTools::send($this, 'msg_cambio_consulente', array(
                    'sender' => $this->getContext()->getUser()->getGuardUser()->getUsername(),
                    'values' => $values,
                    'promoter' => sfConfig::get('app_promoter_object')->getNome(),
                    'tipo' => $values['consulente_tipo'],
                    'mailCons' => $values['consulente_email'],
                    'motiv' => $values['motivazione']
                    ), 
                    array('promoter' => $this->getContext()->getUser()->getGuardUser()->getPromoterRelatedByUserpromoterId()->getEmail())
                );

                $this->getContext()->getUser()->setFlash('resultcambio', '1');
            }
        }

        return '';
    }

}
