<?php


namespace App\Controller;

use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Episode;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/programs", name="program_")
 */
class ProgramController extends AbstractController
{
    /**
     * Show all rows from Program's entity
     *
     * @Route("/", name="index")
     * @return Response A response instance
     */
    public function index(): Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();

        return $this->render('program/index.html.twig',
            ['programs' => $programs]
        );
    }

    /**
     * Getting a program by id
     *
     *@Route("/show/{id<^[0-9]+$>}", requirements={"id" = "\d+"}, methods={"GET"}, name="show")
     *@return Response
     */

    public function show(int $id): Response
    {
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['id' => $id]);

        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : '.$id.' found in program\'s table.'
            );
        }

        $seasons = $this->getDoctrine() //au plusiel car je boucle sur toutes les saisons
            ->getRepository(Season::class)
            ->findBy(['program' => $program]);

        return $this->render('program/show.html.twig', [
            'program' => $program,
            'seasons' => $seasons

        ]);
    }

    /**
     * @Route("/{programId}/seasons/{seasonId}", methods={"GET"}, name="season_show")
     * @param int $programId
     * @param int $seasonId
     * @return Response
    */
    public function showSeason(int $programId, int $seasonId)
    {
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['id' => $programId]);
        $season = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findOneBy(['id' => $seasonId]);
        $episodes = $this->getDoctrine()
            ->getRepository(Episode::class)
            ->findOneBy(['season' => $seasonId]);
        if (!$program) {
            throw $this->createNotFoundException(
                'Aucune série avec l\'id: ' . $programId . ' n\'a été trouvé.'
            );
        }
        if (!$seasonId) {
            throw $this->createNotFoundException(
                'Aucune saison avec l\'id: ' . $seasonId . ' n\'a été trouvé.'
            );
        }
        if (!$episodes) {
            throw $this->createNotFoundException(
                'Aucun épisode n\'a été trouvé'
            );
        }

        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episodes' => $episodes // pas obligatoire car j'y accède par season
        ]);
    }
}