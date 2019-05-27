<?php
    namespace App\Controller;
    use App\Entity\Users;
    use App\EventsBus;
    use App\Message\Event\UserRegistered;
    use http\Exception\InvalidArgumentException;
    use Psr\Log\LoggerInterface;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Annotation\Route;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\Extension\Core\Type\SubmitType;
    use Symfony\Component\Messenger\MessageBusInterface;
    use App\Message\SmsNotification;
    use Symfony\Component\Messenger\Envelope;
    use Symfony\Component\Messenger\Stamp\SerializerStamp;
    use Symfony\Component\Messenger\Handler\HandlersLocator;
    use Symfony\Component\Messenger\MessageBus;
    use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;
    class UsersController extends AbstractController{



        /**
         * @Route("/",name="All_users")
         * @Method({"GET"})
         */
        public function index(){

         $users=$this->getDoctrine()->getRepository(Users::class)
         ->findAll();


         return $this->render('users/index.html.twig',array('users'=>$users));
        }

          /**
         * @Route("/users/create")
         */
        public function create(){
            $entityManager=$this->getDoctrine()->getManager();
            $user=new Users([]);
            $user->setUsrName('User 1');
            $user->setEmail('Email1@gmail.com');
            $user->setPassword('Password1');
            $user->setGender('Male');
            $entityManager->persist($user);
            $entityManager->flush();
           // dump($user);
           // $response = new Response(json_encode($user));
            //$response->headers->set('Content-Type', 'application/json');
           // return new Response('Saved in users with ID of ' .  $user->getId());

            //encoding json response
            $note=[
                ['Id'=>$user->getId(),],
                ['UserName'=>$user->getUsrName(),],
                ['Email'=>$user->getEmail(),],
                ['Password'=>$user->getPassword(),],
                ['Gender'=>$user->getGender(),]
            ];
            $data=['note'=>$note,];
            return new JsonResponse($data);

        }

         /**
         * @Route("/users/delete/{id}")
         * @Method({"DELETE"})
         */
        public function DELETE(Request $request,$id,LoggerInterface $logger)
        {
            $user = $this->search($id);
            $entityManager=$this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
            //dump($request);
            $logger->info($user->getUsrName() . " has been deleted");
            $response=new Response();
            $response->send();
        }
        
        /**
         * @Route("/users/new",name="new_user")
         * Method({"Get","POST"})
         */
        public function new(Request $request,MessageBusInterface $bus ,LoggerInterface $logger,EventsBus $Eventsbus)
        {
            $user=new Users([]);
            $form=$this->createFormBuilder($user)
            ->add('usrName',TextType::class,array(
                'attr'=> array('class'=> 'form-control'))
               
                )
            ->add('Email',TextType::class,array(
             'required'=>false,
             'attr'=> array('class'=> 'form-control')    
            ))
            ->add('Password',TextType::class,array(
                'attr'=> array('class'=> 'form-control')))
            ->add('Gender',TextType::class,array(
                'attr'=> array('class'=> 'form-control')))
            ->add('Save',SubmitType::class,array(
                'label'=>'Create',
                'attr'=>array('calss'=>'btn btn-primary mt-3 form-control')
            ))
            ->getForm();
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid())
            {
                $user=$form->getData();
                $entityManager=$this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                $logger->info($user->getUsrName() . " has been created and
                verify code is sent");
                //$bus->dispatch(new UserRegistered($user->getId()));
               
                $bus->dispatch(new UserRegistered($user->getId()));

                return $this->redirectToRoute('All_users');

            }
            return $this->render('users/new.html.twig',array(
                'form'=>$form->createView()
            ));
        }

     /**
         * @Route("/users/update/{id}",name="update_user")
         * Method({"PUT"})
         */
        public function update(Request $request,$id, LoggerInterface $logger)
        {
            $user=new Users([]);
            $user = $this->search($id);
            $form=$this->createFormBuilder($user)
            ->add('usrName',TextType::class,array(
                'attr'=> array('class'=> 'form-control'))
               
                )
            ->add('Email',TextType::class,array(
             'required'=>false,
             'attr'=> array('class'=> 'form-control')    
            ))
            ->add('Password',TextType::class,array(
                'attr'=> array('class'=> 'form-control')))
            ->add('Gender',TextType::class,array(
                'attr'=> array('class'=> 'form-control')))
            ->add('Save',SubmitType::class,array(
                'label'=>'Update',
                'attr'=>array('calss'=>'btn btn-primary mt-3')
            ))
            ->getForm();
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid())
            {
                $user=$form->getData();
                $entityManager=$this->getDoctrine()->getManager();
                $entityManager->flush();
                $logger->info($user->getUsrName() . " with user ID#" . $user->getId()
                . " has been updated successfully");
                return $this->redirectToRoute('All_users');
            }
            return $this->render('users/update.html.twig',array(
                'form'=>$form->createView()
            ));
        }

        /**
        * @Route("/users/{id}",name="User_Details")
        */
        public function ShowUser($id,LoggerInterface $logger)
        {
            $user=new Users([]);
            $user=$this->search($id);

            $logger->info($user->getUsrName() . " with user ID#" . $user->getId()
                . " was requested");

            return $this->render('users/show.html.twig', array(
                'user'=>$user
            ));
        }


        public function search(int $id)
        {
             return $this->getDoctrine()->getRepository(Users::class)->find($id);

        }

        public function run($user=null)
        {
            if(!$user)
            {
                throw new \Prophecy\Exception\InvalidArgumentException('please pass an asset');
            }
        }

        protected $userData=[];
        /**
         * @return Response
         * @Route("/users/newUser")
         * Method({"POST"})
         */
        public function newAction(array $data)
        {

           // $data=json_decode( $request->getContent(),true);
            $this->userData=$data;
            $user=new Users([]);
            //$user=new Users();
            $user->setUsrName($data[0]);
            $user->setEmail($data[1]);
            $user->setPassword($data[2]);
            $user->setGender($data[3]);

            $response=new Response('It\'s worked!',201) ;
           // $response->headers->set('Location','/users/notFake');
            return $response;
        }



      
    }
