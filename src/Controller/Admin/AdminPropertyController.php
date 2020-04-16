<?php

namespace App\Controller\Admin;

use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPropertyController extends AbstractController
{


    /**
     * @var PropertyRepository
     */
    private $repository;

    public function __construct(PropertyRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/admin", name="admin_property")
     */

    public function index()
    {
        $properties = $this->repository->findAll();
        return $this->render('admin_property/index.html.twig', compact('properties'));
    }

    /**
     * @Route("/admin/{id}", name="admin_property.edit")
     * @param Property $property
     * @return Response
     */

    public function edit(Property $property)
    {
        $form = $this->createForm(PropertyType::class, $property);
        return $this->render('admin_property/edit.html.twig', [
            'property' => $property,
            'form' => $form->createView()

        ]);
    }
}
