<?php
$popupMeta = array(
  'moduleMain' => 'si_Campaigner',
  'varName' => 'si_Campaigner',
  'orderBy' => 'si_Campaigner.name',
  'whereClauses' => array(
    'name' => 'si_Campaigner.name',
    'large_language_model' => 'si_Campaigner.large_language_model',
    'followups_counter' => 'si_Campaigner.followups_counter',
    'max_followups' => 'si_Campaigner.max_followups',
    'assigned_user_id' => 'si_Campaigner.assigned_user_id',
  ),
  'searchInputs' => array(
    1 => 'name',
    4 => 'large_language_model',
    5 => 'followups_counter',
    6 => 'max_followups',
    7 => 'assigned_user_id',
  ),
  'searchdefs' => array(
    'name' =>
    array(
      'name' => 'name',
      'width' => '10%',
    ),
    'large_language_model' =>
    array(
      'type' => 'enum',
      'studio' => 'visible',
      'label' => 'LBL_LARGE_LANGUAGE_MODEL',
      'width' => '10%',
      'name' => 'large_language_model',
    ),
    'followups_counter' =>
    array(
      'type' => 'int',
      'label' => 'LBL_FOLLOWUPS_COUNTER',
      'width' => '10%',
      'name' => 'followups_counter',
    ),
    'max_followups' =>
    array(
      'type' => 'int',
      'label' => 'LBL_MAX_FOLLOWUPS',
      'width' => '10%',
      'name' => 'max_followups',
    ),
    'assigned_user_id' =>
    array(
      'name' => 'assigned_user_id',
      'label' => 'LBL_ASSIGNED_TO',
      'type' => 'enum',
      'function' =>
      array(
        'name' => 'get_user_array',
        'params' =>
        array(
          0 => false,
        ),
      ),
      'width' => '10%',
    ),
  ),
  'listviewdefs' => array(
    'NAME' =>
    array(
      'width' => '32%',
      'label' => 'LBL_NAME',
      'default' => true,
      'link' => true,
      'name' => 'name',
    ),
    'LARGE_LANGUAGE_MODEL' =>
    array(
      'type' => 'enum',
      'default' => true,
      'studio' => 'visible',
      'label' => 'LBL_LARGE_LANGUAGE_MODEL',
      'width' => '10%',
    ),
    'MAX_FOLLOWUPS' =>
    array(
      'type' => 'int',
      'default' => true,
      'label' => 'LBL_MAX_FOLLOWUPS',
      'width' => '10%',
    ),
    'FOLLOWUPS_COUNTER' =>
    array(
      'type' => 'int',
      'default' => true,
      'label' => 'LBL_FOLLOWUPS_COUNTER',
      'width' => '10%',
    ),
    'ASSIGNED_USER_NAME' =>
    array(
      'width' => '9%',
      'label' => 'LBL_ASSIGNED_TO_NAME',
      'module' => 'Employees',
      'id' => 'ASSIGNED_USER_ID',
      'default' => true,
      'name' => 'assigned_user_name',
    ),
  ),
);
