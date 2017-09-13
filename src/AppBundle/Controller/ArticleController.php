<?php
/**
 * Created by PhpStorm.
 * User: xoka
 * Date: 12.09.17
 * Time: 15:22
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Article;
use AppBundle\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
}