<?php
/**
 *
 *
 * @copyright 2009-2015 Vanilla Forums Inc.
 * @license http://www.opensource.org/licenses/gpl-2.0.php GNU GPL v2
 * @package Addons
 * @since 2.0
 */

if (!function_exists('RenderDiscussionAddonWarning')) {
function RenderDiscussionAddonWarning($AddonID, $AddonName, $AttachID) {
  $DeleteOption = '';
  if (Gdn::Session()->CheckPermission('Addons.Addon.Manage')) {
    $DeleteOption = Anchor(
                    'x',
                    'addon/detachfromdiscussion/' . $AttachID,
                    array('class' => 'Dismiss'));
  }
  $String = Wrap(
          $DeleteOption . 
          sprintf(
                  T('This discussion is related to the %s addon.'), Anchor(
                          $AddonName, 'addon/' . $AddonID . '/' . Gdn_Format::Url($AddonName)
                  )
          ), 'div', array('class' => 'Warning AddonAttachment DismissMessage')
  );
  return $String;
}
}
