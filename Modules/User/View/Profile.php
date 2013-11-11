<?php self::header(); ?>
    <nav class="navbar navbar-default navbar-static-top" role="navigation">
        <div class="container">
            <span class="navbar-brand">My profile</span>
        </div>
    </nav>
    <section id="main" class="container">
        <form role="form" method="post">
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="Enter email" value="<?php echo self::$Module->User->email; ?>" />
            </div>
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" id="exampleInputEmail1" placeholder="Enter name" value="<?php echo self::$Module->User->name; ?>" />
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </section>
<?php self::footer(); ?>