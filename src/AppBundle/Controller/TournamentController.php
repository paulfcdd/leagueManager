<?php

namespace AppBundle\Controller;

use AppBundle\Entity\League;
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
use Symfony\Component\Form\{
    FormInterface, Form
};

/**
 * Class TournamentController
 * @package AppBundle\Controller
 * @Route("/tournament")
 */
class TournamentController extends Controller
{
    /**
     * @param Request $request
     * @Route("", name="site.tournament")
     * @return Response
     */
    public function tournamentListAction(Request $request){

        $em = $this->getDoctrine()->getManager();

        $tournament = new Tournament();

        $form = $this
            ->buildTournamentForm($tournament)
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
     * @Route("/remove/{tournament}", name="site.tournament.remove")
     * @Method("POST")
     * @return JsonResponse
     */
    public function removeTournamentAction(Tournament $tournament){

        return $this
            ->get('app.crudbale')
            ->setData($tournament)
            ->delete();
    }

    /**
     * @param Tournament $tournament
     * @return Response
     * @Route("/details/{tournament}", name="site.tournament.details")
     */
    public function tournamentInfo(Tournament $tournament){

        $em = $this->getDoctrine()->getManager();

        $leagues = $em->getRepository(League::class)->findBy(
            ['tournament' => $tournament->getId()],
            ['ranking' => 'ASC']);

        return $this->render(':app/tournament:details.html.twig', [
            'tournament' => $tournament,
            'leagues' => $leagues
        ]);

    }

    /**
     * @param Tournament $tournament
     * @return Form|FormInterface
     */
    private function buildTournamentForm(Tournament $tournament) {

        return $this->createFormBuilder($tournament)
            ->setAction($this->generateUrl('site.tournament', [
                'tournament' => $tournament->getId(),
            ]))
            ->add('name', CollectionType::class,[
                'label' => false,
                'data' => $this->getDoctrine()->getRepository(Tournament::class)->findAll(),
                'entry_type' => TournamentType::class,
                'allow_add' => true,
            ])
            ->getForm();

    }
}