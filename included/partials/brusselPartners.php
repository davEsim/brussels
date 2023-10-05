<?php
$partners = new xPartners($db,"xBrusselPartners");
?>
<style>
    #extraLogos{
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #extraLogos img{
        margin: 2rem;
    }
</style>
<div class="row" id="extraLogos">
    <?=$partners->showNoTypeLogos()?>
</div>