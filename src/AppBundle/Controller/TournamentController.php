<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TournamentController extends Controller
{
    /**
     * @Route("/tournament/list", name="site.tournament.list")
     */
    public function tournamentListAction(){
        return $this->render(':app/tournament:list.html.twig');
    }
}