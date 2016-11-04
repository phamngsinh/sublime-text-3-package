<?php

require_once dirname(__FILE__).'/../lib/contr_istatGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/contr_istatGeneratorHelper.class.php';

/**
 * contr_istat actions.
 *
 * @package    evolutiontravel
 * @subpackage contr_istat
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php,v 1.1 2011/01/26 09:56:32 paolo.cazzitti Exp $
 */
class contr_istatActions extends autoContr_istatActions
{
  protected function buildCriteria()
  {
    $criteria = parent::buildCriteria();
    
    
    $criteria->add(ContrIstatPeer::ST_DATE, time(), Criteria::LESS_EQUAL );
    $criteria->addDescendingOrderByColumn(ContrIstatPeer::ST_DATE);
    
    return $criteria;
    
  }
  
}
