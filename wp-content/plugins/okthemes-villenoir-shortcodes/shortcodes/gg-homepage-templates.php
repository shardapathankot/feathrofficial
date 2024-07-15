<?php

add_filter( 'vc_load_default_templates', 'villenoir_template_modify_array' ); // Hook in
function villenoir_template_modify_array( $data ) {
    return array(); // This will remove all default templates. Basically you should use native PHP functions to modify existing array and then return it.
}

//Homepage v1
add_filter( 'vc_load_default_templates', 'villenoir_custom_template_homepage_v1' ); // Hook in
function villenoir_custom_template_homepage_v1( $data ) {
    $template               = array();
    $template['name']       = esc_html__( 'Homepage', 'okthemes-villenoir-shortcodes' ); // Assign name for your custom template
    $template['custom_class'] = 'custom_template_homepage_v1'; // CSS class name
    $template['content']    = <<<CONTENT
        [vc_row full_width="stretch_row_content_no_spaces" equal_height="yes" content_placement="middle" css=".vc_custom_1460549184095{padding-top: 0px !important;padding-bottom: 0px !important;background-position: center !important;background-repeat: no-repeat !important;background-size: contain !important;}" el_class="gg-force-background-left"][vc_column offset="vc_col-lg-6 vc_col-md-6"][vc_single_image image="1058" img_size="full" css=".vc_custom_1469107422421{margin-bottom: 0px !important;}"][/vc_column][vc_column css=".vc_custom_1460549807462{margin-bottom: 0px !important;padding-top: 20% !important;padding-right: 20% !important;padding-bottom: 20% !important;padding-left: 30px !important;}" offset="vc_col-lg-6 vc_col-md-6"][title_subtitle add_subtitle="use_subtitle" title="A New Generation of Winemakers" subtitle="Message from Villenoir" margin_bottom="90"][vc_column_text]Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur sit amet elit leo.

Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. .[/vc_column_text][vc_single_image image="1049" img_size="full" css=".vc_custom_1469106911371{margin-top: 90px !important;margin-bottom: 0px !important;}"][/vc_column][/vc_row][vc_row full_width="stretch_row" css=".vc_custom_1458035087061{padding-bottom: 35px !important;background-color: #f1f1f1 !important;}"][vc_column css=".vc_custom_1460628359633{margin-top: 0px !important;margin-right: 0px !important;margin-bottom: 0px !important;margin-left: 0px !important;padding-top: 0px !important;padding-right: 0px !important;padding-bottom: 0px !important;padding-left: 0px !important;}" offset="vc_col-lg-6 vc_col-md-6"][vc_single_image image="1053" img_size="full" css=".vc_custom_1469107148386{margin-top: 0px !important;margin-right: 0px !important;margin-bottom: 0px !important;margin-left: 0px !important;padding-top: 0px !important;padding-right: 0px !important;padding-bottom: 0px !important;padding-left: 0px !important;}"][/vc_column][vc_column css=".vc_custom_1460628348165{padding-right: 15% !important;padding-left: 30px !important;}" offset="vc_col-lg-6 vc_col-md-6"][title_subtitle title_type="h2" title="2015" margin_bottom="5"][title_subtitle add_subtitle="use_subtitle" title="Villenoir Cabernet Sauvignon" subtitle="Current release" margin_bottom="90"][vc_column_text css=".vc_custom_1457619221167{margin-bottom: 90px !important;}"]Served well-chilled our authentically made Villenoir Cabernet Sauvignon is a refreshingly delicate dry wine with hints of strawberry, citrus, and peach laced fruit.[/vc_column_text][vc_btn title="Discover" style="custom" custom_background="#b0976d" custom_text="#ffffff" shape="square" link="url:%23||" css=".vc_custom_1458032346984{margin-right: 30px !important;margin-bottom: 0px !important;}"][vc_btn title="View all" style="custom" custom_background="#000000" custom_text="#ffffff" shape="square" link="url:%23||" css=".vc_custom_1458032354177{margin-bottom: 0px !important;}"][vc_column_text css=".vc_custom_1458032377294{margin-top: 90px !important;}"]Speak to a customer care specialist at:
FR 555 555 0005.[/vc_column_text][/vc_column][/vc_row][vc_row equal_height="yes" content_placement="middle"][vc_column css=".vc_custom_1469107067587{padding-top: 20% !important;padding-right: 20% !important;padding-bottom: 20% !important;padding-left: 20% !important;background-image: url(http://okthemes.com/villenoirdemo/wp-content/uploads/2016/03/box-promo-pattern.png?id=1051) !important;background-position: 0 0 !important;background-repeat: repeat !important;}" offset="vc_col-lg-6 vc_col-md-6"][vc_single_image image="1050" img_size="full" alignment="center"][title_subtitle align="center" add_subtitle="use_subtitle" title="We thrive in making fine wines that enrich the taste&soul." subtitle="Current release" margin_bottom="90" title_font_color="#ffffff" subtitle_font_color="#cebc9e"][/vc_column][vc_column css=".vc_custom_1460369664258{margin-top: 0px !important;margin-right: 0px !important;margin-bottom: 0px !important;margin-left: 0px !important;padding-top: 0px !important;padding-right: 0px !important;padding-bottom: 0px !important;padding-left: 0px !important;}" offset="vc_col-lg-6 vc_col-md-6"][vc_single_image image="1045" img_size="full" css=".vc_custom_1469106660194{margin-bottom: 0px !important;}"][/vc_column][/vc_row][vc_row full_width="stretch_row_content_no_spaces" equal_height="yes" content_placement="middle" css=".vc_custom_1457623848687{background-color: #f1f1f1 !important;}"][vc_column width="7/12" offset="vc_col-lg-8 vc_col-md-8" css=".vc_custom_1460370683208{padding-right: 60px !important;}"][vc_single_image image="1055" img_size="full" el_class="force-90-width-large-screen"][vc_row_inner][vc_column_inner width="1/3"][counter align="center" subtitle="varietals" number="5" title="Varietals"][/vc_column_inner][vc_column_inner width="1/3"][counter align="center" subtitle="varietals" number="162" title="Wine produced"][/vc_column_inner][vc_column_inner width="1/3"][counter align="center" subtitle="varietals" number="48" title="Awards won"][/vc_column_inner][/vc_row_inner][/vc_column][vc_column width="5/12" css=".vc_custom_1460370690007{padding-top: 20% !important;padding-right: 20% !important;padding-bottom: 20% !important;padding-left: 20% !important;background: #ffffff url(http://okthemes.com/villenoirdemo/wp-content/uploads/2016/03/triangles-icn.png?id=315) !important;background-position: 0 0 !important;background-repeat: no-repeat !important;}" offset="vc_col-lg-4 vc_col-md-4"][title_subtitle title_type="h2" add_subtitle="use_subtitle" title="The Reds" subtitle="Varieties"][list list_style="line"]

Cabernet Sauvignon
Merlot
Pinot Noir
[/list][vc_btn title="Shop now" style="custom" custom_background="#000000" custom_text="#ffffff" shape="square" link="url:%23||" css=".vc_custom_1460143100643{margin-bottom: 0px !important;}"][/vc_column][/vc_row][vc_row full_width="stretch_row_content_no_spaces" equal_height="yes" content_placement="middle" css=".vc_custom_1458038409405{padding-top: 0px !important;background-color: #f1f1f1 !important;}"][vc_column width="5/12" css=".vc_custom_1460370625769{padding-top: 20% !important;padding-right: 20% !important;padding-bottom: 20% !important;padding-left: 20% !important;background: #ffffff url(http://okthemes.com/villenoirdemo/wp-content/uploads/2016/03/triangles-icn.png?id=315) !important;background-position: 0 0 !important;background-repeat: no-repeat !important;}" offset="vc_col-lg-4 vc_col-md-4"][title_subtitle title_type="h2" add_subtitle="use_subtitle" title="The Whites" subtitle="Varieties"][list list_style="line"]

Chardonnay
Sauvignon Blanc
Riesling
[/list][vc_btn title="Shop now" style="custom" custom_background="#000000" custom_text="#ffffff" shape="square" link="url:%23||" css=".vc_custom_1460143118078{margin-bottom: 0px !important;}"][/vc_column][vc_column width="7/12" offset="vc_col-lg-8 vc_col-md-8" css=".vc_custom_1460370664276{padding-left: 60px !important;}"][vc_single_image image="1056" img_size="full" alignment="right" el_class="force-90-width-large-screen"][vc_row_inner][vc_column_inner width="1/3"][counter align="center" subtitle="varietals" number="5" title="Varietals"][/vc_column_inner][vc_column_inner width="1/3"][counter align="center" subtitle="varietals" number="162" title="Wine produced"][/vc_column_inner][vc_column_inner width="1/3"][counter align="center" subtitle="varietals" number="48" title="Awards won"][/vc_column_inner][/vc_row_inner][/vc_column][/vc_row][vc_row equal_height="yes" content_placement="middle" css=".vc_custom_1457626387315{padding-bottom: 0px !important;}"][vc_column css=".vc_custom_1460370846525{margin-top: 0px !important;margin-right: 0px !important;margin-bottom: 0px !important;margin-left: 0px !important;padding-top: 0px !important;padding-right: 0px !important;padding-bottom: 0px !important;padding-left: 0px !important;}" offset="vc_col-lg-6 vc_col-md-6"][vc_single_image image="1046" img_size="full" css=".vc_custom_1469106688328{margin-top: 0px !important;margin-right: 0px !important;margin-bottom: 0px !important;margin-left: 0px !important;padding-top: 0px !important;padding-right: 0px !important;padding-bottom: 0px !important;padding-left: 0px !important;}"][/vc_column][vc_column css=".vc_custom_1460370835378{padding-top: 20% !important;padding-right: 20% !important;padding-bottom: 20% !important;padding-left: 20% !important;background-color: #f1f1f1 !important;}" offset="vc_col-lg-6 vc_col-md-6"][title_subtitle align="center" add_subtitle="use_subtitle" title="Tasting & Tours" subtitle="The estate" margin_bottom="90"][vc_column_text css=".vc_custom_1457626016290{margin-bottom: 90px !important;}"]

Lorem ipsum dolor sit amet, consectetur adipiscing. Cras vel iaculis urna. Cras bibendum ex id dolor facilisis, in tempor lacus dapibus.

[/vc_column_text][vc_btn title="Read more" style="custom" custom_background="#000000" custom_text="#ffffff" shape="square" align="center" link="url:%23||"][/vc_column][/vc_row][vc_row equal_height="yes" content_placement="middle" css=".vc_custom_1457626404907{padding-top: 0px !important;}"][vc_column css=".vc_custom_1460370856484{padding-top: 20% !important;padding-right: 20% !important;padding-bottom: 20% !important;padding-left: 20% !important;background-color: #f1f1f1 !important;}" offset="vc_col-lg-6 vc_col-md-6"][title_subtitle align="center" add_subtitle="use_subtitle" title="Weddings & Private Events" subtitle="The estate"][vc_column_text css=".vc_custom_1457626016290{margin-bottom: 90px !important;}"]

Lorem ipsum dolor sit amet, consectetur adipiscing. Cras vel iaculis urna. Cras bibendum ex id dolor facilisis, in tempor lacus dapibus.

[/vc_column_text][vc_btn title="Read more" style="custom" custom_background="#000000" custom_text="#ffffff" shape="square" align="center" link="url:%23||"][/vc_column][vc_column css=".vc_custom_1460370866457{margin-top: 0px !important;margin-right: 0px !important;margin-bottom: 0px !important;margin-left: 0px !important;padding-top: 0px !important;padding-right: 0px !important;padding-bottom: 0px !important;padding-left: 0px !important;}" offset="vc_col-lg-6 vc_col-md-6"][vc_single_image image="1047" img_size="full" css=".vc_custom_1469106707986{margin-top: 0px !important;margin-right: 0px !important;margin-bottom: 0px !important;margin-left: 0px !important;padding-top: 0px !important;padding-right: 0px !important;padding-bottom: 0px !important;padding-left: 0px !important;}"][/vc_column][/vc_row][vc_row full_width="stretch_row" css=".vc_custom_1457626757165{background-color: #f1f1f1 !important;}"][vc_column][widget_instagram username="@jordanwinery" link="Follow us" number="6" followers="24K" title="Instagram"][/vc_column][/vc_row][vc_row full_width="stretch_row" content_placement="middle" css=".vc_custom_1469107465065{background-image: url(http://okthemes.com/villenoirdemo/wp-content/uploads/2016/03/visit-us-back.jpg?id=1059) !important;background-position: center !important;background-repeat: no-repeat !important;background-size: cover !important;}" el_class="center-text"][vc_column css=".vc_custom_1460548154083{padding-top: 0px !important;}" offset="vc_col-lg-offset-3 vc_col-lg-6 vc_col-md-12"][title_subtitle align="center" add_subtitle="use_subtitle" title="Visit The Estate" subtitle="The estate"][vc_column_text css=".vc_custom_1458042923071{margin-bottom: 90px !important;}"]

Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas ac vehicula diam. Sed efficitur ullamcorper risus at imperdiet.

[/vc_column_text][vc_btn title="Gallery" style="custom" custom_background="#b0976d" custom_text="#ffffff" shape="square" link="url:%23||" css=".vc_custom_1458044763458{margin-right: 30px !important;margin-bottom: 0px !important;}"][vc_btn title="The Estate" style="custom" custom_background="#000000" custom_text="#ffffff" shape="square" link="url:%23||" css=".vc_custom_1458044772020{margin-bottom: 0px !important;}"][/vc_column][/vc_row][vc_row content_placement="middle" css=".vc_custom_1458048524081{padding-right: 10% !important;padding-left: 10% !important;background-position: center !important;background-repeat: no-repeat !important;background-size: cover !important;}" el_class="center-text"][vc_column css=".vc_custom_1458045278797{padding-top: 0px !important;}"][title_subtitle align="center" add_subtitle="use_subtitle" title="Join Our Newsletter" subtitle="Be connected"][widget_mailchimp title=" "][/vc_column][/vc_row]
CONTENT;
 
    array_unshift( $data, $template );
    return $data;
}

