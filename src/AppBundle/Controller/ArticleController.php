<?php
/**
 * Created by PhpStorm.
 * User: xoka
 * Date: 12.09.17
 * Time: 15:22
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Article;
use AppBundle\Entity\ArticleDestroyForm;
use AppBundle\Entity\Category;
use AppBundle\Repository\ArticleRepository;
use AppBundle\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ArticleController extends Controller
{

    /**
     * @Route("/articles", name="articles.all")
     */
    public function indexAction()
    {
        /** @var ArticleRepository $repository */
        $repository = $this->getDoctrine()->getRepository(Article::class);

        return $this->render('article/index.html.twig', ['articles' => $repository->getLatestArticles(5)]);
    }

    /**
     * @Route("/articles/{id}", name="articles.show", requirements={"id": "\d+"})
     */
    public function showAction($id)
    {
        $repository = $this->getDoctrine()->getRepository(Article::class);
        $article = $repository->find($id);

        if (empty($article)) {
            throw new NotFoundHttpException('article not found');
        }

        return $this->render('article/show.html.twig', ['article' => $article]);
    }

    /**
     * @Route("/articles/search", name="articles.search")
     */
    public function searchAction(Request $request)
    {
        /** @var ArticleRepository $repository */
        $repository = $this->getDoctrine()->getRepository(Article::class);

        $articles = $repository->getSearchResult(trim($request->get('query', ''), '%'));

        $response = new Response(json_encode(['data' => $articles ?? []]));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/articles/weekly", name="articles.weekly")
     */
    public function weeklyAction()
    {
        /** @var ArticleRepository $repository */
        $repository = $this->getDoctrine()->getRepository(Article::class);

        return $this->render('article/weekly.html.twig', ['weekly' => $repository->getWeekly()]);
    }

    /**
     * @Route("/articles/destroy", name="articles.destroy")
     */
    public function destroyAction(Request $request)
    {
        /** @var CategoryRepository $categoryRepository */
        $categoryRepository = $this->getDoctrine()->getRepository(Category::class);
        /** @var ArticleRepository $articleRepository */
        $articleRepository = $this->getDoctrine()->getRepository(Article::class);

        $model = new ArticleDestroyForm();

        $form = $this->createFormBuilder($model)
            ->add('category', ChoiceType::class,
                [
                    'choices' => $categoryRepository->getAll(),
                ])
            ->add('date', DateType::class)
            ->add('save', SubmitType::class, ['label' => 'Списать'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $articleRepository->destroy($task->getCategory(), $task->getDate());
            return $this->redirectToRoute('articles.weekly');
        }

        return $this->render('article/destroy.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}