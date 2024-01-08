<?php
// created: 2024-01-07 18:52:11
$dictionary["Lead"]["fields"]["si_campaigner_leads_1"] = array (
  'name' => 'si_campaigner_leads_1',
  'type' => 'link',
  'relationship' => 'si_campaigner_leads_1',
  'source' => 'non-db',
  'module' => 'si_Campaigner',
  'bean_name' => 'si_Campaigner',
  'vname' => 'LBL_SI_CAMPAIGNER_LEADS_1_FROM_SI_CAMPAIGNER_TITLE',
  'id_name' => 'si_campaigner_leads_1si_campaigner_ida',
);
$dictionary["Lead"]["fields"]["si_campaigner_leads_1_name"] = array (
  'name' => 'si_campaigner_leads_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_SI_CAMPAIGNER_LEADS_1_FROM_SI_CAMPAIGNER_TITLE',
  'save' => true,
  'id_name' => 'si_campaigner_leads_1si_campaigner_ida',
  'link' => 'si_campaigner_leads_1',
  'table' => 'si_Campaigner',
  'module' => 'si_Campaigner',
  'rname' => 'name',
);
$dictionary["Lead"]["fields"]["si_campaigner_leads_1si_campaigner_ida"] = array (
  'name' => 'si_campaigner_leads_1si_campaigner_ida',
  'type' => 'link',
  'relationship' => 'si_campaigner_leads_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_SI_CAMPAIGNER_LEADS_1_FROM_LEADS_TITLE',
);
