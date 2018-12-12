<?php
/**
 * Created by PhpStorm.
 * User: dali
 * Date: 12/12/18
 * Time: 10:26
 */
namespace App\Controller;
use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class HomeController extends AbstractController // Objet qui contient tout les services
{
    // Environment = autowiring = service préchargé avec l'alias twig
    # /**
    # * @var Environment
    # */
    # private $twig;

    #/**
    # * HomeController constructor.
    # */
    #public function __construct( Environment $twig)
    #{
    #   $this->twig = $twig;
    #}



    //Response contient toutes les informations qui doivent être renvoyées au client à partir d'une     demande donnée. Le constructeur prend jusqu'à trois arguments : le contenu de la réponse, le      code d'état et un tableau d'en-têtes HTTP

    /**
     * @Route("/", name="home")
     * @param PropertyRepository $repository
     * @return Response
     */
    public function index(PropertyRepository $repository) :Response {
    //return new Response($this -> twig -> render('pages/home.html.twig'));
        $properties = $repository->findLatest();
    return $this -> render('pages/home.html.twig', [
        'properties' => $properties
    ]);
}

}