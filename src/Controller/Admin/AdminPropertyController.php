<?php
/**
 * Created by PhpStorm.
 * User: dali
 * Date: 13/12/18
 * Time: 09:54
 */
namespace App\Controller\Admin;
use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPropertyController extends AbstractController
{
    /**
     * @var PropertyRepository
     */
    private $repository;
    /**
     * @var ObjectManager
     */
    private $em;


    /**
     * AdminPropertyController constructor.
     * @param PropertyRepository $repository
     * @param ObjectManager $em
     */
    public function __construct(PropertyRepository $repository, ObjectManager $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/admin", name="admin.property.index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(){
        $properties = $this -> repository ->findAll();

        //compact Crée un tableau à partir la variable properties et de sa valeur.

        return $this -> render('admin/property/index.html.twig', compact('properties'));
    }

    /**
     * @Route("/admin/property/create", name="admin.property.create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    public function createProperty(Request $request){
        $property = new Property();
        $form = $this -> createForm(PropertyType::class, $property);
        $form -> handleRequest($request);

        if ($form -> isSubmitted() && $form -> isValid()) {
            $this -> em -> persist($property);
            $this -> em -> flush();
            $this -> addFlash('success', 'Biens créer avec succès');
            return $this -> redirectToRoute('admin.property.index');
        }
        return $this -> render('admin/property/create.html.twig', [
            'property' => $property,
            'form' => $form -> createView()
        ]);
    }

    /**
     * @Route("/admin/property/{id}", name="admin.property.edit", methods="GET|POST")
     * @param Property $property
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Property $property, Request $request){
        $form = $this -> createForm(PropertyType::class, $property);
        $form -> handleRequest($request);

        if ($form -> isSubmitted() && $form -> isValid()) {
                $this -> em -> flush();
                $this -> addFlash('success', 'Biens modifier avec succès');
                return $this -> redirectToRoute('admin.property.index');
        }


       return $this -> render('admin/property/edit.html.twig', [
           'property' => $property,
           'form' => $form -> createView()
       ]);
    }

    /**
     * @Route("/admin/property/{id}", name="admin.property.delete", methods="DELETE")
     * @param Property $property
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Property $property, Request $request){
        if ($this -> isCsrfTokenValid('delete' . $property->getId(), $request->get('_token'))){
            $this -> em -> remove($property);
            $this -> em -> flush();
            $this -> addFlash('success', 'Biens supprimer avec succès');
        }
        return $this -> redirectToRoute('admin.property.index');
    }

}