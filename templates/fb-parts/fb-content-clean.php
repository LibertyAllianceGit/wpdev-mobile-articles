<?php
// Get the Content
$getcontent = get_the_content();
$pcontent = wpautop( $getcontent );
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
// Clean the Content
$patternclean = array(
  "/([.*?])(.*?)([\/.*?])",
  "/[.*?]/",
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
  "/(<p><a.*?><img.*src=\")(.*?)(\".*\/><\/a><\/p>)/",
  "/(<p><img.*?src=\")(.*?)(\".*?\/><\/p>)/",
  "/(<p>\[caption.*?\]<a.*?\"><img.*?src=\")(.*?)(\".*?<\/a>)(.*?)(\[\/caption.*?<\/p>)/",
  "/(<p>\[caption.*?\]<img.*?src=\")(.*?)(\".*?\/>)(.*?)(\[\/caption.*?<\/p>)/",
  "/(<p.*?>\[.*?\]<\/p>)/",
  "/(<p><iframe.*?src=\")(.*?facebook.*?post.*?)(\".*?width=\")(.*?)(\".*?height=\")(.*?)(\".*?<\/iframe><\/p>)/",
  "/(<p><iframe.*?src=\")(.*?facebook.*?video.*?)(\".*?width=\")(.*?)(\".*?height=\")(.*?)(\".*?<\/iframe><\/p>)/",
  "/(<p><iframe.*?src=\")(.*?vine\.co.*?)(\".*?><\/iframe><script.*?<\/script><\/p>)/",
  "/(<blockquote.*?twitter-tweet\"|<blockquote.*?instagram-media\")/",
  "/(<p><script.*?src=\")(.*?platform.instagram.*?|.*?platform.twitter.*?)(\".*?<\/script><\/p>)/",
  "/(<p>)(https.*?twitter.*?)(<\/p>)/",
  "/(<p><iframe.*?src=\")(.*?youtu.*?)(\".*?<\/iframe><\/p>)/",
  "/(<p>)(.*?youtube.*?)(<\/p>)/",
  "/(<p>)(.*?youtu..*?\/)(.*?)(<\/p>)/",
  "/(<p><\/p>)/",
);
$replaceclean = array(
  "$2", // Remove surrounding shortcodes
  "", // Remove shortcodes
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
  "$2", // Keep Content, Remove strong
  "$2", // Keep Content, Remove b
  "$2", // Keep Content, Remove address
  "$2", // Keep Content, Remove caption
  "$2", // Keep Content, Remove center
  "$2", // Keep Content, Remove cite
  "$2", // Keep Content, Remove code
  "$2", // Keep Content, Remove del
  "$2", // Keep Content, Remove em
  "$2", // Keep Content, Remove label
  "$2", // Keep Content, Remove pre
  "$2", // Keep Content, Remove small
  "$2", // Keep Content, Remove source
  "$2", // Keep Content, Remove span
  "$2", // Keep Content, Remove u
  "<p><strong>$2</strong></p>", // Replace H3
  "<p><strong>$2</strong></p>", // Replace H4
  "<p><strong>$2</strong></p>", // Replace H5
  "<p><strong>$2</strong></p>", // Replace H6 https://www.youtube.com/watch?v=ztmF73bri_s
  "<p>", // Remove Classes in p
  "<figure><img src=\"$2\" /></figure>", // Setup img with link
  "<figure><img src=\"$2\" /></figure>", // Setup img without link
  "<figure><img src=\"$2\" /><figcaption class=\"op-vertical-below\"><cite class=\"op-vertical-below op-center\">$4</cite></figcaption></figure>", // Setup img with caption and with link
  "<figure><img src=\"$2\" /><figcaption class=\"op-vertical-below\"><cite class=\"op-vertical-below op-center\">$4</cite></figcaption></figure>", // Setup img with caption and without link
  "", // Remove shortcodes
  "<figure class=\"op-interactive\"><iframe style=\"border: none; overflow: hidden;\" src=\"$2\" width=\"$4\" height=\"$6\" frameborder=\"0\" scrolling=\"no\"></iframe></figure>", // Embed Facebook Post
  "<figure class=\"op-interactive\"><iframe src=\"$2\" width=\"$4\" height=\"$6\" style=\"border:none;overflow:hidden;\" scrolling=\"no\" frameborder=\"0\" allowTransparency=\"true\" allowFullScreen=\"true\"></iframe></figure>", // Embed Facebook Video
  "<figure class=\"op-interactive\"><iframe src=\"$2\" width=\"600\" height=\"600\"></iframe></figure>", // Vine embed
  "<figure class=\"op-interactive\"><iframe>$0", // Instagram/Twitter embed start
  "$0</iframe></figure>", // Instagram/Twitter embed end
  "<figure class=\"op-interactive\"><iframe><blockquote class=\"twitter-tweet\" data-lang=\"en\"><p lang=\"en\" dir=\"ltr\"><a href=\"$2\"></a></blockquote>
  <script async src=\"//platform.twitter.com/widgets.js\" charset=\"utf-8\"></script></iframe></figure>", // Twitter Link Embed
  "<figure class=\"op-interactive\"><iframe width=\"560\" height=\"315\" src=\"$2\"></iframe></figure>", // YouTube embed
  "<figure class=\"op-interactive\"><iframe width=\"560\" height=\"315\" src=\"$2\"></iframe></figure>", // YouTube embed link
  "<figure class=\"op-interactive\"><iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/$3\"></iframe></figure>", // YouTube embed link
  "", // Remove empty p
);
$cleanedcontent = preg_replace($patternclean, $replaceclean, $entitycontent); ?>
