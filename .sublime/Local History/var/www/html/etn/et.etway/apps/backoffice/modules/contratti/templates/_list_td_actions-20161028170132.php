<td>
  <ul class="sf_admin_td_actions">
    <li class=sf_admin_action_edit>
		<a href="<?php echo url_for('@contrattiattivadisattiva?id='.$contratti->getId()); ?>"><?php echo __("Attiva / disattiva")?></a>
	</li>
	<li class="sf_admin_action_detail">
		<a href="<?php echo url_for('@contrattidetail?id='.$contratti->getId().'&pid='.$contratti->getPromoterId()); ?>"><?php echo __("Dettagli / Modifica")?></a>
	</li>
  </ul>
</td>
