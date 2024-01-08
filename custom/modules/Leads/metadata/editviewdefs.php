<?php
$viewdefs['Leads'] = [
  'EditView' => [
    'templateMeta' => [
      'includes' => [
        [
          'file' => 'custom/modules/Leads/js/si_Campaigner.js',
        ],
      ],
      'form' => [
        'hidden' => [
          '<input type="hidden" name="prospect_id" value="{if isset($smarty.request.prospect_id)}{$smarty.request.prospect_id}{else}{$bean->prospect_id}{/if}">',
          '<input type="hidden" name="account_id" value="{if isset($smarty.request.account_id)}{$smarty.request.account_id}{else}{$bean->account_id}{/if}">',
          '<input type="hidden" name="contact_id" value="{if isset($smarty.request.contact_id)}{$smarty.request.contact_id}{else}{$bean->contact_id}{/if}">',
          '<input type="hidden" name="opportunity_id" value="{if isset($smarty.request.opportunity_id)}{$smarty.request.opportunity_id}{else}{$bean->opportunity_id}{/if}">',
        ],
        'buttons' => ['SAVE', 'CANCEL'],
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
      'javascript' => '<script type="text/javascript" language="Javascript">function copyAddressRight(form)  {ldelim} form.alt_address_street.value = form.primary_address_street.value;form.alt_address_city.value = form.primary_address_city.value;form.alt_address_state.value = form.primary_address_state.value;form.alt_address_postalcode.value = form.primary_address_postalcode.value;form.alt_address_country.value = form.primary_address_country.value;return true; {rdelim} function copyAddressLeft(form)  {ldelim} form.primary_address_street.value =form.alt_address_street.value;form.primary_address_city.value = form.alt_address_city.value;form.primary_address_state.value = form.alt_address_state.value;form.primary_address_postalcode.value =form.alt_address_postalcode.value;form.primary_address_country.value = form.alt_address_country.value;return true; {rdelim} </script>',
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
    ],
    'panels' => [
      'LBL_CONTACT_INFORMATION' => [
        [
          [
            'name' => 'first_name',
            'customCode' => '{html_options name="salutation" id="salutation" options=$fields.salutation.options selected=$fields.salutation.value}&nbsp;<input name="first_name" id="first_name" size="25" maxlength="25" type="text" value="{$fields.first_name.value}">',
          ],
          'last_name',
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
          [
            'name' => 'si_linkedin_bio',
            'studio' => 'visible',
            'label' => 'LBL_SI_LINKEDIN_BIO',
          ],
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
            'type' => 'varchar',
            'validateDependency' => false,
            'customCode' => '<input name="account_name" id="EditView_account_name" {if ($fields.converted.value==1)}disabled="true" {/if} size="30" maxlength="255" type="text" value="{$fields.account_name.value}">',
          ],
          'website',
        ],
        [
          [
            'name' => 'primary_address_street',
            'hideLabel' => true,
            'type' => 'address',
            'displayParams' => [
              'key' => 'primary',
              'rows' => 2,
              'cols' => 30,
              'maxlength' => 150,
            ],
          ],
          [
            'name' => 'alt_address_street',
            'hideLabel' => true,
            'type' => 'address',
            'displayParams' => [
              'key' => 'alt',
              'copy' => 'primary',
              'rows' => 2,
              'cols' => 30,
              'maxlength' => 150,
            ],
          ],
        ],
        [
          'description',
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
          'status',
          'lead_source',
        ],
        [
          [
            'name' => 'status_description',
          ],
          [
            'name' => 'lead_source_description',
          ],
        ],
        [
          'opportunity_amount',
          'refered_by',
        ],
        [
          'campaign_name',
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
