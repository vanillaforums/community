<?php if (!defined('APPLICATION')) exit();

if (!function_exists('RenderDiscussionAddonWarning')) {
function RenderDiscussionAddonWarning($AddonID, $AddonName, $Placeholder = FALSE) {
  if($Placeholder) {
    $String = Wrap(T('This discussion is not related to any addon.'),'div', array('class' => 'Warning AddonAttachment Hidden'));
  }
  else {
    $String = Wrap(
            sprintf(
                    T('This discussion is related to the %s addon.'), Anchor(
                            $AddonName, 'addon/' . $AddonID . '/' . Gdn_Format::Url($AddonName)
                    )
            ), 'div', array('class' => 'Warning AddonAttachment')
    );
  }
    return $String;
  }
}
