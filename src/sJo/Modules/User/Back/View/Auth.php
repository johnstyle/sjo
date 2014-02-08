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
    <title><?php Lib\I18n::_e('Authentification'); ?></title>
    <meta name="description" content="<?php Lib\I18n::_e('Authentification'); ?>">
    <?php Style::display(); ?>
    <style type="text/css">
        body {
            background:#333
        }
        .form-signin {
            background:#fff;
            width:300px;
            margin:0 auto;
            padding:0 20px 20px;
            border:5px solid #000;
            border-radius:10px;
            box-shadow: 0 0 10px #000;
        }
    </style>
</head>
<body>
    <form role="form" class="form-signin" method="post">
        <h2 class="form-signin-heading"><?php Lib\I18n::_e('Authentification'); ?></h2>
        <?php self::$Core->Alert->display(); ?>
        <input type="hidden" name="token" value="<?php echo self::$Core->Request->getToken('User/Auth::signin'); ?>" />
        <input type="hidden" name="controller" value="User/Auth" />
        <input type="hidden" name="method" value="signin" />
        <p><input type="text" class="form-control" placeholder="<?php Lib\I18n::_e('Adresse email'); ?>" name="email" value="<?php echo Lib\Env::post('email'); ?>" autofocus /></p>
        <p><input type="password" class="form-control" placeholder="<?php Lib\I18n::_e('Mot de passe'); ?>" name="password" /></p>
        <p><button class="btn btn-lg btn-primary btn-block" type="submit"><?php Lib\I18n::_e('Connexion'); ?></button></p>
    </form>
</body>
</html>