//Homepage v2
add_filter( 'vc_load_default_templates', 'villenoir_custom_template_homepage_v2' ); // Hook in
function villenoir_custom_template_homepage_v2( $data ) {
    $template               = array();
    $template['name']       = esc_html__( 'Homepage v2', 'okthemes-villenoir-shortcodes' ); // Assign name for your custom template
    $template['custom_class'] = 'custom_template_homepage_v2'; // CSS class name
    $template['content']    = <<<CONTENT
        [vc_row full_width="stretch_row"][vc_column][vc_row_inner equal_height="yes" content_placement="middle"][vc_column_inner width="1/2" css=".vc_custom_1488885265853{padding: 5% !important;}"][title_subtitle add_subtitle="use_subtitle" title="A New Generation of Winemakers" subtitle="MESSAGE FROM VILLENOIR"][vc_column_text]Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur sit amet elit leo.

Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. .[/vc_column_text][vc_single_image image="1049" img_size="full" css=".vc_custom_1488891182317{margin-bottom: 0px !important;}"][/vc_column_inner][vc_column_inner width="1/2"][vc_single_image image="1046" img_size="full" css=".vc_custom_1488890777657{margin-bottom: 0px !important;}"][/vc_column_inner][/vc_row_inner][/vc_column][/vc_row][vc_row full_width="stretch_row" css=".vc_custom_1488880591853{background-color: #f1f1f1 !important;}"][vc_column][vc_row_inner][vc_column_inner width="7/12" offset="vc_col-lg-8 vc_col-md-8"][vc_single_image image="1100" img_size="full" alignment="center" css=".vc_custom_1488890837311{margin-bottom: 0px !important;}"][/vc_column_inner][vc_column_inner width="5/12" css=".vc_custom_1460372155757{padding-top: 20% !important;padding-right: 20% !important;padding-bottom: 20% !important;padding-left: 20% !important;background: #ffffff url(http://localhost/villenoir/wp-content/uploads/2016/03/triangles-icn.png?id=315) !important;background-position: 0 0 !important;background-repeat: no-repeat !important;}" offset="vc_col-lg-4 vc_col-md-4"][title_subtitle title_type="h2" add_subtitle="use_subtitle" title="The Reds" subtitle="Varieties"][list list_style="line"]

Cabernet Sauvignon
Merlot
Pinot Noir
[/list][vc_btn title="Shop now" style="custom" custom_background="#000000" custom_text="#ffffff" shape="square" link="url:%23||" css=".vc_custom_1460372096755{margin-bottom: 0px !important;}"][/vc_column_inner][/vc_row_inner][vc_row_inner css=".vc_custom_1458553570458{padding-top: 150px !important;}"][vc_column_inner width="5/12" css=".vc_custom_1460372168380{padding-top: 20% !important;padding-right: 20% !important;padding-bottom: 20% !important;padding-left: 20% !important;background: #ffffff url(http://localhost/villenoir/wp-content/uploads/2016/03/triangles-icn.png?id=315) !important;background-position: 0 0 !important;background-repeat: no-repeat !important;}" offset="vc_col-lg-4 vc_col-md-4"][title_subtitle title_type="h2" add_subtitle="use_subtitle" title="The Whites" subtitle="Varieties"][list list_style="line"]

Chardonnay
Sauvignon Blanc
Riesling
[/list][vc_btn title="Shop now" style="custom" custom_background="#b0976d" custom_text="#ffffff" shape="square" link="url:%23||" css=".vc_custom_1460372126088{margin-bottom: 0px !important;}"][/vc_column_inner][vc_column_inner width="7/12" offset="vc_col-lg-8 vc_col-md-8"][vc_single_image image="1101" img_size="full" alignment="center" css=".vc_custom_1488890879865{margin-bottom: 0px !important;}"][/vc_column_inner][/vc_row_inner][/vc_column][/vc_row][vc_row css=".vc_custom_1488883745872{padding-bottom: 0px !important;background-position: center !important;background-repeat: no-repeat !important;background-size: cover !important;}"][vc_column][title_subtitle align="center" add_subtitle="use_subtitle" title="Vineyard" subtitle="Vineyard"][vc_gallery type="flexslider_slide" interval="3" images="1073,1072,1069" img_size="full" onclick="" css=".vc_custom_1488890938788{margin-bottom: 0px !important;}"][/vc_column][/vc_row][vc_row css=".vc_custom_1488883764701{background-position: center !important;background-repeat: no-repeat !important;background-size: cover !important;}"][vc_column][vc_row_inner][vc_column_inner width="1/3"][title_subtitle add_subtitle="use_subtitle" subtitle="The" title="Location"][/vc_column_inner][vc_column_inner width="1/3"][vc_single_image image="626" img_size="full" css=".vc_custom_1488883844311{margin-bottom: 0px !important;}"][/vc_column_inner][vc_column_inner width="1/3"][vc_column_text]Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin eu urna pretium, fermentum risus aliquam, pulvinar mauris. Nunc sit amet orci placerat, auctor urna at, euismod tellus. Aenean vehicula augue ante, vel suscipit enim ullamcorper vel. Quisque nec nunc lorem. Duis interdum orci et neque elementum fermentum. Nulla quis lectus nibh.[/vc_column_text][vc_btn title="Directions and contacts" style="custom" custom_background="#b0976d" custom_text="#ffffff" shape="square" align="left"][/vc_column_inner][/vc_row_inner][/vc_column][/vc_row][vc_row full_width="stretch_row" css=".vc_custom_1488891284777{padding-right: 15% !important;padding-left: 15% !important;background-image: url(http://okthemes.com/villenoirdemo/wp-content/uploads/2017/03/garden-leaves.png?id=1104) !important;background-position: center !important;background-repeat: no-repeat !important;background-size: cover !important;}"][vc_column][title_subtitle align="center" add_subtitle="use_subtitle" title="Join Our Newsletter" subtitle="BE CONNECTED"][widget_mailchimp title=" "][vc_row_inner css=".vc_custom_1488879427281{padding-top: 60px !important;padding-right: 30% !important;padding-left: 30% !important;}"][vc_column_inner width="1/6" offset="vc_col-lg-2 vc_col-md-2 vc_hidden-sm vc_col-xs-2 vc_hidden-xs"][/vc_column_inner][vc_column_inner width="1/6" offset="vc_col-lg-2 vc_col-md-2 vc_col-xs-2"][vc_icon icon_fontawesome="fa fa-twitter" color="black" align="center" css=".vc_custom_1488883866334{margin-bottom: 0px !important;}" link="url:%23|||"][/vc_column_inner][vc_column_inner width="1/6" offset="vc_col-lg-2 vc_col-md-2 vc_col-xs-2"][vc_icon icon_fontawesome="fa fa-facebook" color="black" align="center" css=".vc_custom_1488883879114{margin-bottom: 0px !important;}" link="url:%23|||"][/vc_column_inner][vc_column_inner width="1/6" offset="vc_col-lg-2 vc_col-md-2 vc_col-xs-2"][vc_icon icon_fontawesome="fa fa-linkedin" color="black" align="center" css=".vc_custom_1488883893462{margin-bottom: 0px !important;}" link="url:%23|||"][/vc_column_inner][vc_column_inner width="1/6" offset="vc_col-lg-2 vc_col-md-1 vc_col-xs-2"][vc_icon icon_fontawesome="fa fa-pinterest-p" color="black" align="center" css=".vc_custom_1488883904625{margin-bottom: 0px !important;}" link="url:%23|||"][/vc_column_inner][vc_column_inner width="1/6" offset="vc_col-lg-2 vc_col-md-2 vc_hidden-sm vc_col-xs-2 vc_hidden-xs"][/vc_column_inner][/vc_row_inner][/vc_column][/vc_row][vc_row full_width="stretch_row_content_no_spaces" css=".vc_custom_1488879490177{margin-bottom: 0px !important;padding-bottom: 0px !important;background-color: #121212 !important;}"][vc_column][title_subtitle align="center" add_subtitle="use_subtitle" title="Explore" subtitle="The estate" title_font_color="#ffffff"][vc_row_inner][vc_column_inner width="1/2" offset="vc_col-lg-3 vc_col-xs-12"][featured_image featured_box_style="overlay" featured_box_text_align="center" image="1046" featured_title="Tasting & Tours" featured_link="url:%23|title:Read%20more||"][/vc_column_inner][vc_column_inner width="1/2" offset="vc_col-lg-3 vc_col-xs-12"][featured_image featured_box_style="overlay" featured_box_text_align="center" image="1046" featured_title="Weddings & Events" featured_link="url:%23|title:Read%20more||"][/vc_column_inner][vc_column_inner width="1/2" offset="vc_col-lg-3 vc_col-xs-12"][featured_image featured_box_style="overlay" featured_box_text_align="center" image="1046" featured_title="Our founders" featured_link="url:%23|title:Read%20more||"][/vc_column_inner][vc_column_inner width="1/2" offset="vc_col-lg-3 vc_col-xs-12"][featured_image featured_box_style="overlay" featured_box_text_align="center" image="1046" featured_title="Behind the bottle" featured_link="url:%23|title:Read%20more||"][/vc_column_inner][/vc_row_inner][/vc_column][/vc_row]
CONTENT;
 
    array_unshift( $data, $template );
    return $data;
}

