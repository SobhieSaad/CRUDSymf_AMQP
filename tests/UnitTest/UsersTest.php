<?php


namespace App\Tests;


use PHPUnit\Framework\TestCase;
use App\Controller\UsersController;
use App\Entity\Users;


use Prophecy\Exception\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\Routing;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class UsersTest extends WebTestCase
{
    /*public function testSetGetUserName()
     {
         $user=new Users();
         $user->setUsrName("Bill");
         $this->assertEquals($user->getUsrName(),'Bill');
     }
*/

    public function testGetUsersCount()
    {
        $controller = new Users(['user 1', 'user2']);

        $this->assertEquals(2, $controller->count());

    }


    /**
     * @expectedException InvalidArgumentException
     */
    public function testThrowExceptionIfNoAssert()
    {

        $controller=new UsersController();
        $controller->run();
    }


    public function testNewUser()
    {
        $client = new UsersController();

        $UserName = 'ObjectOrienter'.rand(0, 999);
        $data = array(
            $UserName,
            'test@example.com',
            'JustTest',
            'Female'
        );

        $request = $client->newAction( $data);
        $response = $request->send();
        $this->assertEquals(201,$response->getStatusCode());
    }

   /* public function testShowPost()
    {
        $client = static::createClient();

        $client->request('GET', '/users/60');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }*/

   /* public function testDoStuffIsTrue()
    {
        $request = $this->getMock("Symfony\Component\HttpFoundation\Request");
        $container = $this->getMock("Symfony\Component\DependencyInjection\ContainerInterface");
        $service = $this->getMockBuilder("Some\Stuff")->disableOriginalConstructor()->getMock();
        $container->expects($this->once())
            ->method("getParameter")
            ->with($this->equalTo('do_stuff'))
            ->will($this->returnValue(true));

        $container->expects($this->once())
            ->method("get")
            ->with($this->equalTo('stuff.service'))
            ->will($this->returnValue($service));

        $controller = new UsersController();
        $controller->setContainer($container);

        $controller->goAction($request);

    }
*/

    /*public function testValidationError()
    {
        $client=new UsersController();

        $data=array(

            'Email'=>'test@example.com',
            'Password'=>'test',
            'Gender'=>'Male'

        );
        $response=$this->client->post('/users/create',[
            'body'=>json_encode($data)

        ]);
        $this->assertEquals(400,$response->getStatusCode());
        $this->asserter()->assertResonsePropertyExists($response ,array(
            'type','title','errors,'
        ));

        $this->asserter()->assertResonsePropertyExists($response,
            'errors.usrName'

        );
        $this->asserter()->assertResponsePropertyEquals(
            $response,
            'errors.usrName[0]','Please enter user name'
        )  ;
    }*/





}