<?php

ob_start();
include 'web/layout.tpl.php';

$html = ob_get_clean();
$html = preg_replace(array('/(\\r|\\n|\\t|\\s{2})+/', '/<!--[^\[].*?-->/'), '', (string) $html);

echo $html. implode("\r\n", array('', '', '<!-- DEVELOPED BY BRAINSUM -->', '<!-- 2015 -->'));
