<?php use sJo\Modules\User\Model\User;
self::header(); ?>
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <span class="navbar-brand">My profile</span>
        </div>
    </nav>
    <section id="main" class="container">
        <form method="post">
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="Enter email" value="<?php echo User::current()->email; ?>" />
            </div>
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Enter name" value="<?php echo User::current()->name; ?>" />
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </section>
<?php self::footer(); ?>