<?php if (!defined('APPLICATION')) exit();
$Session = Gdn::Session();
?>
<div class="SubTitleWrapper">
    <div class="SubTitle">
        <h1>Translations</h1>
    </div>
</div>
<?php
if (!property_exists($this, 'HideSearch')) {
?>
<div class="container_16">
    <div class="grid_12">
        <div class="SearchForm">
            <?php
            echo Anchor('Show All Translations', 'translations', $this->RequestMethod != 'mine' ? 'Active' : '');
            ?>
            or filter to
            <?php
            echo Anchor('My Translations', 'translations/mine', $this->RequestMethod == 'mine' ? 'Active' : '');
            ?>
            for
            <?php
            echo Anchor('Vanilla 1', 'addon/browse/all/recent/1/?Form/Keywords=translation');
            echo Anchor('Vanilla 2', 'translations'.($this->RequestMethod == 'mine' ? '/mine' : ''), 'Active');
            ?>
        </div>
    </div>
    <div class="grid_4">
        <div class="UserOptions">
            <h3>Make Your Own!</h3>
            <ul>
            <?php
                if ($Session->IsValid()) {
                    echo '<li>'.Anchor('Create a New Translation', '/translations/add').'</li>';
                } else {
                    echo '<li>'.Anchor('Sign In', '/entry/?Return=/translations', SignInPopup() ? 'SignInPopup' : '').'</li>';
                }
            ?>
            </ul>
        </div>
    </div>
</div>
<?php
}