//Our Story
add_filter( 'vc_load_default_templates', 'villenoir_custom_template_our_story' ); // Hook in
function villenoir_custom_template_our_story( $data ) {
    $template               = array();
    $template['name']       = esc_html__( 'Our story', 'okthemes-villenoir-shortcodes' ); // Assign name for your custom template
    $template['custom_class'] = 'custom_template_our_story'; // CSS class name
    $template['content']    = <<<CONTENT
        [vc_row full_width="stretch_row" css=".vc_custom_1469107646994{padding-top: 120px !important;background-image: url(http://okthemes.com/villenoirdemo/wp-content/uploads/2016/03/our-story-back-image.jpg?id=1062) !important;background-position: 0 0 !important;background-repeat: no-repeat !important;}" el_class="gg-force-background-bottom"][vc_column width="1/2"][vc_column_text]

How we started

Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur sit amet elit leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec vulputate nibh et nulla hendrerit, ut condimentum odio porttitor. Nulla consectetur nibh massa, eget scelerisque nisl volutpat et. Integer pharetra efficitur tempor. Quisque finibus suscipit nisi, quis scelerisque nunc efficitur vitae. Duis interdum aliquam mauris, ut fermentum enim suscipit.

Passion

Suspendisse commodo ex eget lorem iaculis, vel ultrices neque sodales. Sed bibendum egestas felis, commodo mattis ligula. Mauris aliquam lacus id hendrerit venenatis. Quisque dignissim mi ut dictum gravida.

Craftmanship

Duis sit amet ex sit amet tortor posuere posuere. Mauris sodales rutrum tincidunt. Donec non massa ullamcorper, volutpat ex ac, volutpat libero. Morbi vel metus fermentum augue lacinia maximus sit amet ut dolor.[/vc_column_text][/vc_column][vc_column width="1/2"][title_subtitle align="center" add_subtitle="use_subtitle" title="We thrive in making fine wines that enrich the taste&soul." subtitle="villenoir statement" padding="90px 0 0 0"][/vc_column][/vc_row][vc_row full_width="stretch_row" css=".vc_custom_1469107611394{background-image: url(http://okthemes.com/villenoirdemo/wp-content/uploads/2016/03/our-story-back-image-2-11.jpg?id=1061) !important;background-position: 0 0 !important;background-repeat: no-repeat !important;}" el_class="gg-force-background-bottom-no-cover"][vc_column width="1/3"][/vc_column][vc_column width="1/3" css=".vc_custom_1458310150528{padding-right: 5% !important;padding-left: 5% !important;}"][title_subtitle add_subtitle="use_subtitle" subtitle="History"][vc_column_text]Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur sit amet elit leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec vulputate nibh et nulla hendrerit, ut condimentum odio porttitor.[/vc_column_text][/vc_column][vc_column width="1/3"][title_subtitle add_subtitle="use_subtitle" subtitle="Results"][vc_column_text]Suspendisse commodo ex eget lorem iaculis, vel ultrices neque sodales. Sed bibendum egestas felis, commodo mattis ligula. Mauris aliquam lacus id hendrerit venenatis. Quisque dignissim mi ut dictum gravida.[/vc_column_text][title_subtitle add_subtitle="use_subtitle" subtitle="Vision" padding="25px 0 0 0"][vc_column_text]Duis sit amet ex sit amet tortor posuere posuere. Mauris sodales rutrum tincidunt. Donec non massa ullamcorper, volutpat ex ac, volutpat libero. Morbi vel metus fermentum augue lacinia maximus sit amet ut dolor.[/vc_column_text][vc_column_text]Duis sit amet ex sit amet tortor posuere posuere. Mauris sodales rutrum tincidunt. [/vc_column_text][/vc_column][/vc_row][vc_row full_width="stretch_row" css=".vc_custom_1460974692501{background-color: #f1f1f1 !important;}"][vc_column][title_subtitle add_subtitle="use_subtitle" title="Our Timeline" subtitle="Villenoir" margin_bottom="90"][timeline_item][timeline_inner_item image="743" date="1999" title="Purchased Farm" description="Praesent non nunc dapibus metus luctus laoreet nec id orci. Maecenas felis felis, laoreet congue iaculis ut, mattis sit amet felis. Etiam aliquet nec lorem ac ultricies. "][timeline_inner_item image="640" date="2001" title="Planted Vineyard" description="Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent non nunc dapibus metus luctus laoreet nec id orci. Maecenas felis felis, laoreet congue iaculis ut, mattis sit amet felis. Etiam aliquet nec lorem ac ultricies"][timeline_inner_item image="743" date="2003" title="First Production" description="Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent non nunc dapibus metus luctus laoreet nec id orci. Maecenas felis felis, laoreet congue iaculis ut, mattis sit amet felis. Etiam aliquet nec lorem ac ultricies"][timeline_inner_item image="640" date="2005" title="Awards won" description="Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent non nunc dapibus metus luctus laoreet nec id orci. Maecenas felis felis, laoreet congue iaculis ut, mattis sit amet felis. Etiam aliquet nec lorem ac ultricies"][/timeline_item][/vc_column][/vc_row]
CONTENT;
 
    array_unshift( $data, $template );
    return $data;
}

//Our Wines
add_filter( 'vc_load_default_templates', 'villenoir_custom_template_our_wines' ); // Hook in
function villenoir_custom_template_our_wines( $data ) {
    $template               = array();
    $template['name']       = esc_html__( 'Our Wines', 'okthemes-villenoir-shortcodes' ); // Assign name for your custom template
    $template['custom_class'] = 'custom_template_our_wines'; // CSS class name
    $template['content']    = <<<CONTENT
        [vc_row full_width="stretch_row" equal_height="yes" content_placement="middle" css=".vc_custom_1458476549041{margin-top: 150px !important;background-color: #f1f1f1 !important;}"][vc_column width="7/12" offset="vc_col-lg-8 vc_col-md-8"][vc_single_image image="388" img_size="full"][vc_row_inner][vc_column_inner width="1/3"][counter align="center" subtitle="varietals" number="5" title="Varietals"][/vc_column_inner][vc_column_inner width="1/3"][counter align="center" subtitle="varietals" number="162" title="Wine produced"][/vc_column_inner][vc_column_inner width="1/3"][counter align="center" subtitle="varietals" number="48" title="Awards won"][/vc_column_inner][/vc_row_inner][/vc_column][vc_column width="5/12" css=".vc_custom_1460371798588{padding: 20% !important;background: #ffffff url(http://okthemes.com/villenoirdemo/wp-content/uploads/2016/03/triangles-icn.png?id=315) !important;background-position: 0 0 !important;background-repeat: no-repeat !important;}" offset="vc_col-lg-4 vc_col-md-4"][title_subtitle title_type="h2" add_subtitle="use_subtitle" title="The Reds" subtitle="Varieties"][list list_style="line"]

Cabernet Sauvignon
Merlot
Pinot Noir
[/list][vc_btn title="Shop now" style="custom" custom_background="#000000" custom_text="#ffffff" shape="square" link="url:%23||" css=".vc_custom_1460371810664{margin-bottom: 0px !important;}"][/vc_column][/vc_row][vc_row full_width="stretch_row" equal_height="yes" content_placement="middle" css=".vc_custom_1458476498163{padding-top: 0px !important;background-color: #f1f1f1 !important;}"][vc_column width="1/3" css=".vc_custom_1460371824474{padding: 20% !important;background: #ffffff url(http://okthemes.com/villenoirdemo/wp-content/uploads/2016/03/triangles-icn.png?id=315) !important;background-position: 0 0 !important;background-repeat: no-repeat !important;}"][title_subtitle title_type="h2" add_subtitle="use_subtitle" title="The Whites" subtitle="Varieties"][list list_style="line"]

Chardonnay
Sauvignon Blanc
Riesling
[/list][vc_btn title="Shop now" style="custom" custom_background="#000000" custom_text="#ffffff" shape="square" link="url:%23||" css=".vc_custom_1460371835204{margin-bottom: 0px !important;}"][/vc_column][vc_column width="2/3"][vc_single_image image="392" img_size="full" alignment="right"][vc_row_inner][vc_column_inner width="1/3"][counter align="center" subtitle="varietals" number="5" title="Varietals"][/vc_column_inner][vc_column_inner width="1/3"][counter align="center" subtitle="varietals" number="162" title="Wine produced"][/vc_column_inner][vc_column_inner width="1/3"][counter align="center" subtitle="varietals" number="48" title="Awards won"][/vc_column_inner][/vc_row_inner][/vc_column][/vc_row][vc_row][vc_column][title_subtitle align="center" add_subtitle="use_subtitle" title="Award-Winning Wines" subtitle="SInce 2001"][vc_row_inner][vc_column_inner width="1/6"][/vc_column_inner][vc_column_inner width="2/6"][vc_column_text]Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin eu urna pretium, fermentum risus aliquam, pulvinar mauris. Nunc sit amet orci placerat, auctor urna at, euismod tellus.

Aenean vehicula augue ante, vel suscipit enim ullamcorper vel. Quisque nec nunc lorem. Duis interdum orci et neque elementum fermentum.[/vc_column_text][/vc_column_inner][vc_column_inner width="2/6"][vc_column_text]Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin eu urna pretium, fermentum risus aliquam, pulvinar mauris. Nunc sit amet orci placerat, auctor urna at, euismod tellus.

Aenean vehicula augue ante, vel suscipit enim ullamcorper vel. Quisque nec nunc lorem. Duis interdum orci et neque elementum fermentum.[/vc_column_text][/vc_column_inner][vc_column_inner width="1/6"][/vc_column_inner][/vc_row_inner][vc_row_inner][vc_column_inner width="1/6"][/vc_column_inner][vc_column_inner width="2/6"][featured_image image="1066" featured_title="Our grapes" featured_desc="Nunc gravida malesuada luctus. Fusce viverra nisl quis nulla dapibus, ac elementum lectus scelerisque. Proin fringilla dolor non sapien pellentesque "][featured_image image="1067" featured_title="Our corks" featured_desc="Nunc gravida malesuada luctus. Fusce viverra nisl quis nulla dapibus, ac elementum lectus scelerisque. Proin fringilla dolor non sapien pellentesque "][/vc_column_inner][vc_column_inner width="2/6"][featured_image image="1065" featured_title="Our barrels" featured_desc="Nunc gravida malesuada luctus. Fusce viverra nisl quis nulla dapibus, ac elementum lectus scelerisque. Proin fringilla dolor non sapien pellentesque "][featured_image image="1068" featured_title="Our aging method" featured_desc="Nunc gravida malesuada luctus. Fusce viverra nisl quis nulla dapibus, ac elementum lectus scelerisque. Proin fringilla dolor non sapien pellentesque "][/vc_column_inner][vc_column_inner width="1/6"][/vc_column_inner][/vc_row_inner][/vc_column][/vc_row][vc_row full_width="stretch_row" css=".vc_custom_1469108003527{background-image: url(http://okthemes.com/villenoirdemo/wp-content/uploads/2016/03/our-cellar-background-11.jpg?id=1069) !important;background-position: center !important;background-repeat: no-repeat !important;background-size: cover !important;}"][vc_column][vc_cta h2="Our Cellar" h4="Nunc gravida malesuada luctus. Fusce viverra nisl quis nulla dapibus, ac elementum lectus scelerisque. Proin fringilla dolor non sapien pellentesque accumsan. " txt_align="center" shape="square" style="custom" el_width="lg" add_button="bottom" btn_title="View Gallery" btn_style="custom" btn_custom_background="#b0976d" btn_custom_text="#ffffff" btn_shape="square" btn_align="center" custom_background="rgba(17,17,17,0.8)" btn_link="url:%23||" custom_text="#ffffff"][/vc_cta][/vc_column][/vc_row]
CONTENT;
 
    array_unshift( $data, $template );
    return $data;
}

