<?php

// LOCAL CONNECTION
// $DBHOST = "localhost";
// $DBPORT = "5432";
// $DBNAME = "alexey";
// $DBUSER = "postgres";
// $DBPASS = "rahasia";
// $DSN = "host={$DBHOST} port={$DBPORT} dbname={$DBNAME} user={$DBUSER} password={$DBPASS}";
// $conn = pg_connect($DSN);

HEROKU CONNECTION
$conn = pg_connect(getenv("DATABASE_URL"));