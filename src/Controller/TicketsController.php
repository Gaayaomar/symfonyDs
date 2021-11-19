<?php

namespace App\Controller;

use App\Entity\Ticket;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\DateTime ;
#[Route('/tickets')]
class TicketsController extends AbstractController
{
    #[Route('/list_tous', name: 'tickets_list')]
    public function listtous(): Response
    {
        $tickets = $this->getDoctrine()->getRepository(Ticket::class)->findAll();
        return $this->render('tickets/index.html.twig', ['tickets' => $tickets]);
    }
    /**
     *
     * @Route("/new/{titre}/{nom}/{description}",name="new_ticket")
     */
    public function new($titre,$nom,$description)
    {
        $ticket = new Ticket();
        $ticket->setTitre($titre);
        $ticket->setPersonne($nom);
        $ticket->setDescription($description);
        $ticket->setStatut("en attente");


        $ticket->setDate(new \DateTime());
        $Manager = $this->getDoctrine()->getManager();
        $Manager->persist($ticket);
        $Manager->flush();


        return $this->redirectToRoute('tickets_list');
    }
    /**
     *
     * @Route("/edit/{id}/{titre}/{nom}/{description}", name="ticket_edit")
     */
    public function edit(Request $request,$titre,$nom,$description,$id )
    {

        $ticket = $this->getDoctrine()->getRepository(Ticket::class)->find($id);
        if ($ticket){
            $ticket->setTitre($titre);
            $ticket->setPersonne($nom);
            $ticket->setDescription($description);
            $ticket->setDate(new \DateTime());

            $Manager = $this->getDoctrine()->getManager();
            $Manager->persist($ticket);
            $Manager->flush();

        }

        return $this->redirectToRoute('tickets_list');}

    /**
     *
     * @Route("/delete/{id}", name="ticket_del")
     */
    public function delete(Request $request, $id){
        $ticket = $this->getDoctrine()->getRepository(Ticket::class)->find($id);
        if (!$ticket) {
            return  $this->render('tickets/delete.html.twig', ['id' => $id]);
        }
        $Manager = $this->getDoctrine()->getManager();
        $Manager->remove($ticket);
        $Manager->flush();



        return $this->redirectToRoute('tickets_list');
    }

    #[Route('/list_intervale/{date_min}/{date_max}', name: 'tickets_list_intervalle')]
    public function index($date_min,$date_max): Response
    {

        $datemin=new DateTime($date_min);
        $datemax=new DateTime($date_max);
        $ticketsint = $this->getDoctrine()->getRepository(Ticket::class)->findByDateRange($datemin,$datemax);



        return $this->render('tickets/index2.html.twig', ['tickets' => $ticketsint]);
    }

}
