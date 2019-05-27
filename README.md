# CRUDSymf_AMQP
Hi there,
First of all appologize me for late.
THe API is for create,update, delete and find user per id.
For create you can use the path: 
http://localhost:(RUN_PORT)/users/new
where I build a form for entering: user name, email,password, and gender. then after submitting the form I checked it UsersController.php line#114 with (if($form->isSubmitted() && $form->isValid())) where after that I persist the action to DB using doctrine and flush then send SMS to registered customer using instance of UserRegisters class (which is build as Hexagonal architecture as there are multi buses defined in services.yaml & messenger.yaml.
those services are from CommandBus.php & EventsBus.php classes /locates in src folder/ where SendEmailVerivcationEmail.php get the UserID to path it to SendEmailVerivicationHandler.php which then get instance from swiftMailer and send the message to RabbitQ mail server. Noting that the mail server defined in .env file.
User create form rendered in new.html.twig at template folder
* For update I built the form for update same as create new user but getting already exists user's data (using path /users/update/{id}) and veiw them in  then re-pass them to DB without persist.
User data updated after rendred in view users/update.html.twig at template folder
* For delete I used JavaScript (main,js file in public folder passed it in index.html.twig file)
then used fetch function to get user id from button clicked to delete it from DB using remove properity with confirm notify before delete.

* for gettign all users in index.html.twig I used findAll function from doctrine.
* for getting user per passed id i used find function which takes id as parameter.

===================

for tests:
Postman test passed on path (/users/create) with post request and json data with.

Php unit test:
after installed required packages then build class /src/tests/UnitTest/UsersTest.php
which passes 3 tests (getters,setters,getuser count from fixed passed users, throw exception when if invaled argument after calling run function in UsersController.php, and last test for creating new user using testNewUser function in UserTest.php that calls newAction method from UsersController passed the user data as array.

Test run using php bin/phpunit or php bin/phpunit class_test_name

for Behat test:
I downloaded the symfony2 extension and mink extension for behat.
then creatign feature GetUserPerId in features folder that call FeatureContext.php with parameters same as in 
/**when*/ and /**Senario*/  to do the function from kernel.

**for Docker appologize me since as I explained to you that my CPU doesn't support virtualization.**


================

Note:
If you want to see another implement from amqp, you need to remove the constructor from Users entity and all the calls from 
UsersController.php that pass array when call (new Users([])) then you can path at line 125 in UsersController.php class a new instance from /src/Message/Event/SmsNotification.php
passing $user then this class calls the srs/MessageHandler/SmsNotificationHandler.php class than has invoke method (defines a host,port,vhost,password,queue,
connection,exchange and channel) which build a queue and open connection to user/password with host as account on rabbitMQ
then pass the Message.
Also if you need to see message example only in serial case you can un-comment code in srs/MessageHandler/SmsNotificationHandler.php from line# 87 to the end, as it uses mailtro mailer to send messages, defined in .env file 
at line 38

===================

I wish you find all well.
Waiting your kind reply.

Thank you for your time and effort.

