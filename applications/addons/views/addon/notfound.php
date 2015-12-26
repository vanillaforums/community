<?php if (!defined('APPLICATION')) exit();

if ($this->deliveryType() == DELIVERY_TYPE_ALL) {
    echo $this->fetchView('head');
}

?>
<div id="AddonPage">
    <div id="Content" class="container_16">
        <div class="grid_16">
            <h2>Not Found</h2>
            <p>The item you were looking for could not be found.</p>
        </div>
    </div>
    <div class="clearfix"></div>
</div>