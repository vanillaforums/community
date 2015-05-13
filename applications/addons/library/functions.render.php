<?php if (!defined('APPLICATION')) exit();

if (!function_exists('RenderDiscussionAddonWarning')) {
function RenderDiscussionAddonWarning($AddonID, $AddonName, $AttachID) {
  $DeleteOption = '';
  if (Gdn::Session()->CheckPermission('Addons.Addon.Manage')) {
    $DeleteOption = Wrap(
            Anchor(
                    'x',
                    'addon/detach/discussion/' . $AttachID,
                    array('class' => 'Delete')),
            'div',
            array('class' => 'Options'));
  }
  $String = Wrap(
          sprintf(
                  T('This discussion is related to the %s addon.'), Anchor(
                          $AddonName, 'addon/' . $AddonID . '/' . Gdn_Format::Url($AddonName)
                  )
          ) .
          $DeleteOption, 'div', array('class' => 'Warning AddonAttachment')
  );
  return $String;
}
}
