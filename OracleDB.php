<?php
class OracleDB{
   
    private $host; //호스트 이름
    private $user; //유저 이름
    private $pwd; //유저 비밀번호
    private $tns; //tns name
    private $dbh;
   
    public function __construct($host, $user, $pwd){
       
        /*
        DESCRIPTION : 접속하고자 하는 대상의 데이터베이스 정보
        ADDRESS : 접속하고자 하는 데이터베이스 서버의 리스너를 호출하기 위한 주소 정보
        CONNECT_DATA : SERVICE_NAME 옵션을 이용해 접속할 리스터 프로세스가 사용하는 서비스
        이름을 지정하거나 SID 옵션을 리용하여 데이터베이스의 SID명을 지정
        */
        $this->tns = "
        (DESCRIPTION=
            (ADDRESS_LIST=
              (ADDRESS=(PROTOCOL=TCP)(HOST=$host)(PORT=1521)))(CONNECT_DATA=(SERVICE_NAME=XE)))";
       
        $this->host = $host;
        $this->user = $user;
        $this->pwd = $pwd;
        $this->dbh = NULL;
    }
   
    public function __destruct(){
        unset($this->dbh);
    }
   
    public function connect(){
       
        $tns = $this->tns;
        $user = $this->user;
        $db_pwd = $this->pwd;
       
        // echo($tns." ".$user." ".$db_pwd);
        try{
            $this->dbh = new PDO("oci:dbname=".$tns.";charset=UTF8", $user, $db_pwd);
            return $this->dbh;
        }catch(PDOException $e){
            echo ($e->getMessage());
        }
       
    }

   
    public function select($query){
       
        $stmt = NULL;
        $dbh = $this->dbh;
       
        if ( $dbh != NULL ){
            $stmt = $dbh->prepare($query);
            $stmt->execute();
            $list = $stmt->fetchAll();
           
            return $list;
        }
       
        return NULL;
       
    }
   
    public function insert($subject){
        $stmt = NULL;
        $dbh = $this->dbh;
        $query = "INSERT INTO MEMBER(SUBJECT) VALUES(:subject)";
       
        if ( $dbh != NULL ){
            $stmt = $dbh->prepare($query);
            $stmt->bindParam(':subject', $subject);
            $stmt->execute();
           
            return true;
        }
       
        return false;
       
    }
   
    public function modify(){

        
    }
}

?>