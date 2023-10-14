<?php
$dashletData['si_campaignerDashlet']['searchFields'] = array (
  'name' => 
  array (
    'default' => '',
  ),
  'max_follow_ups' => 
  array (
    'default' => '',
  ),
  'follow_ups_counter' => 
  array (
    'default' => '',
  ),
  'date_entered' => 
  array (
    'default' => '',
  ),
  'date_modified' => 
  array (
    'default' => '',
  ),
  'assigned_user_id' => 
  array (
    'default' => '',
  ),
);
$dashletData['si_campaignerDashlet']['columns'] = array (
  'name' => 
  array (
    'width' => '40%',
    'label' => 'LBL_LIST_NAME',
    'link' => true,
    'default' => true,
    'name' => 'name',
  ),
  'date_modified' => 
  array (
    'width' => '15%',
    'label' => 'LBL_DATE_MODIFIED',
    'name' => 'date_modified',
    'default' => false,
  ),
  'created_by' => 
  array (
    'width' => '8%',
    'label' => 'LBL_CREATED',
    'name' => 'created_by',
    'default' => false,
  ),
  'follow_ups_counter' => 
  array (
    'type' => 'int',
    'default' => false,
    'label' => 'LBL_FOLLOW_UPS_COUNTER',
    'width' => '10%',
    'name' => 'follow_ups_counter',
  ),
  'max_follow_ups' => 
  array (
    'type' => 'int',
    'default' => false,
    'label' => 'LBL_MAX_FOLLOW_UPS',
    'width' => '10%',
    'name' => 'max_follow_ups',
  ),
  'large_language_model' => 
  array (
    'type' => 'enum',
    'default' => false,
    'studio' => 'visible',
    'label' => 'LBL_LARGE_LANGUAGE_MODEL',
    'width' => '10%',
    'name' => 'large_language_model',
  ),
  'assigned_user_name' => 
  array (
    'width' => '8%',
    'label' => 'LBL_LIST_ASSIGNED_USER',
    'name' => 'assigned_user_name',
    'default' => false,
  ),
);
