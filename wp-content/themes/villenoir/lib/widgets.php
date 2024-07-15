<?php
//Include each file from the widgets directory
foreach (glob(get_template_directory().'/lib/widgets/'."*.php") as $filename) {
    include $filename;
}
?>