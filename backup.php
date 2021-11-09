<?php
$DATABASE="lacynaco_db";
$DBUSER="lacynaco_user";
$DBPASSWD="h!rF5X82UyQL";
$PATH=getcwd()."/backup/";
$FILE_NAME="site-name-backup-" . date("Y-m-d_H:i") . ".sql.gz";
exec('/usr/bin/mysqldump -u '.$DBUSER.' -p'.$DBPASSWD.' '.$DATABASE.' | gzip --best > '.$PATH.$FILE_NAME);
//echo "Database(".$DATABASE.") backup completed. File name: ".$PATH.$FILE_NAME;
?>