<?php
// Get the Content
$getcontent = apply_filters( 'the_content', $post->post_content );
$pcontent = wpautop( $getcontent );

// Remove Entities from Content
$entities = array(
  "&nbsp;",
  "&lt;",
  "&gt;",
  "&amp;",
  "&cent;",
  "&pound;",
  "&yen;",
  "&euro;",
  "&copy;",
  "&reg;",
);
$entitycontent = str_replace($entities, '', $pcontent);

$patternclean = array(
  "/<abrr.*?>.*?<\/abbr>/",
  "/<acronym.*?>.*?<\/acronym>/",
  "/<applet.*?>.*?<\/applet>/",
  "/<area.*?>.*?<\/applet>/",
  "/<audio.*?>.*?<\/audio>/",
  "/<base.*?>.*?<\/base>/",
  "/<basefont.*?>.*?<\/basefont>/",
  "/<bdi.*?>.*?<\/bdi>/",
  "/<bdo.*?>.*?<\/bdo>/",
  "/<big.*?>.*?<\/big>/",
  "/<br.*?>/",
  "/<button.*?>.*?<\/button>/",
  "/<canvas.*?>.*?<\/canvas>/",
  "/<col.*?>.*?<\/col>/",
  "/<colgroup.*?>.*?<\/colgroup>/",
  "/<datalist.*?>.*?<\/datalist>/",
  "/<dd.*?>.*?<\/dd>/",
  "/<details.*?>.*?<\/details>/",
  "/<dfn.*?>.*?<\/dfn>/",
  "/<dialog.*?>.*?<\/dialog>/",
  "/<dir.*?>.*?<\/dir>/",
  "/<div.*?>.*?<\/div>/",
  "/<dl.*?>.*?<\/dl>/",
  "/<dt.*?>.*?<\/dt>/",
  "/<embed.*?>.*?<\/embed>/",
  "/<fieldset.*?>.*?<\/fieldset>/",
  "/<font.*?>.*?<\/font>/",
  "/<footer.*?>.*?<\/footer>/",
  "/<form.*?>.*?<\/form>/",
  "/<frame.*?>.*?<\/frame>/",
  "/<frameset.*?>.*?<\/frameset>/",
  "/<hr.*?>/",
  "/<html.*?>.*?<\/html>/",
  "/<i.*?>.*?<\/i>/",
  "/<ins.*?>.*?<\/ins>/",
  "/<kbd.*?>.*?<\/kbd>/",
  "/<keygen.*?>.*?<\/keygen>/",
  "/<legend.*?>.*?<\/legend>/",
  "/<link.*?>.*?<\/link>/",
  "/<main.*?>.*?<\/main>/",
  "/<map.*?>.*?<\/map>/",
  "/<mark.*?>.*?<\/mark>/",
  "/<menu.*?>.*?<\/menu>/",
  "/<menuitem.*?>.*?<\/menuitem>/",
  "/<meta.*?>.*?<\/meta>/",
  "/<meter.*?>.*?<\/meter>/",
  "/<nav.*?>.*?<\/nav>/",
  "/<noframes.*?>.*?<\/noframes>/",
  "/<noscript.*?>.*?<\/noscript>/",
  "/<object.*?>.*?<\/object>/",
  "/<optgroup.*?>.*?<\/optgroup>/",
  "/<option.*?>.*?<\/option>/",
  "/<output.*?>.*?<\/output>/",
  "/<param.*?>.*?<\/param>/",
  "/<progress.*?>.*?<\/progress>/",
  "/<q.*?>.*?<\/q>/",
  "/<rp.*?>.*?<\/rp>/",
  "/<rt.*?>.*?<\/rt>/",
  "/<ruby.*?>.*?<\/ruby>/",
  "/<s.*?>.*?<\/s>/",
  "/<samp.*?>.*?<\/samp>/",
  "/<section.*?>.*?<\/section>/",
  "/<select.*?>.*?<\/select>/",
  "/<strike.*?>.*?<\/strike>/",
  "/<style.*?>.*?<\/style>/",
  "/<sub.*?>.*?<\/sub>/",
  "/<summary.*?>.*?<\/summary>/",
  "/<sup.*?>.*?<\/sup>/",
  "/<table.*?>.*?<\/table>/",
  "/<tbody.*?>.*?<\/tbody>/",
  "/<td.*?>.*?<\/td>/",
  "/<textarea.*?>.*?<\/textarea>/",
  "/<tfoot.*?>.*?<\/tfoot>/",
  "/<th.*?>.*?<\/th>/",
  "/<thead.*?>.*?<\/thead>/",
  "/<time.*?>.*?<\/time>/",
  "/<title.*?>.*?<\/title>/",
  "/<tr.*?>.*?<\/tr>/",
  "/<track.*?>.*?<\/track>/",
  "/<tt.*?>.*?<\/tt>/",
  "/<var.*?>.*?<\/var>/",
  "/<wbr.*?>.*?<\/wbr>/",
  "/&mdash;/",
  "/(<strong.*?>)(.*?)(<\/strong.*?>)/",
  "/(<b.*?>)(.*?)(<\/b.*?>)/",
  "/(<address.*?>)(.*?)(<\/address.*?>)/",
  "/(<caption.*?>)(.*?)(<\/caption.*?>)/",
  "/(<center.*?>)(.*?)(<\/center.*?>)/",
  "/(<cite.*?>)(.*?)(<\/cite.*?>)/",
  "/(<code.*?>)(.*?)(<\/code.*?>)/",
  "/(<del.*?>)(.*?)(<\/del.*?>)/",
  "/(<em.*?>)(.*?)(<\/em.*?>)/",
  "/(<label.*?>)(.*?)(<\/label.*?>)/",
  "/(<pre.*?>)(.*?)(<\/pre.*?>)/",
  "/(<small.*?>)(.*?)(<\/small.*?>)/",
  "/(<source.*?>)(.*?)(<\/source.*?>)/",
  "/(<span.*?>)(.*?)(<\/span.*?>)/",
  "/(<u.*?>)(.*?)(<\/u.*?>)/",
  "/(<h3.*?>)(.*?)(<\/h3.*?>)/",
  "/(<h4.*?>)(.*?)(<\/h4.*?>)/",
  "/(<h5.*?>)(.*?)(<\/h5.*?>)/",
  "/(<h6.*?>)(.*?)(<\/h6.*?>)/",
  "/(<p.*?\"?>)/",
  "/(<a.*?><img.*?src=\")(.*?)(\".*?width=\")(.*?)(\".*?height=\")(.*?)(\".*?srcset=\")(.*?)(\".*?><\/a>)/",
  "/(<a.*?><img.*?src=\")(.*?)(\".*?width=\")(.*?)(\".*?height=\")(.*?)(\".*?><\/a>)/",
  "/(<img.*?src=\")(.*?)(\".*?width=\")(.*?)(\".*?height=\")(.*?)(\".*?srcset=\")(.*?)(\".*?>)/",
  "/(<img.*?src=\")(.*?)(\".*?width=\")(.*?)(\".*?height=\")(.*?)(\".*?>)/",
  "/(<figure.*?src=\")(.*?)(\".*?width=\")(.*?)(\".*?height=\")(.*?)(\".*?\n)(<figcaption.*?<\/figcaption>)(\n.*?<\/figure>)/",
  "/(<script.*?twitter.*?<\/script>)/",
  "/(<blockquote.*?twitter-tweet.*<\/p>\n<p>.*?twitter.*?\/status\/)(.*?)(\".*?\/p>\n.*?<\/blockquote>)/",
  "/(<p>.*?iframe.*?youtu.*?embed\/)(.*?)(\".*?<\/p>|\?.*?<\/p>)/",
  "/(<iframe.*?src=\".*?facebook.*?post.*?href=.*?facebook.*?%2F)(.*?)(%2Fposts%2F)(.*?)(&.*?\".*?width=\")(.*?)(\".*?height=\")(.*?)(\".*?<\/iframe>)/",
  "/(<iframe.*?src=\".*?facebook.*?video.*?href=.*?facebook.*?\/)(.*?)(\/videos\/)(.*?)(&.*?\".*?width=\")(.*?)(\".*?height=\")(.*?)(\".*?<\/iframe>)/",
  "/(<iframe)(.*?)(<\/iframe>)/",
  "/style=\".*?\"/",
  "/(<p><\/p>)/",
);
$replaceclean = array(
  "", // Remove abrr
  "", // Remove acronym
  "", // Remove applet
  "", // Remove area
  "", // Remove audio
  "", // Remove base
  "", // Remove basefont
  "", // Remove bdi
  "", // Remove bdo
  "", // Remove big
  "", // Remove br
  "", // Remove button
  "", // Remove canvas
  "", // Remove col
  "", // Remove colgroup
  "", // Remove datalist
  "", // Remove dd
  "", // Remove details
  "", // Remove dfn
  "", // Remove dialog
  "", // Remove dir
  "", // Remove div
  "", // Remove dl
  "", // Remove dt
  "", // Remove embed
  "", // Remove fieldset
  "", // Remove font
  "", // Remove footer
  "", // Remove form
  "", // Remove frame
  "", // Remove frameset
  "", // Remove hr
  "", // Remove html
  "", // Remove i
  "", // Remove ins
  "", // Remove kbd
  "", // Remove keygen
  "", // Remove legend
  "", // Remove link
  "", // Remove main
  "", // Remove map
  "", // Remove mark
  "", // Remove menu
  "", // Remove menuitem
  "", // Remove meta
  "", // Remove meter
  "", // Remove nav
  "", // Remove noframes
  "", // Remove noscript
  "", // Remove object
  "", // Remove optgroup
  "", // Remove option
  "", // Remove output
  "", // Remove param
  "", // Remove progress
  "", // Remove q
  "", // Remove rp
  "", // Remove rt
  "", // Remove ruby
  "", // Remove s
  "", // Remove samp
  "", // Remove section
  "", // Remove select
  "", // Remove strike
  "", // Remove style
  "", // Remove sub
  "", // Remove summary
  "", // Remove sup
  "", // Remove table
  "", // Remove tbody
  "", // Remove td
  "", // Remove textarea
  "", // Remove tfoot
  "", // Remove th
  "", // Remove thead
  "", // Remove time
  "", // Remove title
  "", // Remove tr
  "", // Remove track
  "", // Remove tt
  "", // Remove var
  "", // Remove wbr
  "", // Remove &mdash;
  "<strong>$2</strong>", // Keep Content, Remove strong
  "<strong>$2</strong>", // Keep Content, Remove b
  "$2", // Keep Content, Remove address
  "$2", // Keep Content, Remove caption
  "$2", // Keep Content, Remove center
  "$2", // Keep Content, Remove cite
  "$2", // Keep Content, Remove code
  "$2", // Keep Content, Remove del
  "<em>$2</em>", // Keep Content, Remove em
  "$2", // Keep Content, Remove label
  "$2", // Keep Content, Remove pre
  "$2", // Keep Content, Remove small
  "$2", // Keep Content, Remove source
  "$2", // Keep Content, Remove span
  "<u>$2</u>", // Keep Content, Remove u
  "<p>$2</p>", // Replace H3
  "<p>$2</p>", // Replace H4
  "<p>$2</p>", // Replace H5
  "<p>$2</p>", // Replace H6
  "<p>", // Remove Classes in p
  "<p><amp-img src=\"$2\" width=$4 height=$6 srcset=\"$8\" layout=\"responsive\"></amp-img></p>", // Setup img with link
  "<p><amp-img src=\"$2\" width=$4 height=$6 layout=\"responsive\"></amp-img></p>", // Setup img with link, but not srcset
  "<p><amp-img src=\"$2\" width=$4 height=$6 srcset=\"$8\" layout=\"responsive\"></amp-img></p>", // Setup img without link
  "<p><amp-img src=\"$2\" width=$4 height=$6 layout=\"responsive\"></amp-img></p>", // Setup img without link and without srcset
  "<p><amp-img src=\"$2\" width=$4 height=$6 layout=\"responsive\"></amp-img></p>$8", // Setup img with caption
  "", // Remove Twitter script
  "<p><amp-twitter width=390 height=50 layout=\"responsive\" data-tweetid=\"$2\"></amp-twitter></p>", // Replace tweets with amp-twitter
  "<p><amp-youtube data-videoid=\"$2\" layout=\"responsive\" width=\"600\" height=\"338\"></amp-youtube></p>", // Replace YouTube iframe with amp-youtube
  "<p><amp-facebook width=$6 height=$8 layout=\"responsive\" data-href=\"https://www.facebook.com/$2/posts/$4\"></amp-facebook></p>", // Replace Facebook iframe embed with amp-facebook
  "<p><amp-facebook width=$6 height=$8 layout=\"responsive\" data-embed-as=\"video\" data-href=\"https://www.facebook.com/$2/videos/$4\"></amp-facebook></p>",
  "<p><amp-iframe $2></amp-iframe></p>", // Replace iframe with amp-iframe
  "", // Remove styles
  "", // Remove empty p
);
$cleanedcontent = preg_replace($patternclean, $replaceclean, $entitycontent); ?>
