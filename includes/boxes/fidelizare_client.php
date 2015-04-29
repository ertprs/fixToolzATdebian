<?php
$box = new niceInfoBox('Fidelizare client',
    'Pentru a demonstra inca odata sprijinul pe care Toolszone.ro doreste sa-l aduca clientilor sai, pe langa produse de mare calitate si servicii ireprosabile, campania de fidelizare clienti incearca sa stabileasca preturile cele mai accesibile din domeniu.',
    ['http://www.toolszone.ro/how_i_order.php#facilitati','Vezi mai multe detalii']);
$boxDecorator = new BoxDecorator($box,BoxDecorator::INFO_BOX);
echo $boxDecorator;

?>

