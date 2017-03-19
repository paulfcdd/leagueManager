<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Tournament;
use AppBundle\Form\TournamentType;
use Doctrine\DBAL\DBALException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TournamentController extends Controller
{
    /**
     * @param Request $request
     * @Route("/tournament", name="site.tournament")
     * @return Response
     */
    public function tournamentListAction(Request $request){

        $em = $this->getDoctrine()->getManager();

        $tournament = new Tournament();

        $form = $this->createFormBuilder($tournament)
            ->setAction($this->generateUrl('site.tournament', [
                'tournament' => $tournament->getId(),
            ]))
            ->add('name', CollectionType::class,[
                'label' => false,
                'data' => $em->getRepository(Tournament::class)->findAll(),
                'entry_type' => TournamentType::class,
                'allow_add' => true,
            ])
            ->getForm()
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($form->getData()->getName() as $item) {
                $em->persist($item);
            }

            try {
                $em->flush();
                return $this->redirectToRoute('site.tournament');
            } catch (DBALException $exception) {
                return Response::create($exception->getMessage());
            }
        }

        return $this->render(':app:tournament.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Tournament $tournament
     * @param Request $request
     * @Route("/tournament/remove/{tournament}", name="site.tournament.remove")
     * @Method("POST")
     * @return JsonResponse
     */
    public function removeTournamentAction(Tournament $tournament, Request $request){
        $em = $this->getDoctrine()->getManager();

        $em->remove($tournament);
        $em->flush();

        return JsonResponse::create();
    }

    /**
     * @Route("/tournament/details/{tournament}", name="site.tournament.details")
     */
    public function tournamentInfo(Tournament $tournament){

        return $this->render(':app/tournament:details.html.twig', [
            'tournament' => $tournament
        ]);

    }
}