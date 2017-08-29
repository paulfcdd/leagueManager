<?php

namespace AppBundle\Controller;

use AppBundle\Entity\League;
use AppBundle\Entity\Tournament;
use AppBundle\Form\LeagueType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class LeagueController
 * @package AppBundle\Controller
 * @Route("/league")
 */
class LeagueController extends Controller
{
    /**
     * @param League|null $league
     * @param Request $request
     * @param string $tournament
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/manage/tournament={tournament}/league={league}", name="league.manage")
     */
    public function manageLeagueAction(League $league = null, Request $request, string $tournament) {

        $em = $this->getDoctrine()->getManager();

        $tournament = $em->getRepository(Tournament::class)->findOneByName($tournament);

        $form = $this->createForm(LeagueType::class, $league)
            ->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            /** @var League $league */
            $league = $form->getData();

            $league->setTournament($tournament);

            $em->persist($league);

            $em->flush();

        }

        return $this->render(':app/league:manage.html.twig', [
            'tournament' => $tournament,
            'form' => $form->createView(),
            'league' => $league,
        ]);
    }
}