[{block name="pers_params__email_plain_order_cust"}]
[{if $oView->showPersParam($sPersParamKey) }]
[{ $oView->getPersParamText($sPersParamKey) }] : [{ $oView->getPersParamValue($sPersParamKey,$sPersParamValue) }]
[{/if}]
[{/block}]