php version >= 8.2 mysql :8.0 apache : 2.4
develop in symfony 7.3 framework on ubnatu
 
clone repo using below commnad : git clone https://github.com/yatishdave/task-system.git

import database from poroject setup-data
prepare virtual host copy file from setup-data and add entry in hosts file (/etc/hosts) like this `127.0.0.1      task-system.local` 
enable virtual host file using a2ensite command 
restart apache 
copy .env.dist to .env file and update DATABASE_URL param
go to console/terminal run below command 
compsoer install 
php bin/console c:c

to acces page 
http://task-system.local/login 
http://task-system.local/logout
http://task-system.local/task
