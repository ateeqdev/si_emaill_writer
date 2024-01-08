<?php
$viewdefs['Leads'] = [
  'DetailView' => [
    'templateMeta' => [
      'includes' => [
        [
          'file' => 'custom/modules/Leads/js/si_Campaigner.js',
        ],
      ],
      'form' => [
        'buttons' => [
          'SEND_CONFIRM_OPT_IN_EMAIL' => [
            'customCode' => '<input type="submit" class="button hidden" disabled="disabled" title="{$APP.LBL_SEND_CONFIRM_OPT_IN_EMAIL}" onclick="this.form.return_module.value=\'Leads\'; this.form.return_action.value=\'Leads\'; this.form.return_id.value=\'{$fields.id.value}\'; this.form.action.value=\'sendConfirmOptInEmail\'; this.form.module.value=\'Leads\'; this.form.module_tab.value=\'Leads\';" name="send_confirm_opt_in_email" value="{$APP.LBL_SEND_CONFIRM_OPT_IN_EMAIL}" />',
            'sugar_html' => [
              'type' => 'submit',
              'value' => '{$APP.LBL_SEND_CONFIRM_OPT_IN_EMAIL}',
              'htmlOptions' => [
                'class' => 'button hidden',
                'id' => 'send_confirm_opt_in_email',
                'title' => '{$APP.LBL_SEND_CONFIRM_OPT_IN_EMAIL}',
                'onclick' => 'this.form.return_module.value=\'Leads\'; this.form.return_action.value=\'DetailView\'; this.form.return_id.value=\'{$fields.id.value}\'; this.form.action.value=\'sendConfirmOptInEmail\'; this.form.module.value=\'Leads\'; this.form.module_tab.value=\'Leads\';',
                'name' => 'send_confirm_opt_in_email',
                'disabled' => true,
              ],
            ],
          ],
          'EDIT',
          'DUPLICATE',
          'DELETE',
          [
            'customCode' => '{if $bean->aclAccess("edit") && !$DISABLE_CONVERT_ACTION}<input title="{$MOD.LBL_CONVERTLEAD_TITLE}" accessKey="{$MOD.LBL_CONVERTLEAD_BUTTON_KEY}" type="button" class="button" onClick="document.location=\'index.php?module=Leads&action=ConvertLead&record={$fields.id.value}\'" name="convert" value="{$MOD.LBL_CONVERTLEAD}">{/if}',
            'sugar_html' => [
              'type' => 'button',
              'value' => '{$MOD.LBL_CONVERTLEAD}',
              'htmlOptions' => [
                'title' => '{$MOD.LBL_CONVERTLEAD_TITLE}',
                'accessKey' => '{$MOD.LBL_CONVERTLEAD_BUTTON_KEY}',
                'class' => 'button',
                'onClick' => 'document.location=\'index.php?module=Leads&action=ConvertLead&record={$fields.id.value}\'',
                'name' => 'convert',
                'id' => 'convert_lead_button',
              ],
              'template' => '{if $bean->aclAccess("edit") && !$DISABLE_CONVERT_ACTION}[CONTENT]{/if}',
            ],
          ],
          'FIND_DUPLICATES',
          [
            'customCode' => '<input title="{$APP.LBL_MANAGE_SUBSCRIPTIONS}" class="button" onclick="this.form.return_module.value=\'Leads\'; this.form.return_action.value=\'DetailView\';this.form.return_id.value=\'{$fields.id.value}\'; this.form.action.value=\'Subscriptions\'; this.form.module.value=\'Campaigns\'; this.form.module_tab.value=\'Leads\';" type="submit" name="Manage Subscriptions" value="{$APP.LBL_MANAGE_SUBSCRIPTIONS}">',
            'sugar_html' => [
              'type' => 'submit',
              'value' => '{$APP.LBL_MANAGE_SUBSCRIPTIONS}',
              'htmlOptions' => [
                'title' => '{$APP.LBL_MANAGE_SUBSCRIPTIONS}',
                'class' => 'button',
                'id' => 'manage_subscriptions_button',
                'onclick' => 'this.form.return_module.value=\'Leads\'; this.form.return_action.value=\'DetailView\';this.form.return_id.value=\'{$fields.id.value}\'; this.form.action.value=\'Subscriptions\'; this.form.module.value=\'Campaigns\'; this.form.module_tab.value=\'Leads\';',
                'name' => '{$APP.LBL_MANAGE_SUBSCRIPTIONS}',
              ],
            ],
          ],
          'AOS_GENLET' => [
            'customCode' => '<input type="button" class="button" onClick="showPopup();" value="{$APP.LBL_PRINT_AS_PDF}">',
          ],
        ],
        'headerTpl' => 'modules/Leads/tpls/DetailViewHeader.tpl',
      ],
      'maxColumns' => '2',
      'widths' => [
        [
          'label' => '10',
          'field' => '30',
        ],
        [
          'label' => '10',
          'field' => '30',
        ],
      ],
      'useTabs' => false,
      'tabDefs' => [
        'LBL_CONTACT_INFORMATION' => [
          'newTab' => false,
          'panelDefault' => 'expanded',
        ],
        'LBL_PANEL_ADVANCED' => [
          'newTab' => false,
          'panelDefault' => 'expanded',
        ],
        'LBL_PANEL_ASSIGNMENT' => [
          'newTab' => false,
          'panelDefault' => 'expanded',
        ],
      ],
      'syncDetailEditViews' => true,
    ],
    'panels' => [
      'LBL_CONTACT_INFORMATION' => [
        [
          [
            'name' => 'first_name',
            'comment' => 'First name of the contact',
            'label' => 'LBL_FIRST_NAME',
          ],
          [
            'name' => 'last_name',
            'comment' => 'Last name of the contact',
            'label' => 'LBL_LAST_NAME',
          ],
        ],
        [
          'title',
          'department',
        ],
        [
          'email1',
        ],
        [
          [
            'name' => 'si_email_subject',
            'label' => 'LBL_SI_EMAIL_SUBJECT',
          ],
        ],
        [
          [
            'name' => 'si_email_body',
            'studio' => 'visible',
            'label' => 'LBL_SI_EMAIL_BODY',
          ],
        ],
        [
          [
            'name' => 'si_linkedin_profile',
            'label' => 'LBL_SI_LINKEDIN_PROFILE',
          ],
          [
            'name' => 'si_company_linkedin_profile',
            'label' => 'LBL_SI_COMPANY_LINKEDIN_PROFILE',
          ],
        ],
        [
          'description',
        ],
        [
          [
            'name' => 'si_company_description',
            'studio' => 'visible',
            'label' => 'LBL_SI_COMPANY_DESCRIPTION',
          ],
        ],
        [
          [
            'name' => 'si_emailed_at',
            'studio' => 'visible',
            'label' => 'LBL_SI_EMAILED_AT',
          ],
        ],
        [
          [
            'name' => 'account_name',
          ],
          'website',
        ],
        [
          [
            'name' => 'primary_address_street',
            'label' => 'LBL_PRIMARY_ADDRESS',
            'type' => 'address',
            'displayParams' => [
              'key' => 'primary',
            ],
          ],
          [
            'name' => 'alt_address_street',
            'label' => 'LBL_ALTERNATE_ADDRESS',
            'type' => 'address',
            'displayParams' => [
              'key' => 'alt',
            ],
          ],
        ],
        [
          'phone_mobile',
          'phone_work',
        ],
        [
          [
            'name' => 'si_campaigner_leads_1_name',
          ],
        ],
      ],
      'LBL_PANEL_ADVANCED' => [
        [
          [
            'name' => 'si_email_status',
            'label' => 'LBL_SI_EMAIL_STATUS',
          ],
          'lead_source',
        ],
        [
          'status_description',
          'lead_source_description',
        ],
        [
          'opportunity_amount',
          'refered_by',
        ],
        [
          [
            'name' => 'campaign_name',
            'label' => 'LBL_CAMPAIGN',
          ],
        ],
      ],
      'LBL_PANEL_ASSIGNMENT' => [
        [
          [
            'name' => 'assigned_user_name',
            'label' => 'LBL_ASSIGNED_TO',
          ],
        ],
      ],
    ],
  ],
];
