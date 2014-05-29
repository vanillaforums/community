<?php

if (!defined('APPLICATION'))
    exit();
/*
  Copyright 2008, 2009 Vanilla Forums Inc.
  This file is part of Garden.
  Garden is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
  Garden is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
  You should have received a copy of the GNU General Public License along with Garden.  If not, see <http://www.gnu.org/licenses/>.
  Contact Vanilla Forums Inc. at support [at] vanillaforums [dot] com
 */

class VFOrgThemeHooks implements Gdn_IPlugin {

    public function Setup() {
        return TRUE;
    }

    public function OnDisable() {
        return TRUE;
    }

    /**
     * Output random honeypot URL
     *
     * @param Gdn_Controller $sender
     */
    public function Base_Render_Before($sender) {
        $opts = [
            '<a href="http://www.vanillaforums.org/cgi-bin/badger.php"><!-- similarity-standard --></a>',
            '<a href="http://www.vanillaforums.org/cgi-bin/badger.php"><img src="similarity-standard.gif" height="1" width="1" border="0"></a>',
            '<a href="http://www.vanillaforums.org/cgi-bin/badger.php" style="display: none;">similarity-standard</a>',
            '<div style="display: none;"><a href="http://www.vanillaforums.org/cgi-bin/badger.php">similarity-standard</a></div>',
            '<a href="http://www.vanillaforums.org/cgi-bin/badger.php"></a>',
            '<!-- <a href="http://www.vanillaforums.org/cgi-bin/badger.php">similarity-standard</a> -->',
            '<div style="position: absolute; top: -250px; left: -250px;"><a href="http://www.vanillaforums.org/cgi-bin/badger.php">similarity-standard</a></div>',
            '<a href="http://www.vanillaforums.org/cgi-bin/badger.php"><span style="display: none;">similarity-standard</span></a>',
            '<a href="http://www.vanillaforums.org/cgi-bin/badger.php"><div style="height: 0px; width: 0px;"></div></a>'
        ];

        $key = array_rand($opts, 1);
        $link = $opts[$key];
        $sender->AddAsset('Foot', $link, 'Badger');
    }

//   public function DiscussionsController_Render_Before($Sender) {
//      $DevActivityModule = new RecentActivityModule($Sender);
//      $DevActivityModule->ActivityModuleTitle = T('News from the Developers');
//      $DevActivityModule->GetData(5, 16);
//      $Sender->AddModule($DevActivityModule);
//
//      $RecentActivityModule = new RecentActivityModule($Sender);
//      $RecentActivityModule->ActivityModuleTitle = T('Recent User Activity');
//      $RecentActivityModule->GetData();
//      $Sender->AddModule($RecentActivityModule);
//   }
//   public function CategoriesController_AfterBreadcrumbs_Handler($Sender, $Args) {
//      $Description = $Sender->Data('Category.Description');
//      if ($Description) {
//         echo '<div class="P">'.$Description.'</div>';
//      }
//   }

    /**
     *
     * @param PostController $Sender
     */
    public function PostController_Render_Before($Sender) {
        $Sender->Head->AddString("<script type=\"text/javascript\">
jQuery(document).ready(function($) {
   $('.HelpFormat, .HelpTags').hide();
   $('#Form_Name').focus(function() { $('.Help').hide(); $('.HelpTitle').show(); });
   $('#Form_Body').focus(function() { $('.Help').hide(); $('.HelpFormat').show(); });
   $('#Form_Tags').focus(function() { $('.Help').hide(); $('.HelpTags').show(); });
});
</script>");
    }

    /**
     * Add the "Stats" buttons to the discussion list.
     */
//	public function Base_BeforeDiscussionContent_Handler($Sender) {
//		$Session = Gdn::Session();
//		$Discussion = GetValue('Discussion', $Sender->EventArguments);
//
//		$CountVotes = 0;
//		if (is_numeric($Discussion->Score)) // && $Discussion->Score > 0)
//			$CountVotes = $Discussion->Score;
//
//		if (!is_numeric($Discussion->CountBookmarks))
//			$Discussion->CountBookmarks = 0;
//
//      echo '<div class="StatBoxes">';
//
//      // Follows
////		$Title = T($Discussion->Bookmarked == '1' ? 'Unbookmark' : 'Bookmark');
////      $CssClass2 = $Discussion->Bookmarked ? ' Bookmarked' : '';
////		if ($Session->IsValid()) {
////			echo Anchor(
////				Wrap(T('Follows'), 'div', array('class' => 'Stats-Label')) . Wrap(Gdn_Format::BigNumber($Discussion->CountBookmarks, 'html'), 'div', array('class' => 'Stats-Number CountBookmarks')),
////				'/vanilla/discussion/bookmark/'.$Discussion->DiscussionID.'/'.$Session->TransientKey().'?Target='.urlencode($Sender->SelfUrl),
////				'StatBox Bookmark'.$CssClass2,
////				array('title' => $Title));
////		} else {
////			echo ''; //Wrap(Wrap(T('Follows')) . Wrap($Discussion->CountBookmarks, 'div', array('class' => 'CountBookmarks')), 'div', array('class' => 'StatBox FollowsBox'));
////		}
//
//		// Views
//		echo Wrap(
//			// Anchor(
//			Wrap(T('Views'), 'div', array('class' => 'Stats-Label')) . Wrap(Gdn_Format::BigNumber($Discussion->CountViews, 'html'), 'div', array('class' => 'Stats-Number'))
//			// , '/discussion/'.$Discussion->DiscussionID.'/'.Gdn_Format::Url($Discussion->Name).($Discussion->CountCommentWatch > 0 ? '/#Item_'.$Discussion->CountCommentWatch : '')
//			// )
//			, 'span', array('class' => 'StatBox ViewsBox'));
//
//		echo Wrap(
//			// Anchor(
//			Wrap(T('Comments'), 'div', array('class' => 'Stats-Label')) . Wrap(Gdn_Format::BigNumber($Discussion->CountComments, 'html'), 'div', array('class' => 'Stats-Number'))
//			// ,'/discussion/'.$Discussion->DiscussionID.'/'.Gdn_Format::Url($Discussion->Name).($Discussion->CountCommentWatch > 0 ? '/#Item_'.$Discussion->CountCommentWatch : '')
//			// )
//			, 'span', array('class' => 'StatBox CommentsBox'));
//
//      echo '</div>';
//	}
}
