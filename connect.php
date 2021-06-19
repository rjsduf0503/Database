
<?php
    include 'OracleDB.php';
    // $orcl = oci_connect('TP201702043', 'Chess00700', 'localhost');
    $orcl = new OracleDB('127.0.0.1', 'TP201702043', 'Chess00700');
    $orcl->connect();

    // SELECT 문(OracleDB)
    $query = "SELECT * FROM EBOOK";
    $list = $orcl->select($query);

    
    foreach($list as $row){
    
        echo $row["ISBN"] . "/";
        // echo $row[""] . "<br>";
    
    }
?>