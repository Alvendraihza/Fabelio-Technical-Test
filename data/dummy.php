<?php
# we need DB Connection here
require '../inc/connection.php';


// CREATE TABLE IF NOT EXISTS products (
//     id serial PRIMARY KEY,
//     name character varying(100),
//     price numeric(20,0),
//     dimension character varying(50),
//     colors character varying(100),
//     material character varying(100),
//     image character varying(250),
//     stock numeric(10,0)
// );


# query insert
$sql = "insert into products(name,price,dimension,colors,material,image,stock) values('%s',%d,'%s','%s','%s','%s',%d)";

# just flag for skipping first row in CSV files
$flag = true;

# just for debugging purpose (feel free to comment out)
# counting how many success data inserted
$success = 0;
$failed = 0;

# load dummy data from CSV
$file = fopen("fab-data.csv", "r");

# do iterate over data for insert into table
while(($line = fgetcsv($file)) !== FALSE) {

  # skip first line which is column name in CSV files
  if ($flag) {
    $flag = false;
    continue;
  }

  # exec insert data to tabel
  $result = pg_query($conn, vsprintf($sql, array_values($line)));
  
  # just for debugging purpose (feel free to comment out)
  (!$result) ? $failed += 1 : $success += 1;
}

# close the CSV file
fclose($file);

# just for debugging purpose (feel free to comment out)
echo "Result: success ($success) failed ($failed)" . PHP_EOL;