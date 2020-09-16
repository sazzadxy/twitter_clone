<?php
namespace TwitterClone\Tweet;
use PDO;
use TwitterClone\User\User;
use TwitterClone\File\File;


class Tweet extends User
{
    public function __construct($db)
    {
        $this->db = $db ;
    }

    public function tweets($user_id, $num)
    {
        $stmt = $this->db->prepare("SELECT * FROM tweets 
        LEFT JOIN users ON tweetBy = user_id 
        WHERE tweetBy = :user_id AND retweetID = '0'
        OR tweetBy = user_id AND retweetBy != :user_id 
        AND tweetBy IN(SELECT receiver FROM follow WHERE sender = :user_id)
        ORDER BY tweetID DESC LIMIT :num ");
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->bindParam(":num", $num, PDO::PARAM_INT);
        $stmt->execute();
        $tweets =  $stmt->fetchAll(PDO::FETCH_OBJ);

        foreach ($tweets as $tweet ) {
            $retweet = $this->checkRetweet($tweet->tweetID, $user_id);
            $likes   = $this->likes($user_id, $tweet->tweetID);
            $user    = $this->profileData($tweet->retweetBy);
            echo '<div class="all-tweet">
            <div class="t-show-wrap">	
             <div class="t-show-inner">
             '.(($retweet['retweetID'] === $tweet->retweetID OR $tweet->retweetID > 0) ? '
                <div class="t-show-banner">
                    <div class="t-show-banner-inner">
                        <span><i class="fa fa-retweet" aria-hidden="true"></i></span><span>'.$user->screenName.' Retweeted</span>
                    </div>
                </div>
                ' : '').'

                '.((!empty($tweet->retweetMsg) && $tweet->tweetID === $retweet['tweetID'] or $tweet->retweetID > 0) ? '
                <div class="t-show-popup" data-tweet="'.$tweet->tweetID.'">
                <div class="t-show-head">
                <div class="t-show-img">
                    <img src="'.BASE_URL.$user->profileImage.'"/>
                </div>
                <div class="t-s-head-content">
                    <div class="t-h-c-name">
                        <span><a href="'.BASE_URL.$user->username.'">'.$user->screenName.'</a></span>
                        <span>@'.$user->username.'</span>
                        <span>'.File::timeAgo($retweet['postedOn']).'</span>
                    </div>
                    <div class="t-h-c-dis">
                        '.self::getTweetLinks($tweet->retweetMsg).'
                    </div>
                </div>
            </div>
            <div class="t-s-b-inner">
                <div class="t-s-b-inner-in">
                    <div class="retweet-t-s-b-inner">
                        '.((!empty($tweet->tweetImage)) ? ' 
                        <div class="retweet-t-s-b-inner-left">
                            <img src="'.BASE_URL.$tweet->tweetImage.'" class="imagePopup" data-tweet="'.$tweet->tweetID.'"/>	
                        </div>  ' : '').'
                        <div>
                            <div class="t-h-c-name">
                                <span><a href="'.BASE_URL.$tweet->username.'">'.$tweet->screenName.'</a></span>
                                <span>@'.$tweet->username.'</span>
                                <span>'.File::timeAgo($tweet->postedOn).'</span>
                            </div>
                            <div class="retweet-t-s-b-inner-right-text">		
                                '.self::getTweetLinks($tweet->status).'
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
            ' : '
                <div class="t-show-popup" data-tweet="'.$tweet->tweetID.'">
                    <div class="t-show-head">
                        <div class="t-show-img">
                            <img src="'.$tweet->profileImage.'"/>
                        </div>
                        <div class="t-s-head-content">
                            <div class="t-h-c-name">
                                <span><a href="'.$tweet->username.'">'.$tweet->screenName.'</a></span>
                                <span>@'.$tweet->username.'</span>
                                <span>'.File::timeAgo($tweet->postedOn).'</span>
                            </div>
                            <div class="t-h-c-dis">
                            '.self::getTweetLinks($tweet->status).'
                            </div>
                        </div>
                    </div>'.
                    ((!empty($tweet->tweetImage)) ? 
                        '<!--tweet show head end-->
                        <div class="t-show-body">
                          <div class="t-s-b-inner">
                           <div class="t-s-b-inner-in">
                             <img src="'.BASE_URL.$tweet->tweetImage.'" class="imagePopup" data-tweet="'.$tweet->tweetID.'"/>
                           </div>
                          </div>
                        </div>
                        <!--tweet show body end-->
                        ' : '').'
                                
                </div>').'

                <div class="t-show-footer">
                    <div class="t-s-f-right">
                        <ul> 
                            <li><button><i class="fa fa-share" aria-hidden="true"></i></button></li>	
                            <li>'.(($tweet->tweetID === $retweet['retweetID']) ? '<button class="retweeted" data-tweet="'.$tweet->tweetID.'" data-user="'.$tweet->tweetBy.'"><i class="fa fa-retweet" aria-hidden="true"></i><span class="retweetsCount">'.$tweet->retweetCount.'</span></button>' : '<button class="retweet" data-tweet="'.$tweet->tweetID.'" data-user="'.$tweet->tweetBy.'"><i class="fa fa-retweet" aria-hidden="true"></i><span class="retweetsCount">'.(($tweet->retweetCount > 0) ? $tweet->retweetCount : '').'</span></button>').'</li>
                            <li>'.(($likes['likeOn']) === $tweet->tweetID ? '<button class="unlike-btn" data-tweet="'.$tweet->tweetID.'" data-user="'.$tweet->tweetBy.'"><i class="fa fa-heart" aria-hidden="true"></i><span class="likesCounter">'.$tweet->likesCount.'</span></button>' : '<button class="like-btn" data-tweet="'.$tweet->tweetID.'" data-user="'.$tweet->tweetBy.'"><i class="fa fa-heart-o" aria-hidden="true"></i><span class="likesCounter">'.(($tweet->likesCount > 0) ? $tweet->likesCount : '').'</span></button>').'</li>
                           '.(($tweet->tweetBy === $user_id) ? '
                            <li>
                                <a href="#" class="more"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></a>
                                <ul> 
                                  <li><label class="deleteTweet" data-tweet="'.$tweet->tweetID.'">Delete Tweet</label></li>
                                </ul>
                            </li>
                            ' : '').'
                        </ul>
                    </div>
                </div>
            </div>
            </div>
            </div>';
        }
    }

    public function getUserTweets($user_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM tweets 
        LEFT JOIN users ON tweetBy = user_id 
        WHERE tweetBy = :user_id
        AND retweetID = '0' OR retweetBy = :user_id ORDER BY tweetID DESC");
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getTrendByHash($hashtag)
    {
        $stmt = $this->db->prepare("SELECT * FROM trends WHERE hashtag LIKE :hashtag LIMIT 5");
        $stmt->bindValue(':hashtag', '%'.$hashtag.'%');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getMention($mention)
    {
        $stmt = $this->db->prepare("SELECT user_id, username, screenName, profileImage FROM
         users WHERE username LIKE :mention OR screenName LIKE :mention LIMIT 5");
        $stmt->bindValue(':mention', '%'.$mention.'%');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function addTrend($hashtag)
    {
        preg_match_all("/#+([a-zA-Z0-9_]+)/i", $hashtag, $matches);
        if ($matches) {
            $result = array_values($matches[1]);
        }

        $sql = "INSERT INTO trends (hashtag, createdOn) VALUES (:hashtag, CURRENT_TIMESTAMP)
         ON DUPLICATE KEY UPDATE hashtag = :hashtag, createdOn = CURRENT_TIMESTAMP ";

        foreach ($result as $trend) {
            if ($stmt = $this->db->prepare($sql)) {
                $stmt->execute(array(':hashtag' => $trend));
            }
        }
    }

    public function trends()
    {
        $stmt = $this->db->prepare("SELECT *, COUNT(tweetID) AS tweetsCount FROM trends 
        INNER JOIN tweets ON status
        LIKE CONCAT('%#', hashtag, '%') OR retweetMsg
        LIKE CONCAT('%#', hashtag, '%') 
        GROUP BY hashtag ORDER BY tweetID ");
        $stmt->execute();
        $trends = $stmt->fetchAll(PDO::FETCH_OBJ);

        //run mysql command line: SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY','')); 

        echo '<div class="trend-wrapper"><div class="trend-inner"><div class="trend-title"><h3>Trends</h3></div><!-- trend title end-->';
        foreach ($trends as $trend ) {
            echo '<div class="trend-body">
            <div class="trend-body-content">
                <div class="trend-link">
                    <a href="'.BASE_URL.'hashtag/'.$trend->hashtag.'">#'.$trend->hashtag.'</a>
                </div>
                <div class="trend-tweets">
                  '.$trend->tweetsCount.' <span>tweets</span>
                </div>
            </div>
        </div>';
        }
        echo '</div></div>';
    }

    public function getTweetsByHash($hashtag)
    {
        $stmt = $this->db->prepare("SELECT * FROM tweets 
        LEFT JOIN users ON tweetBY = user_id WHERE 
        status LIKE :hashtag OR retweetMsg LIKE :hashtag ");
        $stmt->bindValue(":hashtag", '%#'.$hashtag.'%');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getUserByHash($hashtag)
    {
        $stmt = $this->db->prepare("SELECT DISTINCT * FROM tweets 
        INNER JOIN users ON tweetBy = user_id 
        WHERE status LIKE :hashtag OR retweetMsg LIKE :hashtag GROUP BY user_id");
        $stmt->bindValue(":hashtag", '%#'.$hashtag.'%');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function getTweetLinks($tweet)
    {
        $tweet = preg_replace("/(https?:\/\/)([\w]+.)([\w\.]+)/", "<a href='$0' target='_blank' rel='noopener noreferrer'>$0</a>", $tweet);
        $tweet = preg_replace("/#([\w]+)/", "<a href='".BASE_URL."hashtag/$1'>$0</a>", $tweet);
        $tweet = preg_replace("/@([\w]+)/", "<a href='".BASE_URL."$1'>$0</a>", $tweet);
        return $tweet;
    }

    public function getPopupTweet($tweet_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM tweets, users WHERE tweetID = :tweet_id AND tweetBy = user_id ");
        $stmt->bindParam(":tweet_id", $tweet_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function retweet($tweet_id, $user_id, $get_id, $comment)
    {
        $stmt = $this->db->prepare("UPDATE tweets SET retweetCount = retweetCount +1 WHERE tweetID = :tweet_id AND tweetBy = :get_id");
        $stmt->bindParam(":tweet_id", $tweet_id, PDO::PARAM_INT);
        $stmt->bindParam(":get_id", $get_id, PDO::PARAM_INT);
        $stmt->execute();

        $stmt = $this->db->prepare("INSERT INTO tweets (status, tweetBy, tweetImage, retweetBy, retweetID, likesCount, retweetCount, postedOn, retweetMsg)
         SELECT status, tweetBy, tweetImage, :user_id, tweetID, likesCount, retweetCount, postedOn, :retweetMsg 
         FROM tweets WHERE tweetID = :tweet_id ");
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->bindParam(":tweet_id", $tweet_id, PDO::PARAM_INT);
        $stmt->bindParam(":retweetMsg", $comment, PDO::PARAM_STR);
        $stmt->execute();
        $this->sendNotification($get_id, $user_id, $tweet_id, 'retweet');

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

    public function sendNotification($get_id, $user_id, $target, $type)
    {
        $this->create('notification', array('notificationFor' => $get_id, 'notificationFrom' => $user_id, 'target' => $target, 'type' => $type, 'time' => date("Y-m-d H:i:s") , 'status' => 0));
    }

    public function addLike($user_id, $tweet_id, $get_id)
    {
        $stmt = $this->db->prepare("UPDATE tweets SET likesCount = likesCount +1 WHERE tweetID = :tweet_id ");
        $stmt->bindParam(":tweet_id", $tweet_id, PDO::PARAM_INT);
        $stmt->execute();

        $this->create('likes', array('likeBy' => $user_id, 'likeOn' => $tweet_id));
        
        if ($get_id != $user_id) {
            $this->sendNotification($get_id, $user_id, $tweet_id, 'like');
        }

    }

    public function addMention($status, $user_id, $tweet_id)
    {
        preg_match_all("/@+([a-zA-Z0-9_]+)/i", $status, $matches);
        if ($matches) {
            $result = array_values($matches[1]);
        }

        $sql = "SELECT * FROM users WHERE username = :mention";

        foreach ($result as $trend) {
            if ($stmt = $this->db->prepare($sql)) {
                $stmt->execute(array(':mention' => $trend));
                $data = $stmt->fetch(PDO::FETCH_OBJ);
            }
            if ($data->user_id != $user_id) {
                $this->sendNotification($data->user_id, $user_id, $tweet_id, 'mention');
            }
        }
    }

    public function unLike($user_id, $tweet_id, $get_id)
    {
        $stmt = $this->db->prepare("UPDATE tweets SET likesCount = likesCount -1 WHERE tweetID = :tweet_id ");
        $stmt->bindParam(":tweet_id", $tweet_id, PDO::PARAM_INT);
        $stmt->execute();

        $stmt = $this->db->prepare("DELETE FROM likes WHERE likeBy = :user_id AND likeOn = :tweet_id");
        $stmt->bindParam(":user_id", $user_id);
        $stmt->bindParam(":tweet_id", $tweet_id);
        $stmt->execute();
    }

    public function likes($user_id, $tweet_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM likes WHERE likeBy = :user_id AND likeOn = :tweet_id ");
        $stmt->bindParam(":user_id", $user_id);
        $stmt->bindParam(":tweet_id", $tweet_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function profileData($user_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE user_id = :user_id");
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function checkRetweet($tweet_id, $user_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM tweets WHERE retweetID = :tweet_id AND retweetBy = :user_id 
         OR tweetID = :tweet_id AND retweetBy = :user_id");
        $stmt->bindParam(":tweet_id", $tweet_id);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function comments($tweet_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM comments 
         LEFT JOIN users ON commentBy = user_id WHERE commentOn = :tweet_id");
        $stmt->bindParam(":tweet_id", $tweet_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }   

    public function countTweets($user_id)
    {
        $stmt = $this->db->prepare("SELECT COUNT(tweetID) AS totalTweets 
        FROM tweets WHERE tweetBy = :user_id AND 
        retweetID = '0' OR `retweetBy` = :user_id ");
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        $count = $stmt->fetch(PDO::FETCH_OBJ);
        echo $count->totalTweets;
    }

    public function countLikes($user_id)
    {
        $stmt = $this->db->prepare("SELECT COUNT(likeID) AS totalLikes FROM likes WHERE likeBy = :user_id ");
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        $count = $stmt->fetch(PDO::FETCH_OBJ);
        echo $count->totalLikes;
    }

  

}

?> 