//Our Wines Alt
add_filter( 'vc_load_default_templates', 'villenoir_custom_template_our_wines_alt' ); // Hook in
function villenoir_custom_template_our_wines_alt( $data ) {
    $template               = array();
    $template['name']       = esc_html__( 'Our Wines Alt', 'okthemes-villenoir-shortcodes' ); // Assign name for your custom template
    $template['custom_class'] = 'custom_template_our_wines_alt'; // CSS class name
    $template['content']    = <<<CONTENT
        [vc_row full_width="stretch_row" equal_height="yes" content_placement="middle" css=".vc_custom_1458476549041{margin-top: 150px !important;background-color: #f1f1f1 !important;}"][vc_column width="7/12" offset="vc_col-lg-8 vc_col-md-8"][vc_single_image image="388" img_size="full"][vc_row_inner][vc_column_inner width="1/3"][counter align="center" subtitle="varietals" number="5" title="Varietals"][/vc_column_inner][vc_column_inner width="1/3"][counter align="center" subtitle="varietals" number="162" title="Wine produced"][/vc_column_inner][vc_column_inner width="1/3"][counter align="center" subtitle="varietals" number="48" title="Awards won"][/vc_column_inner][/vc_row_inner][/vc_column][vc_column width="5/12" css=".vc_custom_1460371798588{padding: 20% !important;background: #ffffff url(http://okthemes.com/villenoirdemo/wp-content/uploads/2016/03/triangles-icn.png?id=315) !important;background-position: 0 0 !important;background-repeat: no-repeat !important;}" offset="vc_col-lg-4 vc_col-md-4"][title_subtitle title_type="h2" add_subtitle="use_subtitle" title="The Reds" subtitle="Varieties"][list list_style="line"]

Cabernet Sauvignon
Merlot
Pinot Noir
[/list][vc_btn title="Shop now" style="custom" custom_background="#000000" custom_text="#ffffff" shape="square" link="url:%23||" css=".vc_custom_1460371810664{margin-bottom: 0px !important;}"][/vc_column][/vc_row][vc_row full_width="stretch_row" equal_height="yes" content_placement="middle" css=".vc_custom_1458476498163{padding-top: 0px !important;background-color: #f1f1f1 !important;}"][vc_column width="1/3" css=".vc_custom_1460371824474{padding: 20% !important;background: #ffffff url(http://okthemes.com/villenoirdemo/wp-content/uploads/2016/03/triangles-icn.png?id=315) !important;background-position: 0 0 !important;background-repeat: no-repeat !important;}"][title_subtitle title_type="h2" add_subtitle="use_subtitle" title="The Whites" subtitle="Varieties"][list list_style="line"]

Chardonnay
Sauvignon Blanc
Riesling
[/list][vc_btn title="Shop now" style="custom" custom_background="#000000" custom_text="#ffffff" shape="square" link="url:%23||" css=".vc_custom_1460371835204{margin-bottom: 0px !important;}"][/vc_column][vc_column width="2/3"][vc_single_image image="392" img_size="full" alignment="right"][vc_row_inner][vc_column_inner width="1/3"][counter align="center" subtitle="varietals" number="5" title="Varietals"][/vc_column_inner][vc_column_inner width="1/3"][counter align="center" subtitle="varietals" number="162" title="Wine produced"][/vc_column_inner][vc_column_inner width="1/3"][counter align="center" subtitle="varietals" number="48" title="Awards won"][/vc_column_inner][/vc_row_inner][/vc_column][/vc_row][vc_row][vc_column][title_subtitle align="center" add_subtitle="use_subtitle" title="Award-Winning Wines" subtitle="SInce 2001"][vc_row_inner][vc_column_inner width="1/6"][/vc_column_inner][vc_column_inner width="2/6"][vc_column_text]Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin eu urna pretium, fermentum risus aliquam, pulvinar mauris. Nunc sit amet orci placerat, auctor urna at, euismod tellus.

Aenean vehicula augue ante, vel suscipit enim ullamcorper vel. Quisque nec nunc lorem. Duis interdum orci et neque elementum fermentum.[/vc_column_text][/vc_column_inner][vc_column_inner width="2/6"][vc_column_text]Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin eu urna pretium, fermentum risus aliquam, pulvinar mauris. Nunc sit amet orci placerat, auctor urna at, euismod tellus.

Aenean vehicula augue ante, vel suscipit enim ullamcorper vel. Quisque nec nunc lorem. Duis interdum orci et neque elementum fermentum.[/vc_column_text][/vc_column_inner][vc_column_inner width="1/6"][/vc_column_inner][/vc_row_inner][vc_row_inner][vc_column_inner width="1/6"][/vc_column_inner][vc_column_inner width="2/6"][featured_image image="1066" featured_title="Our grapes" featured_desc="Nunc gravida malesuada luctus. Fusce viverra nisl quis nulla dapibus, ac elementum lectus scelerisque. Proin fringilla dolor non sapien pellentesque "][featured_image image="1067" featured_title="Our corks" featured_desc="Nunc gravida malesuada luctus. Fusce viverra nisl quis nulla dapibus, ac elementum lectus scelerisque. Proin fringilla dolor non sapien pellentesque "][/vc_column_inner][vc_column_inner width="2/6"][featured_image image="1065" featured_title="Our barrels" featured_desc="Nunc gravida malesuada luctus. Fusce viverra nisl quis nulla dapibus, ac elementum lectus scelerisque. Proin fringilla dolor non sapien pellentesque "][featured_image image="1068" featured_title="Our aging method" featured_desc="Nunc gravida malesuada luctus. Fusce viverra nisl quis nulla dapibus, ac elementum lectus scelerisque. Proin fringilla dolor non sapien pellentesque "][/vc_column_inner][vc_column_inner width="1/6"][/vc_column_inner][/vc_row_inner][/vc_column][/vc_row][vc_row full_width="stretch_row" css=".vc_custom_1469108003527{background-image: url(http://okthemes.com/villenoirdemo/wp-content/uploads/2016/03/our-cellar-background-11.jpg?id=1069) !important;background-position: center !important;background-repeat: no-repeat !important;background-size: cover !important;}"][vc_column][vc_cta h2="Our Cellar" h4="Nunc gravida malesuada luctus. Fusce viverra nisl quis nulla dapibus, ac elementum lectus scelerisque. Proin fringilla dolor non sapien pellentesque accumsan. " txt_align="center" shape="square" style="custom" el_width="lg" add_button="bottom" btn_title="View Gallery" btn_style="custom" btn_custom_background="#b0976d" btn_custom_text="#ffffff" btn_shape="square" btn_align="center" custom_background="rgba(17,17,17,0.8)" btn_link="url:%23||" custom_text="#ffffff"][/vc_cta][/vc_column][/vc_row]
CONTENT;
 
    array_unshift( $data, $template );
    return $data;
}

