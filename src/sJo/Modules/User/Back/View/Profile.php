<?php use sJo\Modules\User\Model\User;
self::header(); ?>
    <form method="post">
        <div class="row">
            <div class="col-lg-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">khhkj</h3>
                    </div>
                    <div class="panel-body">
                        <fieldset>
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
                        </fieldset>
                    </div>
                    <div class="panel-footer clearfix">
                        <button type="submit" class="btn btn-primary pull-right">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
<?php self::footer(); ?>