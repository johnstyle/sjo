<?php self::header(); ?>
    <form role="form" class="form-signin" method="post">
        <h2 class="form-signin-heading"><?php echo \sJo\Libraries\I18n::__('Authentification'); ?></h2>
        <?php self::$Core->Alert->display(); ?>
        <input type="hidden" name="token" value="<?php echo self::$Core->Request->getToken('User/Auth::signin'); ?>" />
        <input type="hidden" name="controller" value="User/Auth" />
        <input type="hidden" name="method" value="signin" />
        <input type="text" class="form-control" placeholder="<?php echo \sJo\Libraries\I18n::__('Adresse email'); ?>" name="email" value="<?php echo \sJo\Libraries\Env::post('email'); ?>" autofocus />
        <input type="password" class="form-control" placeholder="<?php echo \sJo\Libraries\I18n::__('Mot de passe'); ?>" name="password" />
        <button class="btn btn-lg btn-primary btn-block" type="submit"><?php echo \sJo\Libraries\I18n::__('Connexion'); ?></button>
    </form>
<?php self::footer(); ?>