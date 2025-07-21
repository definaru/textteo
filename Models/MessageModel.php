<?php

namespace App\Models;

use CodeIgniter\Model;

class MessageModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'messages';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    /**
     * @var string[] List of allowed fields in the model.
     */
    protected $allowedFields    = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    protected $db;
    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }
    /**
     * Get List Doctor Clinic User For Patient Chat
     * 
     * @param mixed $user_id
     * @param mixed $keywords
     * @return mixed
     */
    public function getPatients($user_id, $keywords = '')
    {
        $builder = $this->db->table('appointments a');
        $builder->select("
        CONCAT(u.first_name,' ',u.last_name),u.id as userid,u.role,u.first_name,u.last_name,u.username,u.profileimage,
        (select chatdate from chat where recieved_id = userid OR sent_id = userid ORDER BY chatdate DESC LIMIT 1) as chatdate,
        (select msg from chat where sent_id =userid OR recieved_id = userid ORDER BY id DESC LIMIT 1) as last_msg,
        (select type from chat where sent_id = userid OR recieved_id = userid ORDER BY id DESC LIMIT 1) as type,
        (select date_time from user_online_status where user_id=userid) as date_time,
        (select time_zone from user_online_status where user_id=userid) as time_zone,
        (select count(id) from chat where recieved_id=" . $user_id . " and sent_id=userid and read_status=0) as unread_count");
        $builder->join('users u', 'u.id = a.appointment_from', 'left');
        $builder->where('a.appointment_to', $user_id);
        if (!empty($keywords)) {
            $builder->groupStart();
            $builder->like('CONCAT(u.first_name,\' \',u.last_name)', $keywords, 'both');
            $builder->orLike('u.last_name', $keywords, 'both');
            $builder->groupEnd();
        }
        $builder->groupBy('a.appointment_from');
        $builder->orderBy('chatdate', 'DESC');
        return $builder->get()->getResultArray();
        // return $this->db->getLastQuery();  
    }
    /**
     * Get List Of Doctor for chat to Patient
     * 
     * @param mixed $user_id
     * @param mixed $keywords
     * @return mixed
     */
    public function getDoctors($user_id, $keywords = '')
    {
        $builder = $this->db->table('appointments a');
        $builder->select('u.id as userid,u.role,u.first_name,u.last_name,u.username,u.profileimage,
		(select chatdate from chat where (recieved_id = userid OR sent_id = userid) and (sent_id ="' . $user_id . '" OR recieved_id = "' . $user_id . '") ORDER BY chatdate DESC LIMIT 1) as chatdate,
		(select msg from chat where (sent_id=userid OR recieved_id = userid) and (sent_id ="' . $user_id . '" OR recieved_id = "' . $user_id . '") ORDER BY id DESC LIMIT 1) as last_msg,
		(select type from chat where (sent_id = userid OR recieved_id = userid) and (sent_id ="' . $user_id . '" OR recieved_id = "' . $user_id . '") ORDER BY id DESC LIMIT 1) as type,
		(select date_time from user_online_status where user_id=userid)  as date_time,
		(select time_zone from user_online_status where user_id=userid) as time_zone,
		(select count(id) from chat where recieved_id="' . $user_id . '" and sent_id=userid and read_status=0) as unread_count');
        $builder->join('users u', 'u.id = a.appointment_to', 'left');
        $builder->where('a.appointment_from', $user_id);
        if (!empty($keywords)) {
            $builder->groupStart();
            $builder->like('u.first_name', $keywords, 'after');
            $builder->orLike('u.last_name', $keywords, 'after');
            $builder->orLike('CONCAT(u.first_name," ", u.last_name)', $keywords);
            $builder->groupEnd();
        }
        $builder->groupBy('a.appointment_to');
        $builder->orderBy('chatdate', 'DESC');
        return $builder->get()->getResultArray();
    }
    /**
     * Get Latest Chat for users
     * 
     * @param mixed $selected_user
     * @param mixed $user_id
     * @return mixed
     */
    public function get_latest_chat($selected_user, $user_id)
    {
        $per_page = 5;
        $total =  $this->get_total_chat_count($selected_user, $user_id);
        if ($total > 5) {
            $total = $total - 5;
        } else {
            $total = 0;
        }

        $this->update_counts($selected_user, $user_id);

        $query = $this->db->query("SELECT DISTINCT CONCAT(sender.first_name,' ',sender.last_name) as senderName, sender.profileimage as senderImage, sender.id as sender_id,msg.msg, msg.chatdate,msg.id,msg.type,msg.file_name,msg.file_path,msg.time_zone,msg.id
                from chat msg
                LEFT  join users sender on msg.sent_id = sender.id
                left join chat_deleted_details cd on cd.chat_id  = msg.id
                where cd.can_view = $user_id AND ((msg.recieved_id = $selected_user AND msg.sent_id = $user_id) or  (msg.recieved_id = $user_id AND msg.sent_id =  $selected_user))   ORDER BY msg.id ASC LIMIT $total,$per_page ");
        $result = $query->getResultArray();
        return $result;
    }
    /**
     * Get Tolet Chat Count
     * 
     * @param mixed $selected_user
     * @param mixed $user_id
     * @return mixed
     */
    public function get_total_chat_count($selected_user, $user_id)
    {

        $sql = "SELECT msg.id  from chat msg
                left join chat_deleted_details cd on cd.chat_id  = msg.id
                where  cd.can_view = $user_id AND ((msg.recieved_id = $selected_user AND msg.sent_id = $user_id) or  (msg.recieved_id = $user_id AND msg.sent_id =  $selected_user))   ORDER BY msg.id DESC ";

        return  $this->db->query($sql)->getNumRows();
    }
    /**
     * Update Counts
     * 
     * @param mixed $selected_user
     * @param mixed $user_id
     * @return mixed
     */
    public function update_counts($selected_user, $user_id)
    {
        $query = $this->db->query("SELECT msg.id
                                from chat msg
                                LEFT  join users sender on msg.sent_id = sender.id
                                where msg.delete_sts = 0 AND  msg.read_status = 0 AND (msg.recieved_id = $user_id AND msg.sent_id =  $selected_user) ");
        $result = $query->getResultArray();

        if (!empty($result)) {
            foreach ($result as $d) {
                $this->db->table("chat")->where('id', $d['id'])->set('read_status', 1)->update();
            }
        } else {
            return true;
        }
    }
    /**
     * Get Chat for Users
     * 
     * @param mixed $where
     * @return mixed
     */
    public function getChatHistory($where)
    {
        $builder = $this->db->table('chat c');
        $builder->select('*');
        $builder->where($where);
        $builder->orderBy('id', 'DESC');
        return $builder->get()->getResultArray();
    }
    /**
     * Get Chat for Users
     * 
     * @param mixed $where
     * @return mixed
     */
    public function getCountMsg($where)
    {
        $builder = $this->db->table('chat c');
        $builder->select('a.username,COUNT(c.id) as count');
        $builder->join('users a ', 'a.id = c.sent_id', 'left');
        $builder->where($where);
        $builder->groupBy('c.sent_id');
        return $builder->get()->getResultArray();
    }
    /**
     * Get Chat for Users
     * 
     * @param mixed $selected_user
     * @param mixed $user_id
     * @return mixed
     */
    public function getNewChat($selected_user, $user_id)
    {
        $this->update_counts($selected_user, $user_id);
        $query = $this->db->query("SELECT DISTINCT CONCAT(sender.first_name,' ',sender.last_name) as senderName, sender.profileimage as senderImage, sender.id as sender_id,CONCAT(receiver.first_name,' ',receiver.last_name) as receiverName, receiver.profileimage as receiverImage, receiver.id as receiver_id,receiver.device_id as receiver_device_id,receiver.device_type as receiver_device_type,msg.msg, msg.chatdate,msg.id,msg.type,msg.file_name,msg.file_path,msg.time_zone,msg.id,sender.first_name as sender_from_firstusername,sender.last_name as sender_from_lastusername,receiver.first_name as reciever_first_username,receiver.last_name as reciever_last_username
        from chat msg
        LEFT  join users sender on msg.sent_id = sender.id
        LEFT  join users receiver on msg.recieved_id = receiver.id
        left join chat_deleted_details cd on cd.chat_id  = msg.id
        where cd.can_view = $user_id AND ((msg.recieved_id = $selected_user AND msg.sent_id = $user_id) or  (msg.recieved_id = $user_id AND msg.sent_id =  $selected_user))   ORDER BY msg.id ASC ");
        $result = $query->getResultArray();
        return $result;
    }
}
