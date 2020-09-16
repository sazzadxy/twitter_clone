<?php
namespace TwitterClone\Follow;

use TwitterClone\User\User;
use TwitterClone\Tweet\Tweet;
use PDO;

class Follow extends User

{
    public function __construct($db)
    {
        $this->db = $db ;
    }

    public function checkFollow($follower_id, $user_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM follow WHERE sender = :user_id AND receiver = :follower_id");
        $stmt->bindParam(":user_id", $user_id);
        $stmt->bindParam(":follower_id", $follower_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function followBtn($proflie_id, $user_id, $followID)
    {
        $data = $this->checkFollow($proflie_id, $user_id);
        if ($this->isLoggedIn() === true) {
            if ($proflie_id != $user_id) {
                if ($data['receiver'] == $proflie_id) {
                    # following btn
                    return "<button class='f-btn following-btn follow-btn' data-follow='".$proflie_id."' data-profile='".$followID."'>Following</button>";
                } else {
                    return "<button class='f-btn follow-btn' data-follow='".$proflie_id."' data-profile='".$followID."'><i class='fa fa-user-plus'></i>Follow</button>";

                }
            } else {
                # edit button
                return "<button class='f-btn' onclick=location.href='".BASE_URL."settings/profile'>Edit profile</button>";
            }
        } else {
            return "<button class='f-btn' onclick=location.href='index.php'><i class='fa fa-user-plus'</i>Follow</button>";
        }
    }

    public function create($table, $fields = array())
    {
        $columns = implode(',', array_keys($fields));
        $values = ':'.implode(', :', array_keys($fields));
        $sql = "INSERT INTO $table ($columns) VALUES ($values)";
        if ($stmt = $this->db->prepare($sql)) {
            foreach ($fields as $key => $value) {
                $stmt->bindValue(':'.$key, $value);
            }
            $stmt->execute();
            return $this->db->lastInsertId();
        }
    }

    public function delete($table, $array)
    {
        $sql   = "DELETE FROM `{$table}`";
        $where = " WHERE ";

        foreach ($array as $name => $value) {
            $sql .= "{$where} `{$name}` = :{$name}";
            $where = " AND ";
        }

        if ($stmt = $this->db->prepare($sql)) {
            foreach ($array as $name => $value) {
                $stmt->bindValue(':'.$name, $value);
            }
            $stmt->execute();
        }
    }

    public function sendNotification($get_id, $user_id, $target, $type)
    {
        $this->create('notification', array('notificationFor' => $get_id, 'notificationFrom' => $user_id, 'target' => $target, 'type' => $type, 'time' => date("Y-m-d H:i:s")));
    }


    public function follow($followID, $user_id, $profileID)
    {
        $this->create('follow', array('sender' => $user_id, 'receiver' => $followID, 'followOn' => date("Y-m-d H:i:s")));
        $this->addFollowCount($followID, $user_id);
        $stmt = $this->db->prepare("SELECT user_id, following, followers FROM users LEFT JOIN
         follow ON sender = :user_id AND CASE WHEN
         receiver = :user_id THEN sender = user_id 
         END WHERE user_id = :profile_id");
        $stmt->execute(array("user_id" => $user_id,"profile_id" => $profileID));
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($data);
        $this->sendNotification($followID, $user_id, $followID, 'follow');
    }

    public function unfollow($folllowID, $user_id, $profileID)
    {
        $this->delete('follow', array('sender' => $user_id, 'receiver' => $folllowID ));
        $this->removeFollowCount($folllowID, $user_id);
        $stmt = $this->db->prepare("SELECT user_id, following, followers FROM users LEFT JOIN
        follow ON sender = :user_id AND CASE WHEN
        receiver = :user_id THEN sender = user_id 
        END WHERE user_id = :profile_id");
       $stmt->execute(array("user_id" => $user_id,"profile_id" => $profileID));
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($data);
    }
    
    public function addFollowCount($folllowID, $user_id)
    {
        $stmt = $this->db->prepare("UPDATE users SET following = following + 1 WHERE user_id = :user_id; UPDATE users SET followers = followers + 1 WHERE user_id = :follow_id ");
        $stmt->execute(array(":user_id" => $user_id, ":follow_id" => $folllowID));
    }

    public function removeFollowCount($folllowID, $user_id)
    {
        $stmt = $this->db->prepare("UPDATE users SET following = following - 1 WHERE user_id = :user_id; UPDATE users SET followers = followers - 1 WHERE user_id = :follow_id ");
        $stmt->execute(array(":user_id" => $user_id, ":follow_id" => $folllowID));
    }

    public function followingList($proflie_id, $user_id, $followID)
    {
        $stmt = $this->db->prepare("SELECT * FROM users LEFT JOIN follow ON receiver = user_id 
        AND CASE WHEN sender = :user_id THEN receiver = user_id END WHERE sender IS NOT NULL");
        $stmt->bindParam(":user_id", $proflie_id);
        $stmt->execute();
        $followings = $stmt->fetchAll(PDO::FETCH_OBJ);

        foreach ($followings as $following) {
            echo '<div class="follow-unfollow-box">
            <div class="follow-unfollow-inner">
                <div class="follow-background">
                    <img src="'.BASE_URL.$following->profileCover.'"/>
                </div>
                <div class="follow-person-button-img">
                    <div class="follow-person-img"> 
                         <img src="'.BASE_URL.$following->profileImage.'"/>
                    </div>
                    <div class="follow-person-button">
                         <!-- FOLLOW BUTTON -->
                         '.$this->followBtn($following->user_id, $user_id, $followID).'
                    </div>
                </div>
                <div class="follow-person-bio">
                    <div class="follow-person-name">
                        <a href="'.BASE_URL.$following->username.'">'.$following->screenName.'</a>
                    </div>
                    <div class="follow-person-tname">
                        <a href="'.BASE_URL.$following->username.'">'.$following->username.'</a>
                    </div>
                    <div class="follow-person-dis">
                    '.Tweet::getTweetLinks($following->bio).'
                    </div>
                </div>
            </div>
        </div>';
        }
    }

    public function followersList($proflie_id, $user_id, $followID)
    {
        $stmt = $this->db->prepare("SELECT * FROM users LEFT JOIN follow ON sender = user_id 
        AND CASE WHEN receiver = :user_id THEN sender = user_id END WHERE receiver IS NOT NULL");
        $stmt->bindParam(":user_id", $proflie_id);
        $stmt->execute();
        $followers = $stmt->fetchAll(PDO::FETCH_OBJ);

        foreach ($followers as $follower) {
            echo '<div class="follow-unfollow-box">
            <div class="follow-unfollow-inner">
                <div class="follow-background">
                    <img src="'.BASE_URL.$follower->profileCover.'"/>
                </div>
                <div class="follow-person-button-img">
                    <div class="follow-person-img"> 
                         <img src="'.BASE_URL.$follower->profileImage.'"/>
                    </div>
                    <div class="follow-person-button">
                         <!-- FOLLOW BUTTON -->
                         '.$this->followBtn($follower->user_id, $user_id, $followID).'
                    </div>
                </div>
                <div class="follow-person-bio">
                    <div class="follow-person-name">
                        <a href="'.BASE_URL.$follower->username.'">'.$follower->screenName.'</a>
                    </div>
                    <div class="follow-person-tname">
                        <a href="'.BASE_URL.$follower->username.'">'.$follower->username.'</a>
                    </div>
                    <div class="follow-person-dis">
                    '.Tweet::getTweetLinks($follower->bio).'
                    </div>
                </div>
            </div>
        </div>';
        }
    }

    public function whoToFollow($user_id, $profileID)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE user_id != :user_id 
        AND user_id NOT IN (SELECT receiver FROM follow WHERE sender = :user_id) 
        ORDER BY rand() LIMIT 3");
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_OBJ);

        echo '<div class="follow-wrap"><div class="follow-inner"><div class="follow-title"><h3>Who to follow</h3></div>';
        foreach ($users as $user ) {
            echo '<div class="follow-body">
            <div class="follow-img">
              <img src="'.BASE_URL.$user->profileImage.'"/>
            </div>
            <div class="follow-content">
                <div class="fo-co-head">
                    <a href="'.BASE_URL.$user->username.'">'.$user->screenName.'</a><span>@'.$user->username.'</span>
                </div>
                <!-- FOLLOW BUTTON -->
                '.$this->followBtn( $user->user_id, $user_id, $profileID).'
            </div>
        </div>';
        }
        echo '</div>
        </div>';
    }

}



?>