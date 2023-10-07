<?php
require_once 'ModuleInstall/ModuleScanner.php';
require_once 'ModuleInstall/ModuleInstaller.php';
require_once 'custom/include/ModuleInstaller/CustomGridLayoutMetaDataParser.php';

define('DISABLED_PATH', 'Disabled');

class CustomModuleInstaller extends ModuleInstaller {

    function addFieldsToLayout($layoutAdditions) {

        $invalidModules = array(
            'emails',
            'kbdocuments'
        );
        foreach ($layoutAdditions as $deployedModuleName => $fieldName) {

            if (!in_array(strtolower($deployedModuleName), $invalidModules)) {

                foreach ( array(MB_EDITVIEW, MB_DETAILVIEW ) as $view) {

                    $GLOBALS['log']->debug(get_class($this) . ": adding $fieldName to $view layout for module $deployedModuleName");
                    $parser = new CustomGridLayoutMetaDataParser($view, $deployedModuleName);
                    $parser->addField( array('name' => $fieldName ));
                    $parser->handleSave(false);
                }
            }
        }
    }
}
