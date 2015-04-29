<?php
$box = new niceInfoBox('Te sunam noi',
                        'Lasa-ne un mesaj si vei fi contactat in intervalul orar specificat de tine.',
                        [tep_href_link(FILENAME_PHONE_ME, '', 'SSL'),'Completeaza formularul']);
$boxDecorator = new BoxDecorator($box,BoxDecorator::INFO_BOX);
echo $boxDecorator;

?>
