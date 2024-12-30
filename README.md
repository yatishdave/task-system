php version >= 8.2 mysql :8.0 apache : 2.4
develop in symfony 7.3 framework on ubnatu
 
clone repo using below commnad : git clone https://github.com/yatishdave/task-system.git

import database from poroject setup-data
prepare virtual host copy file from setup-data and add entry in hosts file (/etc/hosts) like this `127.0.0.1      task-system.local` 
enable virtual host file using a2ensite command 
restart apache

You can run this project using symfony CLI also please refer below page
https://symfony.com/download  
https://symfony.com/doc/current/setup.html 
need to install symfony cli first and then run below command from project root folder 
symfony server:start

copy .env.dist to .env file and update DATABASE_URL param
go to console/terminal run below command 
compsoer install

this project use jwt token so run below command to generate keys 
php bin/console lexik:jwt:generate-keypair
 
php bin/console c:c

below are page url  
http://task-system.local/login 
http://task-system.local/logout
http://task-system.local/task


for api login 

1. curl -X POST -H "Content-Type: application/json" http://127.0.0.1:8000/api/login_check -d '{"username":"test3","password":"1234567"}'

2.curl -X 'GET' \
  'http://127.0.0.1:8000/api/task' \
  -H 'accept: application/json' \
  -H 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE3MzUzNzY0MzMsImV4cCI6MTc2NjkxMjQzMywicm9sZXMiOlsiUk9MRV9VU0VSIl0sInVzZXJuYW1lIjoidGVzdDMifQ.bo3hVL8ZGvLMQ-rnYxdotAXzsZ3pgqznIpZoQIJFVHP3CkSIWPHzBQ7tYkiUnNoIQDWDekJy4RdsVR7JcRFyAUdmcyTfei8vklxJsXouGXNBKJk_UP2ox_XIEuqudtexIhIMLO8QRorfsMTTJeiSpXSaQKjidKlAni7dAVV_TU0rne2iQ9i-ENwuDAJHSO_8HD348Ftt8VAkTtvgJq_aXV_Mi1hwSmqs5Jj5kf9KrYVHFhcm55vlEChNYi88BmoT0HwUfHEL3vxMpCwIwnSI00u30nDSsmFEpsW4vmXciKW8qm42nxSdhapLVr6I92LmiOKCYZz8yFFt_C6PO1gBAQ'
  
{
    "code": 200,
    "data": [
        {
            "id": 28,
            "title": "test1111",
            "taskDescription": "retere",
            "dueDate": "2025-12-29T00:00:00+00:00",
            "taskStatus": "s1",
            "taskCategory": "test"
        },
        {
            "id": 27,
            "title": "test fff",
            "taskDescription": "fdsfdsf",
            "dueDate": "2025-01-03T00:00:00+00:00",
            "taskStatus": "s1",
            "taskCategory": "test"
        }
    ],
    "message": "Task get successfully."
}  
  
3.curl -X 'POST' \
  'http://127.0.0.1:8000/api/task/create' \
  -d '{"title":"test1111","taskDescription":"retere","dueDate":"2025-12-29","status":"1","category":"1"}' \
  -H 'accept: application/json' \
  -H 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE3MzUzNzY0MzMsImV4cCI6MTc2NjkxMjQzMywicm9sZXMiOlsiUk9MRV9VU0VSIl0sInVzZXJuYW1lIjoidGVzdDMifQ.bo3hVL8ZGvLMQ-rnYxdotAXzsZ3pgqznIpZoQIJFVHP3CkSIWPHzBQ7tYkiUnNoIQDWDekJy4RdsVR7JcRFyAUdmcyTfei8vklxJsXouGXNBKJk_UP2ox_XIEuqudtexIhIMLO8QRorfsMTTJeiSpXSaQKjidKlAni7dAVV_TU0rne2iQ9i-ENwuDAJHSO_8HD348Ftt8VAkTtvgJq_aXV_Mi1hwSmqs5Jj5kf9KrYVHFhcm55vlEChNYi88BmoT0HwUfHEL3vxMpCwIwnSI00u30nDSsmFEpsW4vmXciKW8qm42nxSdhapLVr6I92LmiOKCYZz8yFFt_C6PO1gBAQ'
  
  {
      "code": 200,
      "message": "Task added successfully."
  }
  
4.curl -X 'POST' \
  'http://127.0.0.1:8000/api/task/edit' \
  -d '{"title":"test1111","taskDescription":"retere","dueDate":"2025-12-29","status":"1","category":"1","id":27}' \
  -H 'accept: application/json' \
  -H 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE3MzUzNzY0MzMsImV4cCI6MTc2NjkxMjQzMywicm9sZXMiOlsiUk9MRV9VU0VSIl0sInVzZXJuYW1lIjoidGVzdDMifQ.bo3hVL8ZGvLMQ-rnYxdotAXzsZ3pgqznIpZoQIJFVHP3CkSIWPHzBQ7tYkiUnNoIQDWDekJy4RdsVR7JcRFyAUdmcyTfei8vklxJsXouGXNBKJk_UP2ox_XIEuqudtexIhIMLO8QRorfsMTTJeiSpXSaQKjidKlAni7dAVV_TU0rne2iQ9i-ENwuDAJHSO_8HD348Ftt8VAkTtvgJq_aXV_Mi1hwSmqs5Jj5kf9KrYVHFhcm55vlEChNYi88BmoT0HwUfHEL3vxMpCwIwnSI00u30nDSsmFEpsW4vmXciKW8qm42nxSdhapLVr6I92LmiOKCYZz8yFFt_C6PO1gBAQ'
  
  {
      "code": 200,
      "message": "Task updated successfully."
  }

5.curl -X 'POST' \
  'http://127.0.0.1:8000/api/task/delete' \
  -d '{"id":27}' \
  -H 'accept: application/json' \
  -H 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE3MzUzNzY0MzMsImV4cCI6MTc2NjkxMjQzMywicm9sZXMiOlsiUk9MRV9VU0VSIl0sInVzZXJuYW1lIjoidGVzdDMifQ.bo3hVL8ZGvLMQ-rnYxdotAXzsZ3pgqznIpZoQIJFVHP3CkSIWPHzBQ7tYkiUnNoIQDWDekJy4RdsVR7JcRFyAUdmcyTfei8vklxJsXouGXNBKJk_UP2ox_XIEuqudtexIhIMLO8QRorfsMTTJeiSpXSaQKjidKlAni7dAVV_TU0rne2iQ9i-ENwuDAJHSO_8HD348Ftt8VAkTtvgJq_aXV_Mi1hwSmqs5Jj5kf9KrYVHFhcm55vlEChNYi88BmoT0HwUfHEL3vxMpCwIwnSI00u30nDSsmFEpsW4vmXciKW8qm42nxSdhapLVr6I92LmiOKCYZz8yFFt_C6PO1gBAQ'
  
  {
      "code": 200,
      "message": "Task delete successfully."
  }





