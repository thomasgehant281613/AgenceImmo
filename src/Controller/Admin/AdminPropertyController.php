<?php

namespace App\Controller\Admin;

use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface as EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RedirectResponse as RedirectResponseAlias;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfToken;

class AdminPropertyController extends AbstractController
{


    /**
     * @var PropertyRepository
     */
    private $repository;
    /**
     * @var EntityManager
     */
    private $em;


    public function __construct(PropertyRepository $repository, EntityManagerInterface $em )
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/admin/property", name="admin_property.index")
     */

    public function index()
    {
        $properties = $this->repository->findAll();
        return $this->render('admin_property/index.html.twig', compact('properties'));
    }

    /**
     * @Route("/admin/property/create", name="admin_property.new")
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws ORMException
     * @throws OptimisticLockException
     */


    public function new(Request $request)
    {
        $property = new Property();
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $this->em->persist($property);
            $this->em->flush();
            $this->addFlash('success','Le bien a été créé avec succès');
            return $this->redirectToRoute('admin_property.new');

        }
        return $this->render('admin_property/new.html.twig', [
            'property' => $property,
            'form' => $form->createView()

        ]);
    }

    /**
     * @Route("/admin/property/{id}", name="admin_property.edit", methods="GET|POST")
     * @param Property $property
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws ORMException
     * @throws OptimisticLockException
     */

    public function edit(Property $property, Request $request)
    {
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $this->em->flush();
            $this->addFlash('success','Le bien a été modifié avec succès');
            return $this->redirectToRoute('admin_property.index');

        }
        return $this->render('admin_property/edit.html.twig', [
            'property' => $property,
            'form' => $form->createView()

        ]);
    }

    /**
     * @Route("/admin/property/{id}", name="admin_property.delete", methods="DELETE")
     * @param Property $property
     * @param Request $request
     * @return RedirectResponseAlias
     * @throws ORMException
     * @throws OptimisticLockException
     */

    public function delete(Property $property)
    {
            $this->em->remove($property);
            $this->em->flush();
            $this->addFlash('success', 'Le bien a été supprimé avec succès');

        return $this->redirectToRoute('admin_property.index');
    }
}
