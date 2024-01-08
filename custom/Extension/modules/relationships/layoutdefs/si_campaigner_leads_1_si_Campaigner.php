<?php
 // created: 2024-01-07 18:52:11
$layout_defs["si_Campaigner"]["subpanel_setup"]['si_campaigner_leads_1'] = array (
  'order' => 100,
  'module' => 'Leads',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_SI_CAMPAIGNER_LEADS_1_FROM_LEADS_TITLE',
  'get_subpanel_data' => 'si_campaigner_leads_1',
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
