<?php

use sJo\Libraries as Lib;
use sJo\Loader\Router;
use sJo\Request\Request;
use sJo\View\Helper;

?><!DOCTYPE html>
<html lang="<?php echo Lib\I18n::country(); ?>">
<head>
    <meta charset="<?php echo SJO_CHARSET; ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="<?php echo SJO_BASEHREF; ?>" />
    <title><?php Lib\I18n::_e('Authentification'); ?></title>
    <meta name="description" content="<?php Lib\I18n::_e('Authentification'); ?>">
    <?php if(Helper\Style::hasRegistry()): ?>
        <?php Helper\Style::applyRegistry(); ?>
    <?php endif; ?>
    <style type="text/css">
        body{background:#333}
        form{background:#fff;width:300px;margin:150px auto 0;padding:0 20px 20px;border:5px solid #000;border-radius:10px;box-shadow:0 0 10px #000}
    </style>
</head>
<body>

<?php

Helper\Form::create(array(
    Helper\Fieldset::create(array(
        Helper\Token::create(Router::getToken('signin')),
        Helper\Container::create(array(
            'tagname' => 'h2',
            'attributes' => array(
                'class' => 'form-signin-heading'
            ),
            'elements' => Lib\I18n::__('Authentification')
        )),
        Helper\Alert::create(),
        Helper\Input::create(array(
            'attributes' => array(
                'name' => 'email',
                'value' => Request::env('POST')->email->val(),
                'placeholder' => Lib\I18n::__('Adresse email'),
                'autofocus' => true
            )
        )),
        Helper\Input::create(array(
            'attributes' => array(
                'type' => 'password',
                'name' => 'password',
                'placeholder' => Lib\I18n::__('Mot de passe')
            )
        )),
        Helper\Button::create(array(
            'attributes' => array(
                'class' => 'btn-lg btn-block btn-primary',
                'value' => Lib\I18n::__('Connexion')
            )
        ))
    ))
))->render();

?>
</body>
</html>