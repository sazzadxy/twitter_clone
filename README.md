# twitter_clone
making twitter like social media website using php-mysql-ajax

dummy users:
bigbro@mail.com/duke@mail.com/sharp@mail.com-987654, maria00@mail.com/claudia@mail.com-123456


 //run mysql command line if Fatal error: Uncaught PDOException: SQLSTATE[42000]: 
 //Syntax error or access violation: 1055 Expression #4 of SELECT list is not in GROUP BY clause and contains nonaggregated column occures: 
 
 SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY','')); 
