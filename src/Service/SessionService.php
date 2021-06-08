<?php 
// src/Service/SessionService.php
namespace App\Service;

use App\Entity\Session;
use App\Repository\PendingListRepository;
use App\Repository\SessionRepository;

class SessionService
{
    public function getNextSessionInfo($session, $user, SessionRepository $sessionRepository, PendingListRepository $pendingListRepository): array
    {
        if ($session == null){
            
            // *** fonction crée dans le repo *** //
            // Si la session est nulle en argument, on récupère la prochaine
            $session = $sessionRepository->findNext(); 
        }

        $users = $pendingListRepository->findBy(['session'=>$session, 'user'=>$user]);
        if($users){
            $statusUserThisSession = "signed";
        }
        else {
            $statusUserThisSession = "notsigned";
        }
        
        //Le nombre d'utilisateurs dans une session
        $usersInSession = count($session->getUser());

        //On récupère la liste d'attente de la session
        $pendingLists = $pendingListRepository->getPendingList($session);

        //Calcul des places restantes
        $adultsNb = 75 - count($pendingLists['adults']);
        $kidsNb = 15 - count($pendingLists['kids']);

        return array(
            "session"=>$session,
            "adults"=> $adultsNb,
            "children"=>$kidsNb,
            "usersInSession" => $usersInSession,
            "statusUserThisSession" => $statusUserThisSession
        );
    }
}