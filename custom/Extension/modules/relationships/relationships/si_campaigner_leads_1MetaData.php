<?php
// created: 2024-01-07 18:52:11
$dictionary["si_campaigner_leads_1"] = array (
  'true_relationship_type' => 'one-to-many',
  'from_studio' => true,
  'relationships' => 
  array (
    'si_campaigner_leads_1' => 
    array (
      'lhs_module' => 'si_Campaigner',
      'lhs_table' => 'si_Campaigner',
      'lhs_key' => 'id',
      'rhs_module' => 'Leads',
      'rhs_table' => 'leads',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'si_campaigner_leads_1_c',
      'join_key_lhs' => 'si_campaigner_leads_1si_campaigner_ida',
      'join_key_rhs' => 'si_campaigner_leads_1leads_idb',
    ),
  ),
  'table' => 'si_campaigner_leads_1_c',
  'fields' => 
  array (
    0 => 
    array (
      'name' => 'id',
      'type' => 'varchar',
      'len' => 36,
    ),
    1 => 
    array (
      'name' => 'date_modified',
      'type' => 'datetime',
    ),
    2 => 
    array (
      'name' => 'deleted',
      'type' => 'bool',
      'len' => '1',
      'default' => '0',
      'required' => true,
    ),
    3 => 
    array (
      'name' => 'si_campaigner_leads_1si_campaigner_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'si_campaigner_leads_1leads_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'si_campaigner_leads_1spk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'si_campaigner_leads_1_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'si_campaigner_leads_1si_campaigner_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'si_campaigner_leads_1_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'si_campaigner_leads_1leads_idb',
      ),
    ),
  ),
);