//The estate
add_filter( 'vc_load_default_templates', 'villenoir_custom_template_the_estate' ); // Hook in
function villenoir_custom_template_the_estate( $data ) {
    $template               = array();
    $template['name']       = esc_html__( 'The estate', 'okthemes-villenoir-shortcodes' ); // Assign name for your custom template
    $template['custom_class'] = 'custom_template_the_estate'; // CSS class name
    $template['content']    = <<<CONTENT
        [vc_row full_width="stretch_row_content_no_spaces" css=".vc_custom_1458555179979{padding-bottom: 0px !important;}"][vc_column][vc_gallery interval="0" images="1072,1073,1074,1075" img_size="full" onclick=""][/vc_column][/vc_row][vc_row css=".vc_custom_1458737831058{padding-bottom: 0px !important;}"][vc_column width="1/6"][/vc_column][vc_column width="2/3" css=".vc_custom_1458555142589{padding-right: 10% !important;padding-left: 10% !important;}"][title_subtitle align="center" add_subtitle="use_subtitle" title="Villenoir Estate" subtitle="The story of"][vc_column_text]Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin eu urna pretium, fermentum risus aliquam, pulvinar mauris. Nunc sit amet orci placerat, auctor urna at, euismod tellus. Aenean vehicula augue ante, vel suscipit enim ullamcorper vel. Quisque nec nunc lorem. Duis interdum orci et neque elementum fermentum. Nulla quis lectus nibh.

Nunc gravida malesuada luctus. Fusce viverra nisl quis nulla dapibus, ac elementum lectus scelerisque. Proin fringilla dolor non sapien pellentesque accumsan. Quisque volutpat lobortis odio quis feugiat.[/vc_column_text][horizontal_list_item][horizontal_list_inner_item title="Acreage" description="113 acres"][horizontal_list_inner_item title="Soil type" description="Deep, cobbly, volcanic soils
"][horizontal_list_inner_item title="Planted" description="Cabernet Sauvignon, Merlot, Cabernet Franc"][horizontal_list_inner_item title="Notes" description="Sustainably Farmed, Certified by the Friendly Farming"][/horizontal_list_item][/vc_column][vc_column width="1/6"][/vc_column][/vc_row][vc_row full_width="stretch_row" css=".vc_custom_1469108420952{padding-top: 60px !important;background-image: url(http://okthemes.com/villenoirdemo/wp-content/uploads/2016/03/the-estate-background-11.jpg?id=1077) !important;background-position: center !important;background-repeat: no-repeat !important;background-size: cover !important;}"][vc_column][vc_row_inner gap="30"][vc_column_inner width="1/2" css=".vc_custom_1460628751680{padding-top: 15% !important;padding-right: 15% !important;padding-bottom: 15% !important;padding-left: 15% !important;background-color: #b0976d !important;}" offset="vc_col-lg-offset-0 vc_col-lg-4 vc_col-md-offset-0 vc_col-md-4 vc_col-sm-offset-3"][vc_icon type="openiconic" icon_openiconic="vc-oi vc-oi-layers-alt" color="white"][vc_column_text]

Soil

In tempor, mauris nec viverra molestie, lorem diam dignissim ex, quis lobortis dui turpis ut enim lacerat in massa eget, lacinia accumsan nunc magna.[/vc_column_text][/vc_column_inner][vc_column_inner width="1/2" css=".vc_custom_1460628787670{padding-top: 15% !important;padding-right: 15% !important;padding-bottom: 15% !important;padding-left: 15% !important;background-color: #ffffff !important;}" offset="vc_col-lg-offset-0 vc_col-lg-4 vc_col-md-offset-0 vc_col-md-4 vc_col-sm-offset-3"][vc_icon type="openiconic" icon_openiconic="vc-oi vc-oi-cloud" color="custom" custom_color="#b0976d"][vc_column_text]

Climate

In tempor, mauris nec viverra molestie, lorem diam dignissim ex, quis lobortis dui turpis ut enim lacerat in massa eget, lacinia accumsan nunc magna.[/vc_column_text][/vc_column_inner][vc_column_inner width="1/2" css=".vc_custom_1460628881427{padding-top: 15% !important;padding-right: 15% !important;padding-bottom: 15% !important;padding-left: 15% !important;background-color: #b0976d !important;}" offset="vc_col-lg-offset-0 vc_col-lg-4 vc_col-md-offset-0 vc_col-md-4 vc_col-sm-offset-3"][vc_icon type="openiconic" icon_openiconic="vc-oi vc-oi-share" color="white"][vc_column_text]

Grapes

In tempor, mauris nec viverra molestie, lorem diam dignissim ex, quis lobortis dui turpis ut enim lacerat in massa eget, lacinia accumsan nunc magna.[/vc_column_text][/vc_column_inner][/vc_row_inner][vc_row_inner gap="30"][vc_column_inner width="1/2" css=".vc_custom_1460628891183{padding-top: 15% !important;padding-right: 15% !important;padding-bottom: 15% !important;padding-left: 15% !important;background-color: #ffffff !important;}" offset="vc_col-lg-offset-0 vc_col-lg-4 vc_col-md-offset-0 vc_col-md-4 vc_col-sm-offset-3"][vc_icon type="openiconic" icon_openiconic="vc-oi vc-oi-sun" color="custom" custom_color="#b0976d"][vc_column_text]

Solar

In tempor, mauris nec viverra molestie, lorem diam dignissim ex, quis lobortis dui turpis ut enim lacerat in massa eget, lacinia accumsan nunc magna.[/vc_column_text][/vc_column_inner][vc_column_inner width="1/2" css=".vc_custom_1460628903037{padding-top: 15% !important;padding-right: 15% !important;padding-bottom: 15% !important;padding-left: 15% !important;background-color: #b0976d !important;}" offset="vc_col-lg-offset-0 vc_col-lg-4 vc_col-md-offset-0 vc_col-md-4 vc_col-sm-offset-3"][vc_icon type="openiconic" icon_openiconic="vc-oi vc-oi-cd" color="white"][vc_column_text]

Vineyard

In tempor, mauris nec viverra molestie, lorem diam dignissim ex, quis lobortis dui turpis ut enim lacerat in massa eget, lacinia accumsan nunc magna.[/vc_column_text][/vc_column_inner][vc_column_inner width="1/2" css=".vc_custom_1460628914502{padding-top: 15% !important;padding-right: 15% !important;padding-bottom: 15% !important;padding-left: 15% !important;background-color: #ffffff !important;}" offset="vc_col-lg-offset-0 vc_col-lg-4 vc_col-md-offset-0 vc_col-md-4 vc_col-sm-offset-3"][vc_icon type="openiconic" icon_openiconic="vc-oi vc-oi-calendar-alt" color="custom" custom_color="#b0976d"][vc_column_text]

Winery

In tempor, mauris nec viverra molestie, lorem diam dignissim ex, quis lobortis dui turpis ut enim lacerat in massa eget, lacinia accumsan nunc magna.[/vc_column_text][/vc_column_inner][/vc_row_inner][/vc_column][/vc_row][vc_row][vc_column offset="vc_col-lg-4 vc_col-md-4"][title_subtitle add_subtitle="use_subtitle" title="The Location" subtitle="Villenoir Estate"][/vc_column][vc_column width="1/2" offset="vc_col-lg-4 vc_col-md-4"][vc_single_image image="626" img_size="195x241" alignment="center"][/vc_column][vc_column width="1/2" offset="vc_col-lg-4 vc_col-md-4"][vc_column_text]Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin eu urna pretium, fermentum risus aliquam, pulvinar mauris. Nunc sit amet orci placerat, auctor urna at, euismod tellus. Aenean vehicula augue ante, vel suscipit enim ullamcorper vel. Quisque nec nunc lorem. Duis interdum orci et neque elementum fermentum. Nulla quis lectus nibh.[/vc_column_text][/vc_column][/vc_row][vc_row full_width="stretch_row" full_height="yes" content_placement="middle" video_bg="yes" video_bg_url="https://www.youtube.com/watch?v=ryCDD0cG2os"][vc_column][vc_icon icon_fontawesome="fa fa-play-circle-o" color="custom" size="lg" align="center" custom_color="#b0976d"][title_subtitle align="center" add_subtitle="use_subtitle" title="Estate drone footage" title_font_color="#ffffff" subtitle="Watch the"][/vc_column][/vc_row][vc_row full_width="stretch_row_content_no_spaces" equal_height="yes" content_placement="middle" css=".vc_custom_1458558193547{padding-top: 0px !important;padding-bottom: 0px !important;}"][vc_column css=".vc_custom_1460629135634{margin-top: 0px !important;margin-right: 0px !important;margin-bottom: 0px !important;margin-left: 0px !important;padding-top: 0px !important;padding-right: 0px !important;padding-bottom: 0px !important;padding-left: 0px !important;}" offset="vc_col-lg-6 vc_col-md-6"][vc_single_image image="919" img_size="full" css=".vc_custom_1469108449923{margin-top: 0px !important;margin-right: 0px !important;margin-bottom: 0px !important;margin-left: 0px !important;padding-top: 0px !important;padding-right: 0px !important;padding-bottom: 0px !important;padding-left: 0px !important;}"][/vc_column][vc_column css=".vc_custom_1460629151533{padding-top: 115px !important;padding-right: 115px !important;padding-bottom: 115px !important;padding-left: 115px !important;}" offset="vc_col-lg-6 vc_col-md-6"][title_subtitle align="center" add_subtitle="use_subtitle" title="Tasting & Tours" subtitle="The estate" margin_bottom="90"][vc_column_text css=".vc_custom_1457626016290{margin-bottom: 90px !important;}"]

Lorem ipsum dolor sit amet, consectetur adipiscing. Cras vel iaculis urna. Cras bibendum ex id dolor facilisis, in tempor lacus dapibus.

[/vc_column_text][vc_btn title="Read more" style="custom" custom_background="#000000" custom_text="#ffffff" shape="square" align="center" link="url:%23||"][/vc_column][/vc_row][vc_row full_width="stretch_row_content_no_spaces" equal_height="yes" content_placement="middle" css=".vc_custom_1458558211443{padding-top: 0px !important;padding-bottom: 0px !important;}"][vc_column css=".vc_custom_1460629243166{padding-top: 115px !important;padding-right: 115px !important;padding-bottom: 115px !important;padding-left: 115px !important;}" offset="vc_col-lg-6 vc_col-md-6"][title_subtitle align="center" add_subtitle="use_subtitle" title="Corporate & Private Events" subtitle="The estate"][vc_column_text css=".vc_custom_1457626016290{margin-bottom: 90px !important;}"]

Lorem ipsum dolor sit amet, consectetur adipiscing. Cras vel iaculis urna. Cras bibendum ex id dolor facilisis, in tempor lacus dapibus.

[/vc_column_text][vc_btn title="Read more" style="custom" custom_background="#000000" custom_text="#ffffff" shape="square" align="center" link="url:%23||"][/vc_column][vc_column css=".vc_custom_1460629254486{margin-top: 0px !important;margin-right: 0px !important;margin-bottom: 0px !important;margin-left: 0px !important;padding-top: 0px !important;padding-right: 0px !important;padding-bottom: 0px !important;padding-left: 0px !important;}" offset="vc_col-lg-6 vc_col-md-6"][vc_single_image image="1046" img_size="full" css=".vc_custom_1469108471059{margin-top: 0px !important;margin-right: 0px !important;margin-bottom: 0px !important;margin-left: 0px !important;padding-top: 0px !important;padding-right: 0px !important;padding-bottom: 0px !important;padding-left: 0px !important;}"][/vc_column][/vc_row][vc_row full_width="stretch_row_content_no_spaces" equal_height="yes" content_placement="middle" css=".vc_custom_1458558203228{padding-top: 0px !important;padding-bottom: 0px !important;}"][vc_column css=".vc_custom_1460629267259{margin-top: 0px !important;margin-right: 0px !important;margin-bottom: 0px !important;margin-left: 0px !important;padding-top: 0px !important;padding-right: 0px !important;padding-bottom: 0px !important;padding-left: 0px !important;}" offset="vc_col-lg-6 vc_col-md-6"][vc_single_image image="1078" img_size="full" css=".vc_custom_1469108496760{margin-top: 0px !important;margin-right: 0px !important;margin-bottom: 0px !important;margin-left: 0px !important;padding-top: 0px !important;padding-right: 0px !important;padding-bottom: 0px !important;padding-left: 0px !important;}"][/vc_column][vc_column css=".vc_custom_1460629276472{padding-top: 115px !important;padding-right: 115px !important;padding-bottom: 115px !important;padding-left: 115px !important;}" offset="vc_col-lg-6 vc_col-md-6"][title_subtitle align="center" add_subtitle="use_subtitle" title="Weddings at the Estate" subtitle="The estate" margin_bottom="90"][vc_column_text css=".vc_custom_1457626016290{margin-bottom: 90px !important;}"]

Lorem ipsum dolor sit amet, consectetur adipiscing. Cras vel iaculis urna. Cras bibendum ex id dolor facilisis, in tempor lacus dapibus.

[/vc_column_text][vc_btn title="Read more" style="custom" custom_background="#000000" custom_text="#ffffff" shape="square" align="center" link="url:%23||"][/vc_column][/vc_row][vc_row full_width="stretch_row" css=".vc_custom_1460629297580{padding-top: 150px !important;padding-bottom: 150px !important;background: #f2f2f2 url(http://okthemes.com/villenoirdemo/wp-content/uploads/2016/03/the-estate-our-address.jpg?id=478) !important;background-position: center !important;background-repeat: no-repeat !important;background-size: cover !important;}"][vc_column offset="vc_col-lg-offset-2 vc_col-lg-8 vc_col-md-offset-2 vc_col-md-8"][title_subtitle align="center" add_subtitle="use_subtitle" title="53 Rue de Venteille, 33185 Le Haillan Bordeaux, France" subtitle="Our address" margin_bottom="90"][vc_btn title="Direction and contacts" style="custom" custom_background="#b0976d" custom_text="#ffffff" shape="square" align="center" link="url:%23||"][/vc_column][/vc_row]
CONTENT;
 
    array_unshift( $data, $template );
    return $data;
}

//Our Winemakers
add_filter( 'vc_load_default_templates', 'villenoir_custom_template_our_winemakers' ); // Hook in
function villenoir_custom_template_our_winemakers( $data ) {
    $template               = array();
    $template['name']       = esc_html__( 'Our Winemakers', 'okthemes-villenoir-shortcodes' ); // Assign name for your custom template
    $template['custom_class'] = 'custom_template_our_winemakers'; // CSS class name
    $template['content']    = <<<CONTENT
        [vc_row full_width="stretch_row_content_no_spaces" equal_height="yes" content_placement="middle" css=".vc_custom_1458562782643{margin-top: 150px !important;padding-top: 0px !important;padding-bottom: 0px !important;}"][vc_column css=".vc_custom_1460369218397{margin-top: 0px !important;margin-right: 0px !important;margin-bottom: 0px !important;margin-left: 0px !important;padding-top: 0px !important;padding-right: 0px !important;padding-bottom: 0px !important;padding-left: 0px !important;}" offset="vc_col-lg-6 vc_col-md-6"][vc_single_image image="1080" img_size="full" css=".vc_custom_1469108573445{margin-top: 0px !important;margin-right: 0px !important;margin-bottom: 0px !important;margin-left: 0px !important;padding-top: 0px !important;padding-right: 0px !important;padding-bottom: 0px !important;padding-left: 0px !important;}"][/vc_column][vc_column css=".vc_custom_1460369333053{padding-top: 115px !important;padding-right: 115px !important;padding-bottom: 115px !important;padding-left: 115px !important;background-color: #121212 !important;}" offset="vc_col-lg-6 vc_col-md-6"][title_subtitle title_type="h2" add_subtitle="use_subtitle" title="Anatole Vallée Villenoir" subtitle="Director of Winemaking" title_font_color="#ffffff"][vc_column_text css=".vc_custom_1469108613830{margin-bottom: 90px !important;}"]

Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin eu urna pretium, fermentum risus aliquam, pulvinar mauris. Nunc sit amet orci placerat, auctor urna at, euismod tellus. Aenean vehicula augue.

Nunc sit amet orci placerat, auctor urna at, euismod tellus. Aenean vehicula augue ante.

[/vc_column_text][blockquote quote="Wine is constant proof that God loves us and loves to see us happy." quote_color="#ffffff"][/vc_column][/vc_row][vc_row full_width="stretch_row_content_no_spaces" equal_height="yes" content_placement="middle" css=".vc_custom_1458558211443{padding-top: 0px !important;padding-bottom: 0px !important;}"][vc_column css=".vc_custom_1460369363082{padding-top: 115px !important;padding-right: 115px !important;padding-bottom: 115px !important;padding-left: 115px !important;background-color: #f1f1f1 !important;}" offset="vc_col-lg-6 vc_col-md-6"][title_subtitle title_type="h2" add_subtitle="use_subtitle" title="Rénne Villenoir" subtitle="Winemaker"][vc_column_text css=".vc_custom_1469108626427{margin-bottom: 90px !important;}"]

Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin eu urna pretium, fermentum risus aliquam, pulvinar mauris. Nunc sit amet orci placerat, auctor urna at, euismod tellus. Aenean vehicula augue.

Nunc sit amet orci placerat, auctor urna at, euismod tellus. Aenean vehicula augue ante.

[/vc_column_text][blockquote quote="Wine is constant proof that God loves us and loves to see us happy."][/vc_column][vc_column css=".vc_custom_1460369372764{margin-top: 0px !important;margin-right: 0px !important;margin-bottom: 0px !important;margin-left: 0px !important;padding-top: 0px !important;padding-right: 0px !important;padding-bottom: 0px !important;padding-left: 0px !important;}" offset="vc_col-lg-6 vc_col-md-6"][vc_single_image image="1081" img_size="full" css=".vc_custom_1469108590826{margin-top: 0px !important;margin-right: 0px !important;margin-bottom: 0px !important;margin-left: 0px !important;padding-top: 0px !important;padding-right: 0px !important;padding-bottom: 0px !important;padding-left: 0px !important;}"][/vc_column][/vc_row]
CONTENT;
 
    array_unshift( $data, $template );
    return $data;
}

//Awards
add_filter( 'vc_load_default_templates', 'villenoir_custom_template_awards' ); // Hook in
function villenoir_custom_template_awards( $data ) {
    $template               = array();
    $template['name']       = esc_html__( 'Awards', 'okthemes-villenoir-shortcodes' ); // Assign name for your custom template
    $template['custom_class'] = 'custom_template_awards'; // CSS class name
    $template['content']    = <<<CONTENT
        [vc_row css=".vc_custom_1458564277675{padding-bottom: 0px !important;}"][vc_column width="1/2"][title_subtitle title_type="h2" add_subtitle="use_subtitle" title="Villenoir Cabernet Sauvignon" subtitle="2015"][/vc_column][vc_column width="1/2"][list list_style="line"]

94 points, Gold, Best of Region White, California State Fair, June 2015
91 points, Tasting Panel, Aug-Sept 2015
Silver, Alameda County Wine Competition, June 2015
Silver, San Francisco International Wine Competition
87 points, Silver, Critics Challenge, May 2015
[/list][/vc_column][/vc_row][vc_row css=".vc_custom_1458564260157{padding-top: 0px !important;padding-bottom: 0px !important;}"][vc_column css=".vc_custom_1458564215708{padding-top: 0px !important;padding-bottom: 0px !important;}"][vc_separator css=".vc_custom_1458564307490{margin-bottom: 0px !important;}"][/vc_column][/vc_row][vc_row css=".vc_custom_1458565399766{padding-top: 55px !important;padding-bottom: 0px !important;}"][vc_column width="1/2"][title_subtitle title_type="h2" add_subtitle="use_subtitle" title="Villenoir Merlot" subtitle="2014"][/vc_column][vc_column width="1/2"][list list_style="line"]

94 points, Gold, Best of Region White, California State Fair, June 2015
91 points, Tasting Panel, Aug-Sept 2015
Silver, Alameda County Wine Competition, June 2015
[/list][/vc_column][/vc_row][vc_row css=".vc_custom_1458564260157{padding-top: 0px !important;padding-bottom: 0px !important;}"][vc_column css=".vc_custom_1458564215708{padding-top: 0px !important;padding-bottom: 0px !important;}"][vc_separator css=".vc_custom_1458564307490{margin-bottom: 0px !important;}"][/vc_column][/vc_row][vc_row css=".vc_custom_1458565399766{padding-top: 55px !important;padding-bottom: 0px !important;}"][vc_column width="1/2"][title_subtitle title_type="h2" add_subtitle="use_subtitle" title="Villenoir Pinot Noir" subtitle="2013"][/vc_column][vc_column width="1/2"][list list_style="line"]

94 points, Gold, Best of Region White, California State Fair, June 2015
91 points, Tasting Panel, Aug-Sept 2015
[/list][/vc_column][/vc_row][vc_row css=".vc_custom_1458564260157{padding-top: 0px !important;padding-bottom: 0px !important;}"][vc_column css=".vc_custom_1458564215708{padding-top: 0px !important;padding-bottom: 0px !important;}"][vc_separator css=".vc_custom_1458564307490{margin-bottom: 0px !important;}"][/vc_column][/vc_row][vc_row css=".vc_custom_1458565518104{padding-top: 55px !important;padding-bottom: 150px !important;}"][vc_column width="1/2"][title_subtitle title_type="h2" add_subtitle="use_subtitle" title="Villenoir Riesling" subtitle="2011"][/vc_column][vc_column width="1/2"][list list_style="line"]

94 points, Gold, Best of Region White, California State Fair, June 2015
91 points, Tasting Panel, Aug-Sept 2015
[/list][/vc_column][/vc_row]
CONTENT;
 
    array_unshift( $data, $template );
    return $data;
}

//FAQ
add_filter( 'vc_load_default_templates', 'villenoir_custom_template_faq' ); // Hook in
function villenoir_custom_template_faq( $data ) {
    $template               = array();
    $template['name']       = esc_html__( 'FAQ', 'okthemes-villenoir-shortcodes' ); // Assign name for your custom template
    $template['custom_class'] = 'custom_template_faq'; // CSS class name
    $template['content']    = <<<CONTENT
        [vc_row][vc_column width="1/2"][title_subtitle title_type="h2" add_subtitle="use_subtitle" title="Delivery" subtitle="About"][vc_column_text]What courier do you use for deliveries?

We use DHL to send all of our orders and they are forwarded to the national postal service on arrival in the country of destination.

How long does delivery take?

International orders generally take between 1-3 working days to arrive. A delivery within Germany usually takes 1-2 working days.

How much is delivery?

We offer FREE Delivery on all orders over €50 / £50 / 50 CHF.

Can I track my item?

Yes. You will be sent a confirmation email as soon as your order has been processed. This email will also contain your tracking number.

Do you deliver to my country?

We currently ship to the following countries: Austria, Belgium, Finland, France, Germany, Netherlands, Spain, Sweden, Switzerland, Ireland and the UK.[/vc_column_text][title_subtitle title_type="h2" add_subtitle="use_subtitle" title="Payment" subtitle="About"][vc_column_text]Which payment methods do you accept?

We currently accept all relevant credit and debit cards including Visa, Mastercard, PayPal and Postfinance.

What currencies can I use?

We accept payment in Euros, GBP and CHF only.[/vc_column_text][/vc_column][vc_column width="1/2"][title_subtitle title_type="h2" add_subtitle="use_subtitle" title="Returns" subtitle="About"][vc_column_text]What is your online returns policy?

If you are not completely happy with the goods that you have received, you can return them to us within 14 days of receipt, providing they are in original resalable condition.

Once returned you are entitled to receive an exchange or a refund.

We are not able to refund or exchange items that appear to have been worn, washed, or are not in original condition.

Please enclose the completed Returns Form with your goods, and make sure all returned items are well packaged, so as not to be damaged in the post.

How do I return an item?

To return an item you can simply complete the enclosed returns form and attach the return address to the package and take it to your local post office.

Who pays for return postage?

We offer FREE return postage on all orders returned within 14 days of receipt.

Can you confirm you have received my return?

We aim to process returns within 2 working days of receiving them. You will be notified by email once the returns process is complete.[/vc_column_text][/vc_column][/vc_row]
CONTENT;
 
    array_unshift( $data, $template );
    return $data;
}

//The Restaurant
add_filter( 'vc_load_default_templates', 'villenoir_custom_template_the_restaurant' ); // Hook in
function villenoir_custom_template_the_restaurant( $data ) {
    $template               = array();
    $template['name']       = esc_html__( 'The Restaurant', 'okthemes-villenoir-shortcodes' ); // Assign name for your custom template
    $template['custom_class'] = 'custom_template_the_restaurant'; // CSS class name
    $template['content']    = <<<CONTENT
        [vc_row full_width="stretch_row_content_no_spaces"][vc_column][vc_gallery interval="3" images="1072,1073,1074" img_size="full" onclick=""][/vc_column][/vc_row][vc_row equal_height="yes" css=".vc_custom_1466169520129{padding-top: 0px !important;padding-right: 0px !important;padding-bottom: 0px !important;padding-left: 0px !important;}"][vc_column width="1/2" css=".vc_custom_1466159675532{padding: 18% !important;background-color: #b0976d !important;}"][vc_icon icon_fontawesome="fa fa-phone" color="custom" background_style="rounded" background_color="white" align="center" custom_color="#b0976d"][title_subtitle align="center" add_subtitle="use_subtitle" title="Make a Reservation" subtitle="The restaurant" title_font_color="#ffffff" subtitle_font_color="rgba(255,255,255,0.7)"][vc_column_text]


Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent non libero consequat, sodales ipsum malesuada, ullamcorper nisi.

[/vc_column_text][vc_btn title="Call us now" style="custom" custom_background="#000000" custom_text="#ffffff" shape="square" align="center" link="url:%23|||"][/vc_column][vc_column width="1/2" css=".vc_custom_1466159685700{padding: 18% !important;background-color: #000000 !important;}"][vc_icon icon_fontawesome="fa fa-cutlery" color="custom" background_style="rounded" background_color="white" align="center" custom_color="#000000"][title_subtitle align="center" add_subtitle="use_subtitle" title="Private Dining" subtitle="The restaurant" title_font_color="#ffffff" subtitle_font_color="rgba(255,255,255,0.7)"][vc_column_text]


Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent non libero consequat, sodales ipsum malesuada, ullamcorper nisi.

[/vc_column_text][vc_btn title="Call us now" style="custom" custom_background="#b0976d" custom_text="#ffffff" shape="square" align="center" link="url:%23|||"][/vc_column][/vc_row][vc_row][vc_column width="1/2" css=".vc_custom_1466169436879{padding-right: 10% !important;}"][title_subtitle add_subtitle="use_subtitle" title="The Restaurant" subtitle="About"][vc_column_text]Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam malesuada sem est, ut feugiat nibh interdum nec. Curabitur vel cursus justo. Nulla dignissim ullamcorper arcu, quis hendrerit nulla. Nam et aliquet ante. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.

Curabitur convallis, quam vitae pretium vulputate, purus magna vehicula libero, ac varius tortor dolor sit amet nisi. Nunc sapien eros, vulputate ut rutrum porta, porttitor sed nibh.[/vc_column_text][/vc_column][vc_column width="1/2"][vc_single_image image="918" img_size="full" alignment="center"][/vc_column][/vc_row][vc_row equal_height="yes" content_placement="middle" css=".vc_custom_1466166287107{padding-top: 0px !important;padding-bottom: 0px !important;}"][vc_column css=".vc_custom_1460370846525{margin-top: 0px !important;margin-right: 0px !important;margin-bottom: 0px !important;margin-left: 0px !important;padding-top: 0px !important;padding-right: 0px !important;padding-bottom: 0px !important;padding-left: 0px !important;}" offset="vc_col-lg-6 vc_col-md-6"][vc_single_image image="919" img_size="full" css=".vc_custom_1466170682784{margin-top: 0px !important;margin-right: 0px !important;margin-bottom: 0px !important;margin-left: 0px !important;padding-top: 0px !important;padding-right: 0px !important;padding-bottom: 0px !important;padding-left: 0px !important;}"][/vc_column][vc_column css=".vc_custom_1460370835378{padding-top: 20% !important;padding-right: 20% !important;padding-bottom: 20% !important;padding-left: 20% !important;background-color: #f1f1f1 !important;}" offset="vc_col-lg-6 vc_col-md-6"][title_subtitle align="center" add_subtitle="use_subtitle" title="Menus" subtitle="The restaurant" margin_bottom="90"][vc_column_text css=".vc_custom_1457626016290{margin-bottom: 90px !important;}"]

Lorem ipsum dolor sit amet, consectetur adipiscing. Cras vel iaculis urna. Cras bibendum ex id dolor facilisis, in tempor lacus dapibus.

[/vc_column_text][vc_btn title="Read more" style="custom" custom_background="#000000" custom_text="#ffffff" shape="square" align="center" link="url:%23||"][/vc_column][/vc_row][vc_row equal_height="yes" content_placement="middle" css=".vc_custom_1457626404907{padding-top: 0px !important;}"][vc_column css=".vc_custom_1460370856484{padding-top: 20% !important;padding-right: 20% !important;padding-bottom: 20% !important;padding-left: 20% !important;background-color: #f1f1f1 !important;}" offset="vc_col-lg-6 vc_col-md-6"][title_subtitle align="center" add_subtitle="use_subtitle" title="Meet the chef" subtitle="The estate"][vc_column_text css=".vc_custom_1457626016290{margin-bottom: 90px !important;}"]

Lorem ipsum dolor sit amet, consectetur adipiscing. Cras vel iaculis urna. Cras bibendum ex id dolor facilisis, in tempor lacus dapibus.

[/vc_column_text][vc_btn title="Read more" style="custom" custom_background="#000000" custom_text="#ffffff" shape="square" align="center" link="url:%23||"][/vc_column][vc_column css=".vc_custom_1460370866457{margin-top: 0px !important;margin-right: 0px !important;margin-bottom: 0px !important;margin-left: 0px !important;padding-top: 0px !important;padding-right: 0px !important;padding-bottom: 0px !important;padding-left: 0px !important;}" offset="vc_col-lg-6 vc_col-md-6"][vc_single_image image="918" img_size="full" css=".vc_custom_1466170695060{margin-top: 0px !important;margin-right: 0px !important;margin-bottom: 0px !important;margin-left: 0px !important;padding-top: 0px !important;padding-right: 0px !important;padding-bottom: 0px !important;padding-left: 0px !important;}"][/vc_column][/vc_row][vc_row css=".vc_custom_1466164975372{padding-top: 0px !important;padding-bottom: 60px !important;}"][vc_column width="1/2"][title_subtitle add_subtitle="use_subtitle" title="Launch Hours" subtitle="Restaurant"][horizontal_list_item][horizontal_list_inner_item title="Sunday - Monday" description="5:00pm - 8:00pm"][horizontal_list_inner_item title="Tuesday - Saturday" description="5:00pm - 9:00pm"][/horizontal_list_item][/vc_column][vc_column width="1/2"][title_subtitle add_subtitle="use_subtitle" title="Dinner Hours" subtitle="Restaurant"][horizontal_list_item][horizontal_list_inner_item title=" Monday - Saturday" description="11:30am - 2:30pm"][horizontal_list_inner_item title="Sunday Brunch" description="10:30am - 2:30pm"][/horizontal_list_item][/vc_column][/vc_row][vc_row full_width="stretch_row" css=".vc_custom_1466170711495{background-image: url(http://okthemes.com/villenoirdemo/wp-content/uploads/2016/04/estate-gallery-3.jpg?id=917) !important;background-position: center !important;background-repeat: no-repeat !important;background-size: cover !important;}"][vc_column offset="vc_col-lg-6"][vc_cta h2="Host an event or your Dream Wedding" shape="square" style="custom" add_button="bottom" btn_title="Contact us now" btn_style="flat" btn_shape="square" btn_color="white" btn_align="left" btn_link="url:%23|||" custom_background="rgba(255,255,255,0.3)"]Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut neque leo, tempus ac consequat eget, malesuada vel lectus. Fusce rhoncus ornare vulputate.[/vc_cta][/vc_column][vc_column offset="vc_col-lg-6"][/vc_column][/vc_row]
CONTENT;
 
    array_unshift( $data, $template );
    return $data;
}

//Contact
add_filter( 'vc_load_default_templates', 'villenoir_custom_template_contact' ); // Hook in
function villenoir_custom_template_contact( $data ) {
    $template               = array();
    $template['name']       = esc_html__( 'Contact', 'okthemes-villenoir-shortcodes' ); // Assign name for your custom template
    $template['custom_class'] = 'custom_template_contact'; // CSS class name
    $template['content']    = <<<CONTENT
        [vc_row full_width="stretch_row" css=".vc_custom_1458720332308{padding-top: 90px !important;padding-bottom: 0px !important;background-color: #000000 !important;}"][vc_column width="1/2"][infoboxes infotitle="villenoir wines" description_color="#ffffff"]53 Rue de Venteille, 33185 Le Haillan,
Bordeaux, France
Get directions[/infoboxes][infoboxes infotitle="talk to us" description_color="#ffffff"]hello@villenoir.com
+40 (555) 556 555[/infoboxes][/vc_column][vc_column width="1/2"][horizontal_list_item module_title="Tastin room hours"][horizontal_list_inner_item title="MONDAY - THURSDAY" description="11AM - 5PM" description_color="#ffffff" title_color="#ffffff"][horizontal_list_inner_item title="FRIDAY - SUNDAY" description="10AM - 5PM" description_color="#ffffff" title_color="#ffffff"][horizontal_list_inner_item title="LAST POUR" description="4:30PM" description_color="#ffffff" title_color="#ffffff"][/horizontal_list_item][infoboxes infotitle="reservations" description_color="#ffffff"]tasting@villenoir.com
+40 (555) 556 556[/infoboxes][/vc_column][/vc_row]
CONTENT;
 
    array_unshift( $data, $template );
    return $data;
}

//Contact
add_filter( 'vc_load_default_templates', 'villenoir_custom_template_club' ); // Hook in
function villenoir_custom_template_club( $data ) {
    $template               = array();
    $template['name']       = esc_html__( 'Club', 'okthemes-villenoir-shortcodes' ); // Assign name for your custom template
    $template['custom_class'] = 'custom_template_club'; // CSS class name
    $template['content']    = <<<CONTENT
        [vc_row css=".vc_custom_1526047811183{padding-bottom: 0px !important;}"][vc_column width="1/6"][/vc_column][vc_column width="2/3" css=".vc_custom_1526304946515{padding-right: 5% !important;padding-left: 5% !important;}"][title_subtitle align="center" title="Join the Club"][vc_column_text css=".vc_custom_1526304928075{margin-bottom: 0px !important;}"]
<p style="text-align: center;">Villenoir vineyard provides several exclusive memberships so you can choose the club that is right for you. Please browse the below selections to learn more or join the club.</p>
<p style="text-align: center;"><em>Ready to join? Choose from one of three club options:</em></p>
[/vc_column_text][/vc_column][vc_column width="1/6"][/vc_column][/vc_row][vc_row full_width="stretch_row"][vc_column width="1/3"][clubbox align="center" image="919" title="Classic" price="$65-$85" link="url:http%3A%2F%2Flocalhost%2Fvillenoirtest%2Fproduct%2Fclub-classic%2F|title:Join%20now||" css=".vc_custom_1526307462277{background-color: #f1f1f1 !important;}" top_title="Club" price_affix="/shipment"]
<ul>
    <li>2 bottles per shipment</li>
    <li>15% off all wine orders</li>
    <li>20% off tasting room purchases</li>
    <li>Discounted or free entry to in-house events</li>
    <li>Complementary wine tasting</li>
    <li>Exclusive club-only wine releases</li>
</ul>
[/clubbox][/vc_column][vc_column width="1/3"][clubbox align="center" image="919" title="Connoisseur" price="$85-$100" link="url:http%3A%2F%2Flocalhost%2Fvillenoirtest%2Fproduct%2Fclub-classic%2F|title:Join%20now||" css=".vc_custom_1526307672771{background-color: #f1f1f1 !important;}" top_title="Club" price_affix="/shipment "]
<ul>
    <li>4 bottles per shipment</li>
    <li>20% off all wine orders</li>
    <li>25% off tasting room purchases</li>
    <li>Discounted or free entry to in-house events</li>
    <li>Complementary wine tasting</li>
    <li>Exclusive club-only wine releases</li>
</ul>
[/clubbox][/vc_column][vc_column width="1/3"][clubbox align="center" image="919" title="Exclusive" price="$100-$125" link="url:http%3A%2F%2Flocalhost%2Fvillenoirtest%2Fproduct%2Fclub-classic%2F|title:Join%20now||" css=".vc_custom_1526307681931{background-color: #f1f1f1 !important;}" top_title="Club" price_affix="/shipment"]
<ul>
    <li>6 bottles per shipment</li>
    <li>30% off all wine orders</li>
    <li>40% off tasting room purchases</li>
    <li>Discounted or free entry to in-house events</li>
    <li>Complementary wine tasting</li>
    <li>Exclusive club-only wine releases</li>
</ul>
[/clubbox][/vc_column][/vc_row][vc_row equal_height="yes" content_placement="middle" css=".vc_custom_1526304651436{padding-top: 0px !important;padding-bottom: 0px !important;}"][vc_column css=".vc_custom_1460370846525{margin-top: 0px !important;margin-right: 0px !important;margin-bottom: 0px !important;margin-left: 0px !important;padding-top: 0px !important;padding-right: 0px !important;padding-bottom: 0px !important;padding-left: 0px !important;}" offset="vc_col-lg-6 vc_col-md-6"][vc_single_image image="1046" img_size="full" css=".vc_custom_1469106688328{margin-top: 0px !important;margin-right: 0px !important;margin-bottom: 0px !important;margin-left: 0px !important;padding-top: 0px !important;padding-right: 0px !important;padding-bottom: 0px !important;padding-left: 0px !important;}"][/vc_column][vc_column css=".vc_custom_1460370835378{padding-top: 20% !important;padding-right: 20% !important;padding-bottom: 20% !important;padding-left: 20% !important;background-color: #f1f1f1 !important;}" offset="vc_col-lg-6 vc_col-md-6"][title_subtitle align="center" add_subtitle="use_subtitle" title="Discounts on Purchases" subtitle="DISCOUNTS"][vc_column_text css=".vc_custom_1526040723566{margin-bottom: 90px !important;}"]
<p style="text-align: center;">Wine club members receive great discounts on our wines including quarterly shipments, wine purchases, tasting room merchandise, winery events, and more!</p>
[/vc_column_text][/vc_column][/vc_row][vc_row equal_height="yes" content_placement="middle" css=".vc_custom_1526041005372{padding-top: 0px !important;padding-bottom: 0px !important;}"][vc_column css=".vc_custom_1460370856484{padding-top: 20% !important;padding-right: 20% !important;padding-bottom: 20% !important;padding-left: 20% !important;background-color: #f1f1f1 !important;}" offset="vc_col-lg-6 vc_col-md-6"][title_subtitle align="center" add_subtitle="use_subtitle" title="Member Exclusive Events" subtitle="Events"][vc_column_text css=".vc_custom_1526040811883{margin-bottom: 90px !important;}"]
<p style="text-align: center;">We host Wine Club exclusive events throughout the year including pick-up parties, seasonal celebrations, pairing events, and trips to Italy!</p>
[/vc_column_text][/vc_column][vc_column css=".vc_custom_1460370866457{margin-top: 0px !important;margin-right: 0px !important;margin-bottom: 0px !important;margin-left: 0px !important;padding-top: 0px !important;padding-right: 0px !important;padding-bottom: 0px !important;padding-left: 0px !important;}" offset="vc_col-lg-6 vc_col-md-6"][vc_single_image image="1047" img_size="full" css=".vc_custom_1469106707986{margin-top: 0px !important;margin-right: 0px !important;margin-bottom: 0px !important;margin-left: 0px !important;padding-top: 0px !important;padding-right: 0px !important;padding-bottom: 0px !important;padding-left: 0px !important;}"][/vc_column][/vc_row][vc_row equal_height="yes" content_placement="middle" css=".vc_custom_1526041586423{padding-top: 0px !important;}"][vc_column css=".vc_custom_1460370846525{margin-top: 0px !important;margin-right: 0px !important;margin-bottom: 0px !important;margin-left: 0px !important;padding-top: 0px !important;padding-right: 0px !important;padding-bottom: 0px !important;padding-left: 0px !important;}" offset="vc_col-lg-6 vc_col-md-6"][vc_single_image image="1046" img_size="full" css=".vc_custom_1469106688328{margin-top: 0px !important;margin-right: 0px !important;margin-bottom: 0px !important;margin-left: 0px !important;padding-top: 0px !important;padding-right: 0px !important;padding-bottom: 0px !important;padding-left: 0px !important;}"][/vc_column][vc_column css=".vc_custom_1460370835378{padding-top: 20% !important;padding-right: 20% !important;padding-bottom: 20% !important;padding-left: 20% !important;background-color: #f1f1f1 !important;}" offset="vc_col-lg-6 vc_col-md-6"][title_subtitle align="center" add_subtitle="use_subtitle" title="Latest Wine Releases" subtitle="Releases"][vc_column_text css=".vc_custom_1526040723566{margin-bottom: 90px !important;}"]
<p style="text-align: center;">Wine club members receive great discounts on our wines including quarterly shipments, wine purchases, tasting room merchandise, winery events, and more!</p>
[/vc_column_text][/vc_column][/vc_row][vc_row css=".vc_custom_1526304628017{padding-top: 0px !important;padding-bottom: 0px !important;}"][vc_column width="1/6"][/vc_column][vc_column width="2/3" css=".vc_custom_1458555142589{padding-right: 10% !important;padding-left: 10% !important;}"][title_subtitle align="center" add_subtitle="use_subtitle" title="How it works" subtitle="Wine club"][vc_column_text]Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin eu urna pretium, fermentum risus aliquam, pulvinar mauris. Nunc sit amet orci placerat, auctor urna at, euismod tellus. Aenean vehicula augue ante, vel suscipit enim ullamcorper vel.

Nunc gravida malesuada luctus. Fusce viverra nisl quis nulla dapibus, ac elementum lectus scelerisque. Proin fringilla dolor non sapien pellentesque accumsan.[/vc_column_text][horizontal_list_item el_class="horizontal-flex"][horizontal_list_inner_item title="Step 1" description="Choose your Club"][horizontal_list_inner_item title="Step 2 " description="Sign Up with your billing and shipping info."][horizontal_list_inner_item title="Step 3" description="Receive 3-4 bottles hand-selected by our Winemaker four times per year "][/horizontal_list_item][/vc_column][vc_column width="1/6"][/vc_column][/vc_row][vc_row equal_height="yes" content_placement="top" css=".vc_custom_1526303856754{padding-top: 0px !important;}"][vc_column width="1/3" css=".vc_custom_1526303698491{padding: 15% !important;background-color: #b0976d !important;}"][vc_icon icon_fontawesome="fa fa-truck" color="white" size="lg"][infoboxes infotitle="SHIPPING SCHEDULE" css=".vc_custom_1526303191558{margin-bottom: 0px !important;}" title_color="#ffffff" description_color="#ffffff"]Shipments will go out on the second Monday of each month: March, June, September, December[/infoboxes][/vc_column][vc_column width="1/3" css=".vc_custom_1526303726415{padding: 15% !important;background-color: #f1f1f1 !important;}"][vc_icon icon_fontawesome="fa fa-cog" color="custom" size="lg" custom_color="#b0976d"][infoboxes infotitle="Already a member?" css=".vc_custom_1526302753868{margin-bottom: 0px !important;}" title_color="#000000"]
<p style="text-align: left;">If you are a current Wine Club Member, please <a href="/my-account">click here</a>.</p>
[/infoboxes][/vc_column][vc_column width="1/3" css=".vc_custom_1526303756413{padding: 15% !important;background-color: #b0976d !important;}"][vc_icon icon_fontawesome="fa fa-phone" color="white" size="lg"][infoboxes infotitle="CUSTOMER SERVICE" description_color="#ffffff" css=".vc_custom_1526303445720{margin-bottom: 0px !important;}" title_color="#ffffff"]If we can assist you with your selection or membership, please contact us at (555) 555-5555[/infoboxes][/vc_column][/vc_row]
CONTENT;
 
    array_unshift( $data, $template );
    return $data;
}
