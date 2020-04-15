<?php

namespace App\Controller;

use App\Entity\Property;
use App\Entity\Repository;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @Route("/property", name="property")
     */
    public function index()
    {
        return $this->render('property/index.html.twig', [
            'controller_name' => 'PropertyController',
        ]);
    }

    /**
     * @Route("/property/{slug}-{id}", name="property.show", requirements={"slug" : "[a-z0-9\-]*"})
     * @param Property $property
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
