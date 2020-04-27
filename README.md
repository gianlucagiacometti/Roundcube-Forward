# PostfixAdmin Forward Plugin for RoudCube

AUTHOR

Gianluca Giacometti (php@gianlucagiacometti.it)



CONTRIBUTORS

Sebastien Blaisot (https://github.com/sblaisot)
Jan B. Fiedler (https://github.com/zuloo)
Ray Deng (https://github.com/iBL1nK/)
Sebastian L (https://github.com/brknkfr)


VERSION

1.4.0



RELEASE DATE

27-04-2020



INSTALL

Requirements :
- jQuery UI.

To install this plugin, copy all files into /plugin/forward folder and
add it to the plugin array in config/config.inc.php :

// List of active plugins (in plugins/ directory)
$rcmail_config['plugins'] = array('forward');

note: if you have more than one plugin installed, add them to the array
eg:

// List of active plugins (in plugins/ directory)
$rcmail_config['plugins'] = array('forward, PLUGIN2, PLUGIN3');



CONFIGURATION

Edit the plugin configuration file 'config.inc.php' and choose the appropriate options:

$rcmail_config['forward_driver'] = 'sql';

    so far only sql is available

$rcmail_config['forward_sql_dsn'] = value;

    example value: 'pgsql://username:password@host/database'
    example value: 'mysql://username:password@host/database'

$rcmail_config['forward_sql_write'] = query;

    the query depends upon your postfixadmin database structure
    placeholders %goto and %address must be kept unchanged
    default query: 'UPDATE alias SET goto = %goto, modified = %modified WHERE address = %address'
    example query: 'UPDATE alias SET forwardto = %goto, modified = %modified WHERE address = %address'
    example query: 'UPDATE aliases SET forwardto = %goto, modified = %modified WHERE address = %address'

$rcmail_config['forward_sql_read'] = query;

    the query depends upon your postfixadmin database structure
    placeholder %address must be kept unchanged
    default query: 'SELECT * FROM alias WHERE address = %address'
    example query: 'SELECT * FROM aliases WHERE address = %address'



LICENCE

Licensed under GNU GPL2 licence.



NOTE

The code is based on Vacation plugin (rc-vacation) by Boris HUISGEN et al. (https://github.com/bhuisgen/rc-vacation).
Thank you Boris et al.
