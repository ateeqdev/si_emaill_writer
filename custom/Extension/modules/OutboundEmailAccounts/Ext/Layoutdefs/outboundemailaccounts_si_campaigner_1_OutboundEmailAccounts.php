<?php
 // created: 2024-01-07 18:51:19
$layout_defs["OutboundEmailAccounts"]["subpanel_setup"]['outboundemailaccounts_si_campaigner_1'] = array (
  'order' => 100,
  'module' => 'si_Campaigner',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_OUTBOUNDEMAILACCOUNTS_SI_CAMPAIGNER_1_FROM_SI_CAMPAIGNER_TITLE',
  'get_subpanel_data' => 'outboundemailaccounts_si_campaigner_1',
  'top_buttons' => 
  array (
    0 => 
    array (
      'widget_class' => 'SubPanelTopButtonQuickCreate',
    ),
    1 => 
    array (
      'widget_class' => 'SubPanelTopSelectButton',
      'mode' => 'MultiSelect',
    ),
  ),
);
