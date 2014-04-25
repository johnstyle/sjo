<?php

use sJo\Libraries as Lib;
use sJo\View\Helper\Style;

?><!DOCTYPE html>
<html lang="<?php echo Lib\I18n::country(); ?>">
<head>
    <meta charset="<?php echo SJO_CHARSET; ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="<?php echo SJO_BASEHREF; ?>" />
    <title>403 | <?php Lib\I18n::_e('Forbidden!'); ?></title>
    <meta name="description" content="403 | Forbidden!">
    <?php if(Style::hasRegistry()): ?>
        <?php Style::applyRegistry(); ?>
    <?php endif; ?>
    <style type="text/css">
        *{margin:0;padding:0}
        body{font-family:"Times New Roman",Bodoni,Garamond,"Minion Web","ITC Stone Serif","MS Georgia","Bitstream Cyberbit",serif;overflow:auto;height:100%;text-align:center;line-height:1;color:#555;background:#eee;text-shadow:0 0 5px rgba(0,0,0,0.2);font-size:16px;margin-top:50px}
        h1{font-weight:300;font-size:15em;color:#a0a0a0}
        h2{font-weight:300;font-size:3em;color:#afafaf}
        h3{font-weight:300;font-size:2em;margin-bottom:5px}
        p{font-weight:300;font-size:1.3em;line-height:1.5}
        #error{padding-bottom:30px;margin-bottom:50px}
        #message{background:#e0e0e0;padding:20px 0;margin-top:25px}
        @media screen and (max-width: 640px) {
            body{font-size:12px}
        }
    </style>
</head>
<body>
    <div id="error">
        <h1>403</h1>
        <h2><?php Lib\I18n::_e('Forbidden!'); ?></h2>
    </div>
    <div id="message">
        <h3><?php Lib\I18n::_e('We Are Sory'); ?></h3>
        <p><?php Lib\I18n::_e('You do not have permission to access this page.'); ?></p>
    </div>
</body>
</html>