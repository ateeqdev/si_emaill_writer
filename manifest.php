<?php

$manifest = [
  0 => [
    'acceptable_sugar_versions' => [
      0 => '',
    ],
  ],
  1 => [
    'acceptable_sugar_flavors' => [
      0 => 'CE',
      1 => 'PRO',
      2 => 'ENT',
    ],
  ],
  'readme' => '',
  'key' => 'si',
  'author' => 'StackImagine',
  'description' => 'Automate sending cold emails with followups',
  'icon' => '',
  'is_uninstallable' => true,
  'name' => 'StackImagine Campaigner',
  'published_date' => '2023-10-07 13:26:25',
  'type' => 'module',
  'version' => 1,
  'remove_tables' => 'prompt',
];


$installdefs = [
  'id' => 'StackImagine_Campaigner',
  'beans' => [
    0 =>
    [
      'module' => 'si_campaigner',
      'class' => 'si_campaigner',
      'path' => 'modules/si_campaigner/si_campaigner.php',
      'tab' => true,
    ],
  ],
  'layoutdefs' => [],
  'relationships' => [],
  'image_dir' => '<basepath>/custom/themes/default',
  'copy' => [
    [
      'from' => '<basepath>/modules/si_campaigner',
      'to' => 'modules/si_campaigner',
    ],
  ],
  'language' => [
    [
      'from' => '<basepath>/custom/Extension/application/Ext/Language/en_us.StackImagineCampaigner.php',
      'to_module' => 'application',
      'language' => 'en_us',
    ],
  ],
  'custom_fields' => [
    [
      'name'           => 'si_linkedin_profile',
      'label'          => 'LBL_LINKEDIN_PROFILE',
      'type'           => 'varchar',
      'default'        => '',
      'reportable'     => false,
      'massupdate'     => false,
      'importable'     => false,
      'module'         => 'Leads',
    ],
    [
      'name'           => 'si_linkedin_bio',
      'label'          => 'LBL_LINKEDIN_BIO',
      'type'           => 'text',
      'default'        => '',
      'reportable'     => false,
      'massupdate'     => false,
      'importable'     => false,
      'module'         => 'Leads',
    ],
    [
      'name'           => 'si_linkedin_profile',
      'label'          => 'LBL_LINKEDIN_PROFILE',
      'type'           => 'varchar',
      'default'        => '',
      'reportable'     => false,
      'massupdate'     => false,
      'importable'     => false,
      'module'         => 'Accounts',
    ],
    [
      'name'           => 'si_linkedin_bio',
      'label'          => 'LBL_LINKEDIN_BIO',
      'type'           => 'text',
      'default'        => '',
      'reportable'     => false,
      'massupdate'     => false,
      'importable'     => false,
      'module'         => 'Accounts',
    ],
    [
      'name'           => 'si_company_linkedin_profile',
      'label'          => 'LBL_COMPANY_LINKEDIN_PROFILE',
      'type'           => 'varchar',
      'default'        => '',
      'reportable'     => false,
      'massupdate'     => false,
      'importable'     => false,
      'module'         => 'Leads',
    ],
    [
      'name'           => 'si_company_linkedin_bio',
      'label'          => 'LBL_COMPANY_LINKEDIN_BIO',
      'type'           => 'text',
      'default'        => '',
      'reportable'     => false,
      'massupdate'     => false,
      'importable'     => false,
      'module'         => 'Leads',
    ],
    [
      'name'           => 'si_prompt',
      'vname'          => 'LBL_PROMPT',
      'type'           => 'text',
      'default'        => '',
      'reportable'     => false,
      'massupdate'     => false,
      'importable'     => false,
      'module'         => 'Leads',
    ],
    [
      'name'           => 'si_email_body',
      'vname'          => 'LBL_EMAIL_BODY',
      'type'           => 'text',
      'default'        => '',
      'reportable'     => false,
      'massupdate'     => false,
      'importable'     => false,
      'module'         => 'Leads',
    ],
    [
      'name'           => 'si_email_subject',
      'vname'          => 'LBL_EMAIL_SUBJECT',
      'type'           => 'varchar',
      'default'        => '',
      'reportable'     => false,
      'massupdate'     => false,
      'importable'     => false,
      'module'         => 'Leads',
    ],
    [
      'name'           => 'si_gmail_id',
      'vname'          => 'LBL_GMAIL_ID',
      'type'           => 'varchar',
      'reportable'     => false,
      'importable'     => false,
      'studio'         => false,
      'default'        => '',
      'module'         => 'Users',

    ],
  ],
];
