<?php
$viewdefs['Leads'] = array(
  'DetailView' => array(
    'templateMeta' => array(
      'includes' => array(
        0 => array(
          'file' => 'modules/Leads/Lead.js',
          'file' => 'custom/modules/Leads/js/si_Campaigner.js',
        ),
      ),
      'form' => array(
        'buttons' => array(
          'SEND_CONFIRM_OPT_IN_EMAIL' => array(
            'customCode' => '<input type="submit" class="button hidden" disabled="disabled" title="{$APP.LBL_SEND_CONFIRM_OPT_IN_EMAIL}" onclick="this.form.return_module.value=\'Leads\'; this.form.return_action.value=\'Leads\'; this.form.return_id.value=\'{$fields.id.value}\'; this.form.action.value=\'sendConfirmOptInEmail\'; this.form.module.value=\'Leads\'; this.form.module_tab.value=\'Leads\';" name="send_confirm_opt_in_email" value="{$APP.LBL_SEND_CONFIRM_OPT_IN_EMAIL}" />',
            'sugar_html' => array(
              'type' => 'submit',
              'value' => '{$APP.LBL_SEND_CONFIRM_OPT_IN_EMAIL}',
              'htmlOptions' => array(
                'class' => 'button hidden',
                'id' => 'send_confirm_opt_in_email',
                'title' => '{$APP.LBL_SEND_CONFIRM_OPT_IN_EMAIL}',
                'onclick' => 'this.form.return_module.value=\'Leads\'; this.form.return_action.value=\'DetailView\'; this.form.return_id.value=\'{$fields.id.value}\'; this.form.action.value=\'sendConfirmOptInEmail\'; this.form.module.value=\'Leads\'; this.form.module_tab.value=\'Leads\';',
                'name' => 'send_confirm_opt_in_email',
                'disabled' => true,
              ),
            ),
          ),
          0 => 'EDIT',
          1 => 'DUPLICATE',
          2 => 'DELETE',
          3 => array(
            'customCode' => '{if $bean->aclAccess("edit") && !$DISABLE_CONVERT_ACTION}<input title="{$MOD.LBL_CONVERTLEAD_TITLE}" accessKey="{$MOD.LBL_CONVERTLEAD_BUTTON_KEY}" type="button" class="button" onClick="document.location=\'index.php?module=Leads&action=ConvertLead&record={$fields.id.value}\'" name="convert" value="{$MOD.LBL_CONVERTLEAD}">{/if}',
            'sugar_html' => array(
              'type' => 'button',
              'value' => '{$MOD.LBL_CONVERTLEAD}',
              'htmlOptions' => array(
                'title' => '{$MOD.LBL_CONVERTLEAD_TITLE}',
                'accessKey' => '{$MOD.LBL_CONVERTLEAD_BUTTON_KEY}',
                'class' => 'button',
                'onClick' => 'document.location=\'index.php?module=Leads&action=ConvertLead&record={$fields.id.value}\'',
                'name' => 'convert',
                'id' => 'convert_lead_button',
              ),
              'template' => '{if $bean->aclAccess("edit") && !$DISABLE_CONVERT_ACTION}[CONTENT]{/if}',
            ),
          ),
          4 => 'FIND_DUPLICATES',
          5 => array(
            'customCode' => '<input title="{$APP.LBL_MANAGE_SUBSCRIPTIONS}" class="button" onclick="this.form.return_module.value=\'Leads\'; this.form.return_action.value=\'DetailView\';this.form.return_id.value=\'{$fields.id.value}\'; this.form.action.value=\'Subscriptions\'; this.form.module.value=\'Campaigns\'; this.form.module_tab.value=\'Leads\';" type="submit" name="Manage Subscriptions" value="{$APP.LBL_MANAGE_SUBSCRIPTIONS}">',
            'sugar_html' => array(
              'type' => 'submit',
              'value' => '{$APP.LBL_MANAGE_SUBSCRIPTIONS}',
              'htmlOptions' => array(
                'title' => '{$APP.LBL_MANAGE_SUBSCRIPTIONS}',
                'class' => 'button',
                'id' => 'manage_subscriptions_button',
                'onclick' => 'this.form.return_module.value=\'Leads\'; this.form.return_action.value=\'DetailView\';this.form.return_id.value=\'{$fields.id.value}\'; this.form.action.value=\'Subscriptions\'; this.form.module.value=\'Campaigns\'; this.form.module_tab.value=\'Leads\';',
                'name' => '{$APP.LBL_MANAGE_SUBSCRIPTIONS}',
              ),
            ),
          ),
          'AOS_GENLET' => array(
            'customCode' => '<input type="button" class="button" onClick="showPopup();" value="{$APP.LBL_PRINT_AS_PDF}">',
          ),
        ),
        'headerTpl' => 'modules/Leads/tpls/DetailViewHeader.tpl',
      ),
      'maxColumns' => '2',
      'widths' => array(
        0 => array(
          'label' => '10',
          'field' => '30',
        ),
        1 => array(
          'label' => '10',
          'field' => '30',
        ),
      ),
      'useTabs' => false,
      'tabDefs' => array(
        'LBL_CONTACT_INFORMATION' => array(
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_PANEL_ADVANCED' => array(
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_PANEL_ASSIGNMENT' => array(
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
      'syncDetailEditViews' => true,
    ),
    'panels' =>
    array(
      'LBL_CONTACT_INFORMATION' =>
      array(
        0 =>
        array(
          0 =>
          array(
            'name' => 'first_name',
            'comment' => 'First name of the contact',
            'label' => 'LBL_FIRST_NAME',
          ),
          1 =>
          array(
            'name' => 'last_name',
            'comment' => 'Last name of the contact',
            'label' => 'LBL_LAST_NAME',
          ),
        ),
        1 =>
        array(
          0 => 'title',
          1 => 'department',
        ),
        2 =>
        array(
          0 => 'email1',
        ),
        3 =>
        array(
          0 =>
          array(
            'name' => 'si_email_subject_c',
            'label' => 'LBL_SI_EMAIL_SUBJECT',
          ),
        ),
        4 =>
        array(
          0 =>
          array(
            'name' => 'si_email_body_c',
            'studio' => 'visible',
            'label' => 'LBL_SI_EMAIL_BODY',
          ),
        ),
        5 =>
        array(
          0 =>
          array(
            'name' => 'si_linkedin_profile_c',
            'label' => 'LBL_SI_LINKEDIN_PROFILE',
          ),
          1 =>
          array(
            'name' => 'si_company_linkedin_profile_c',
            'label' => 'LBL_SI_COMPANY_LINKEDIN_PROFILE',
          ),
        ),
        6 =>
        array(
          0 =>
          array(
            'name' => 'account_name',
          ),
          1 => 'website',
        ),
        7 =>
        array(
          0 =>
          array(
            'name' => 'si_linkedin_bio_c',
            'studio' => 'visible',
            'label' => 'LBL_SI_LINKEDIN_BIO',
          ),
        ),
        8 =>
        array(
          0 =>
          array(
            'name' => 'si_company_linkedin_bio_c',
            'studio' => 'visible',
            'label' => 'LBL_SI_COMPANY_LINKEDIN_BIO',
          ),
        ),
        9 =>
        array(
          0 =>
          array(
            'name' => 'primary_address_street',
            'label' => 'LBL_PRIMARY_ADDRESS',
            'type' => 'address',
            'displayParams' =>
            array(
              'key' => 'primary',
            ),
          ),
          1 =>
          array(
            'name' => 'alt_address_street',
            'label' => 'LBL_ALTERNATE_ADDRESS',
            'type' => 'address',
            'displayParams' =>
            array(
              'key' => 'alt',
            ),
          ),
        ),
        10 =>
        array(
          0 => 'description',
        ),
        11 =>
        array(
          0 => 'phone_mobile',
          1 => 'phone_work',
        ),
      ),
      'LBL_PANEL_ADVANCED' =>
      array(
        0 =>
        array(
          0 => 'status',
          1 => 'lead_source',
        ),
        1 =>
        array(
          0 => 'status_description',
          1 => 'lead_source_description',
        ),
        2 =>
        array(
          0 => 'opportunity_amount',
          1 => 'refered_by',
        ),
        3 =>
        array(
          0 =>
          array(
            'name' => 'campaign_name',
            'label' => 'LBL_CAMPAIGN',
          ),
        ),
      ),
      'LBL_PANEL_ASSIGNMENT' =>
      array(
        0 =>
        array(
          0 =>
          array(
            'name' => 'assigned_user_name',
            'label' => 'LBL_ASSIGNED_TO',
          ),
        ),
      ),
    ),
  ),
);
