phpmysearch
===========

Search pattern in a mysql database schema.


    Usage: phpmysearch.php -d <db_name> -t <table_name> -s <pattern> [OPTION]...

        -h      Print help
        -d      db_name
        -t      table_name
        -s      pattern


Examples
========

Search any table that contain the string 'user' in their name

    $ php phpmysearch.php  -d tifomatic -s user
    search for "user"
    searching in db mysql
        matched in table : user

Search in all databases

    $ php phpmysearch.php -s user
    search for "user"
    searching in db mysql, table general_log
        matched in field : user_host

    searching in db mysql, table proxies_priv
        matched in field : Proxied_user

    searching in db mysql, table slow_log
        matched in field : user_host

    searching in db mysql, table user
        matched in field : Create_user_priv
        matched in field : max_user_connections

    searching in db mysql
        matched in table : user
