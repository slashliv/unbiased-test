<?php

namespace App\Controller;

use App\Entity\Enquiry;
use App\Form\Type\EnquiryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class EnquiryController extends AbstractController
{
    public function createAction(Request $request, EntityManagerInterface $entityManager)
    {
        $enquiry = new Enquiry();
        $form = $this->createForm(EnquiryType::class, $enquiry);
        $contentData = json_decode($request->getContent(), true);
        $form->submit($contentData);

        if (! $form->isValid()) {
            $errors = [];
            foreach ($form->getErrors(true) as $formError) {
                $errors[] = [
                    'property' => $formError->getCause()->getPropertyPath(),
                    'message' => $formError->getMessage()
                ];
            }

            return $this->json([
                'success' => false,
                'message' => 'Enquiry is not valid',
                'errors' => $errors,
            ], 200);
        }

        $entityManager->persist($enquiry);
        $entityManager->flush();

        return $this->json([
            'success' => true,
            'id' => $enquiry->getId(),
        ]);
    }
}
