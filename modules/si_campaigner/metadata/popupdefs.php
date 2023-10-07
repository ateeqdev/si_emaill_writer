<?php
$popupMeta = array (
    'moduleMain' => 'si_campaigner',
    'varName' => 'si_campaigner',
    'orderBy' => 'si_campaigner.name',
    'whereClauses' => array (
  'name' => 'si_campaigner.name',
  'large_language_model' => 'si_campaigner.large_language_model',
  'follow_ups_counter' => 'si_campaigner.follow_ups_counter',
  'max_follow_ups' => 'si_campaigner.max_follow_ups',
  'assigned_user_id' => 'si_campaigner.assigned_user_id',
),
    'searchInputs' => array (
  1 => 'name',
  4 => 'large_language_model',
  5 => 'follow_ups_counter',
  6 => 'max_follow_ups',
  7 => 'assigned_user_id',
),
    'searchdefs' => array (
  'name' => 
  array (
    'name' => 'name',
    'width' => '10%',
  ),
  'large_language_model' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_LARGE_LANGUAGE_MODEL',
    'width' => '10%',
    'name' => 'large_language_model',
  ),
  'follow_ups_counter' => 
  array (
    'type' => 'int',
    'label' => 'LBL_FOLLOW_UPS_COUNTER',
    'width' => '10%',
    'name' => 'follow_ups_counter',
  ),
  'max_follow_ups' => 
  array (
    'type' => 'int',
    'label' => 'LBL_MAX_FOLLOW_UPS',
    'width' => '10%',
    'name' => 'max_follow_ups',
  ),
  'assigned_user_id' => 
  array (
    'name' => 'assigned_user_id',
    'label' => 'LBL_ASSIGNED_TO',
    'type' => 'enum',
    'function' => 
    array (
      'name' => 'get_user_array',
      'params' => 
      array (
        0 => false,
      ),
    ),
    'width' => '10%',
  ),
),
    'listviewdefs' => array (
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
    'name' => 'name',
  ),
  'LARGE_LANGUAGE_MODEL' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_LARGE_LANGUAGE_MODEL',
    'width' => '10%',
  ),
  'MAX_FOLLOW_UPS' => 
  array (
    'type' => 'int',
    'default' => true,
    'label' => 'LBL_MAX_FOLLOW_UPS',
    'width' => '10%',
  ),
  'FOLLOW_UPS_COUNTER' => 
  array (
    'type' => 'int',
    'default' => true,
    'label' => 'LBL_FOLLOW_UPS_COUNTER',
    'width' => '10%',
  ),
  'ASSIGNED_USER_NAME' => 
  array (
    'width' => '9%',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'module' => 'Employees',
    'id' => 'ASSIGNED_USER_ID',
    'default' => true,
    'name' => 'assigned_user_name',
  ),
),
);
