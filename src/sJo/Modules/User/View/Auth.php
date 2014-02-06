<?php use \sJo\Libraries as Libs;
self::header(); ?>
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
    <form role="form" class="form-signin" method="post">
        <h2 class="form-signin-heading"><?php Libs\I18n::_e('Authentification'); ?></h2>
        <?php self::$Core->Alert->display(); ?>
        <input type="hidden" name="token" value="<?php echo self::$Core->Request->getToken('User/Auth::signin'); ?>" />
        <input type="hidden" name="controller" value="User/Auth" />
        <input type="hidden" name="method" value="signin" />
        <p><input type="text" class="form-control" placeholder="<?php Libs\I18n::_e('Adresse email'); ?>" name="email" value="<?php echo Libs\Env::post('email'); ?>" autofocus /></p>
        <p><input type="password" class="form-control" placeholder="<?php Libs\I18n::_e('Mot de passe'); ?>" name="password" /></p>
        <p><button class="btn btn-lg btn-primary btn-block" type="submit"><?php Libs\I18n::_e('Connexion'); ?></button></p>
    </form>
<?php self::footer(); ?>