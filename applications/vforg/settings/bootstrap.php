<?php
/**
 * Register library folder in autoloader
 */
Gdn_Autoloader::RegisterMap(
        Gdn_Autoloader::MAP_LIBRARY,
        Gdn_Autoloader::CONTEXT_APPLICATION,
        PATH_APPLICATIONS.DS.'vforg'.DS.'library',
        ['Extension' => 'vforg']);