<?php
$box = new niceInfoBox('Voucher',
    'Voucher-ul valoric Toolszone.ro aduce clientilor nostri o reducere in plus fata de celelalte magazine din domeniu. Numarul nelimitat de vouchere puse la dispozitia utilizatorilor, ofera posibilitatea de a primi bonificatii in lei valabile pe site-ul nostru.',
    [tep_href_link(FILENAME_VOUCHER, '', 'SSL'),'Recomanda ToolsZone.ro']);
$boxDecorator = new BoxDecorator($box,BoxDecorator::INFO_BOX);
echo $boxDecorator;

?>