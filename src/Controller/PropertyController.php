<?php


namespace App\Controller;

use App\Entity\Property;

use App\Entity\PropertySearch;
use App\Form\PropertySearchType;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ObjectManager;

use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;



class PropertyController extends AbstractController
{
    /**
     * @var PropertyRepository
     */
    private $repository;
    /**
     * @var EntityManagerInterface
     */
    private $em;


    public function __construct(PropertyRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/property", name="property.index")
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(PaginatorInterface $paginator, Request $request):Response
    {
        $search = new PropertySearch();
        $form = $this->createForm(PropertySearchType::class, $search);
        $form ->handleRequest($request);

        $properties =  $paginator->paginate(
            $this->repository->findAllVisibleQuery($search),
            $request->query->getInt('page', 1),
            12
        );
        return $this->render('property/index.html.twig', [
            'controller_name' => 'PropertyController',
            'properties'      => $properties,
            'form'            => $form->createView()
        ]);
    }

    /**
     * @Route("/property/{slug}-{id}", name="property.show", requirements={"slug" : "[a-z0-9\-]*"})
     * @param Property $property
     * @param string $slug
     * @return Response
     */

    public function show(Property $property, string $slug)
    {
        if ($property->getSlug() !== $slug ){
            return $this->redirectToRoute('property.show',
                [
                    'id' => $property->getId(),
                    'slug'=> $property->getSlug()
                ], 301);
        }
        return $this->render('property/show.html.twig', [
            'property' => $property,
            'controller_name' => 'PropertyController',
        ]);
    }
}
