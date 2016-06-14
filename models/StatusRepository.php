<?php

class StatusRepository extends DbRepository{
    
    public function insert($user_id, $body){
        $now = new datetime();
        
        $sql = 'INSERT INTO status (user_id, body, created_at) VALUES(:user_id, :body, :created_at)';
        
        $stmt = $this->execute($sql,array(
            ':user_id' => $user_id,
            ':body' => $body,
            ':created_at' => $now->format('Y-m-d H:i:s'),
        ));
    }
    
    public function fetchAllPersonalArchiveByUserId($user_id){
        $sql = 'SELECT a.*, u.user_name FROM status a LEFT JOIN user u ON a.user_id = u.id WHERE u.id = :user_id ORDER BY a.created_at DESC';
        
        return $this->fetchAll($sql,array(':user_id' => $user_id));
    }
}

?>