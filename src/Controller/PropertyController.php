<?php


namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Property;

use App\Entity\PropertySearch;
use App\Form\ContactType;
use App\Form\ContactTypeType;
use App\Form\PropertySearchType;
use App\Notification\ContactNotification;
use App\Repository\PropertyRepository;
use Knp\Component\Pager\PaginatorInterface;
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
     * @param Request $request
     * @param ContactNotification $notification
     * @param string $slug
     * @return Response
     */

    public function show(Property $property, Request $request, ContactNotification $notification, string $slug)
    {
        if ($property->getSlug() !== $slug ){
            return $this->redirectToRoute('property.show',
                [
                    'id' => $property->getId(),
                    'slug'=> $property->getSlug()
                ], 301);
        }


        $contact = new Contact();
        $contact->setProperty($property);
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $notification->notify($contact);
            $this->addFlash('succes','Votre email a bien été envoyé');
            return $this->redirectToRoute('property.show',
                [
                    'id' => $property->getId(),
                    'slug'=> $property->getSlug()
                ]);
        }

        return $this->render('property/show.html.twig', [
            'property' => $property,
            'controller_name' => 'PropertyController',
            'form' => $form->createView()
        ]);
    }
}