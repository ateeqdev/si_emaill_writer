<?php
$hook_array['before_save'][] = array(1, 'Associate with Account based on Linkedin', 'custom/include/si_Campaigner/si_Campaigner_hook.php', 'si_CampaignerHook', 'linkAccountToLead');

$hook_array['before_save'][] = array(1, 'Set status when bio is added', 'custom/include/si_Campaigner/si_Campaigner_hook.php', 'si_CampaignerHook', 'setBioStatus');
