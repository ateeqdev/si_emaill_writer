<?php
$module_name = 'si_Campaigner';
$viewdefs[$module_name] =
  array(
    'DetailView' =>
    array(
      'templateMeta' =>
      array(
        'form' =>
        array(
          'buttons' =>
          array(
            0 => 'EDIT',
            1 => 'DUPLICATE',
            2 => 'DELETE',
            3 => 'FIND_DUPLICATES',
          ),
        ),
        'maxColumns' => '2',
        'widths' =>
        array(
          0 =>
          array(
            'label' => '10',
            'field' => '30',
          ),
          1 =>
          array(
            'label' => '10',
            'field' => '30',
          ),
        ),
        'useTabs' => false,
        'tabDefs' =>
        array(
          'DEFAULT' =>
          array(
            'newTab' => false,
            'panelDefault' => 'expanded',
          ),
        ),
        'syncDetailEditViews' => true,
      ),
      'panels' =>
      array(
        'default' =>
        array(
          0 =>
          array(
            0 => 'name',
            1 => 'assigned_user_name',
          ),
          1 =>
          array(
            0 => 'description',
          ),
          2 =>
          array(
            0 =>
            array(
              'name' => 'anthropic_key',
              'label' => 'LBL_ANTHROPIC_KEY',
            ),
            1 =>
            array(
              'name' => 'openai_key',
              'label' => 'LBL_OPENAI_KEY',
            ),
          ),
          3 =>
          array(
            0 =>
            array(
              'name' => 'large_language_model',
              'studio' => 'visible',
              'label' => 'LBL_LARGE_LANGUAGE_MODEL',
            ),
            1 =>
            array(
              'name' => 'max_followups',
              'label' => 'LBL_MAX_FOLLOWUPS',
            ),
          ),
          4 =>
          array(
            0 =>
            array(
              'name' => 'prompt',
              'studio' => 'visible',
              'label' => 'LBL_PROMPT',
            ),
          ),
        ),
      ),
    ),
  );;
