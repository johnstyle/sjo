<?php use sJo\Modules\User\Model\User;
self::header(); ?>
    <h1 class="page-header">My profil</h1>
    <form method="post">
        <input type="hidden" name="token" value="<?php echo self::$Core->Request->getToken('User/Profile::update'); ?>" />
        <input type="hidden" name="controller" value="User/Profile" />
        <input type="hidden" name="method" value="update" />
        <div class="form-group">
            <label for="email">Email address</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="Enter email" value="<?php echo User::getInstance()->email; ?>" />
        </div>
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" placeholder="Enter name" value="<?php echo User::getInstance()->name; ?>" />
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
<?php self::footer(); ?>