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
  'description' => 'Send personalized cold emails through ChatGPT',
  'icon' => '',
  'is_uninstallable' => true,
  'name' => 'StackImagine Campaigner',
  'id' => 'StackImagine_Campaigner',
  'published_date' => '2023-10-07 13:26:25',
  'type' => 'module',
  'version' => 2,
  'remove_tables' => 'prompt',
];

$installdefs = [
  'id' => 'StackImagine_Campaigner_v2',
  'beans' => [
    0 =>
    [
      'module' => 'si_Campaigner',
      'class' => 'si_Campaigner',
      'path' => 'modules/si_Campaigner/si_Campaigner.php',
      'tab' => true,
    ],
  ],
  'layoutdefs' => [],
  'relationships' => [],
  'image_dir' => '<basepath>/custom/themes/default',
  'copy' => [
    [
      'from' => '<basepath>/modules/si_Campaigner',
      'to' => 'modules/si_Campaigner',
    ],
    [
      'from' => '<basepath>/custom',
      'to' => 'custom',
    ],
  ],
  'language' => [
    [
      'from' => '<basepath>/custom/Extension/application/Ext/Language/en_us.StackImagineCampaigner.php',
      'to_module' => 'application',
      'language' => 'en_us',
    ],
  ],
  'post_uninstall' => [
    '<basepath>/scripts/post_uninstall.php',
  ],
  'pre_uninstall' => [
    '<basepath>/scripts/pre_uninstall.php',
  ],
];
