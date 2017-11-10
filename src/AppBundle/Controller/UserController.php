<?php
/**
 * Created by PhpStorm.
 * User: xoka
 * Date: 13.10.2017
 * Time: 19:08
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use AppBundle\Entity\UserAddRoleForm;
use AppBundle\Entity\UserRole;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserController extends Controller
{
    /**
     * @Route("/users", name="user.all")
     */
    public function actionIndex()
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        return $this->render('user/index.html.twig', ['users' => $repository->getAll()]);
    }

    /**
     * @Route("/users/{id}", name="user.show", requirements={"id": "\d+"})
     */
    public function show($id, Request $request)
    {
        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $roleRepository = $this->getDoctrine()->getRepository(Role::class);
        $userRoleRepository = $this->getDoctrine()->getRepository(UserRole::class);
        $user = $userRepository->find($id);
        if (empty($user)) {
            throw new NotFoundHttpException('user not found');
        }
        $formModel = new UserAddRoleForm();

        $form = $this->createFormBuilder($formModel)
            ->add('roleId', ChoiceType::class,
                [
                    'choices' => $roleRepository->getAll(),
                ])
            ->add('date', DateType::class)
            ->add('save', SubmitType::class, ['label' => 'Сохранить'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $userRoleRepository->add($user, $roleRepository->getById($task->getRoleId()), $task->getDate());
            return $this->redirectToRoute('user.all');
        }
        return $this->render('user/show.html.twig', ['user' => $user, 'form' => $form->createView()]);
    }
}