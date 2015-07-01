<?php if (!defined('APPLICATION')) exit();
$this->HideSearch = TRUE;
if ($this->DeliveryType() == DELIVERY_TYPE_ALL)
    echo $this->FetchView('head');

?>
<div class="Form">
    <div class="container_12">
        <h2><?php
            $Author = new stdClass();
            $Author->UserID = $this->UserLanguage->InsertUserID;
            $Author->Name = $this->UserLanguage->InsertName;
            echo $this->UserLanguage->Name.' ('.$this->UserLanguage->Code.') by '.UserAnchor($Author);
        ?></h2>
        <div class="SearchForm">
            <p>This language definition contains <?php echo $this->UserLanguage->CountTranslations; ?> completed translations of <?php echo $this->CountTranslations; ?> total.</p>
            <?php
            echo Anchor('Browse All Translations', 'translations/edit/'.$this->UserLanguage->UserLanguageID, 'Active');
            ?> or <?php
            echo Anchor('Browse Incomplete Translations', 'translations/edit/'.$this->UserLanguage->UserLanguageID, 'Active');
            ?>
            or search for
            <?php
            $Query = GetIncomingValue('Form/Keywords', '');
            echo $this->Form->Open();
            echo $this->Form->Errors();
            echo $this->Form->TextBox('Keywords', array('value' => $Query));
            echo $this->Form->Button('Go');
            echo $this->Form->Close();
            ?>
        </div>
        <?php
        echo $this->Form->Open();
        echo $this->Form->Errors();
        ?>
        <ul>
            <?php
            $Alt = '';
            foreach ($this->TranslationData->Result() as $Translation) {
                $Alt = $Alt == '' ? ' class="Alt"' : '';
                echo '<li'.$Alt.'>'
                    .'<div>'.$Translation->Value.'</div>'
                    .$this->Form->TextBox('Value')
                .'</li>';
            }
            ?>
        </ul>
        <?php echo $this->Form->Close('Save'); ?>
    </div>
</div>