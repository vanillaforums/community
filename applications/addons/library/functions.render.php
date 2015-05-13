<?php if (!defined('APPLICATION')) exit();

if (!function_exists('RenderDiscussionAddonWarning')) {
function RenderDiscussionAddonWarning($AddonID, $AddonName, $AttachID) {
  $DeleteOption = '';
  if (Gdn::Session()->CheckPermission('Addons.Addon.Manage')) {
    $DeleteOption = Anchor(
                    'x',
                    'addon/detach/discussion/' . $AttachID,
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
