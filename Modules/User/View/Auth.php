<?php self::header(); ?>
    <form class="form-signin" method="post">
        <h2 class="form-signin-heading">Authentification</h2>
        <?php self::$Core->Alert->display(); ?>
        <input type="hidden" name="token" value="<?php echo self::$Core->Request->getToken('Auth::signin'); ?>" />
        <input type="hidden" name="controller" value="Auth" />
        <input type="hidden" name="method" value="signin" />        
        <input type="text" class="form-control" placeholder="Adresse email" name="login" value="<?php echo \PHPTools\Libraries\Env::post('login'); ?>" autofocus />
        <input type="password" class="form-control" placeholder="Mot de passe" name="password" />
        <button class="btn btn-lg btn-primary btn-block" type="submit">Connexion</button>
    </form>
<?php self::footer(); ?>