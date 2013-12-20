PDO
=======

<pre>
use \sJo\Db\PDO\Drivers\Mysql as Db;
</pre>

Exemple avec une seule base
=======

<pre>
Db::auth(array(
    'host' => ''
    'dbname' => '',
    'login' => '',
    'password' => '',
    'charset' => ''
));

Db::instance()->results("SELECT * FROM ...");
</pre>

Exemple avec plusieurs seule bases
=======

<pre>
Db::auth(array(
    'MyDb1' => array(
        'host' => ''
        'dbname' => '',
        'login' => '',
        'password' => '',
        'charset' => ''
    ),
    'MyDb2' => array(
        'host' => ''
        'dbname' => '',
        'login' => '',
        'password' => '',
        'charset' => ''
    )
));

Db::instance('MyDb1')->results("SELECT * FROM ...");

Db::instance('MyDb2')->results("SELECT * FROM ...");
</pre>
