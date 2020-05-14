<?php

namespace App\Controller;

use App\Entity\Etablissement;
use App\Entity\Categorie;
use App\Entity\Horaire;
use App\Entity\Horaire2;
use App\Entity\Photo;
use App\Entity\User;
use App\Repository\CategorieRepository;
use App\Repository\CommentaireRepository;
use App\Repository\EtablissementRepository;
use App\Repository\Horaire2Repository;
use App\Repository\HoraireRepository;
use App\Repository\JourRepository;
use App\Repository\PhotoRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BackController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboard(UserRepository $userrepo, CommentaireRepository $comrepo, EtablissementRepository $etrepo)
    {
        $users = $userrepo->findAll();
        $commentaires = $comrepo->findAll();
        $etablissementsA = $etrepo->findBy(
            [
                'etat'=>1
            ]
        );
        $etablissementsD = $etrepo->findBy(
            [
                'etat'=>0
            ]
        );

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('back/dashboard.html.twig', [
            'users' => $users,
            'commentaires' => $commentaires,
            'etablissementsA' => $etablissementsA,
            'etablissementsD' => $etablissementsD,
        ]);
    }

    /**
     * @Route("/avis", name="avis")
     */
    public function avis(CommentaireRepository $comrepo)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $commentaires = $comrepo->findAll();

        return $this->render('back/avis.html.twig', [
            'commentaires' => $commentaires,
        ]);
    }

    /**
     * @Route("/utilisateurs", name="utilisateurs")
     */
    public function utilisateurs(UserRepository $userepo)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $users = $userepo->findAll();

        return $this->render('back/utilisateurs.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/listingActifs", name="listingActifs")
     */
    public function listingActifs(EtablissementRepository $etrepo, PaginatorInterface $paginator, Request $request, CategorieRepository $catrepo)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $categories = $catrepo->findAll();

        $donnees = $etrepo->findBy(
            [
                'etat'=>1
            ]
        );

        $etablissements = $paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6 // Nombre de résultats par page
        );

        return $this->render('back/listingActifs.html.twig', [
            'etablissements' => $etablissements,
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/categorieActifs/{id}", name="categorieActifs")
     */
    public function categorieActifs(EtablissementRepository $etrepo, PaginatorInterface $paginator, Request $request, Categorie $categorie, CategorieRepository $catrepo)
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $categories = $catrepo->findAll();

        $donnees = $etrepo->findBy(
            [
                'etat'=>1,
                'categorie'=>$categorie
            ]
        );

        $etablissements = $paginator->paginate(
            $donnees, // Requête contenant les données à paginer 
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6 // Nombre de résultats par page
        );

        return $this->render('back/listingActifs.html.twig', [
            'etablissements' => $etablissements,
            'categorie' => $categorie,
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/listingDisabled", name="listingDisabled")
     */
    public function listingDisabled(EtablissementRepository $etrepo, PaginatorInterface $paginator, Request $request, CategorieRepository $catrepo)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $categories = $catrepo->findAll();

        $donnees = $etrepo->findBy(
            [
                'etat'=>0
            ]
        );

        $etablissements = $paginator->paginate(
            $donnees, // Requête contenant les données à paginer
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            4 // Nombre de résultats par page
        );

        return $this->render('back/listingDisabled.html.twig', [
            'etablissements' => $etablissements,
            'categories' => $categories,
        ]);
    }

     /**
     * @Route("/categorieDisabled/{id}", name="categorieDisabled")
     */
    public function categorieDisabled(EtablissementRepository $etrepo, PaginatorInterface $paginator, Request $request, Categorie $categorie, CategorieRepository $catrepo)
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $categories = $catrepo->findAll();

        $donnees = $etrepo->findBy(
            [
                'etat'=>0,
                'categorie'=>$categorie
            ]
        );

        $etablissements = $paginator->paginate(
            $donnees, // Requête contenant les données à paginer 
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6 // Nombre de résultats par page
        );

        return $this->render('back/listingDisabled.html.twig', [
            'etablissements' => $etablissements,
            'categorie' => $categorie,
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/listingDetails/{id}", name="listingDetails", methods={"GET", "POST"})
     */
    public function listingDetails(CategorieRepository $catrepo, Etablissement $et, Request $request, ManagerRegistry $dmanager, JourRepository $jourrepo, Horaire2Repository $horairerepo)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if($request->request->get('basicInfo')!=null){
            
            $nom = $request->request->get('nom');

            $categorie_id = $request->request->get('categorie');
            $categorie = $catrepo->find($categorie_id);

            $desc = $request->request->get('desc');
            $tel = $request->request->get('telephone');
            $site = $request->request->get('site');
            $email = $request->request->get('email');

            $et->setNom($nom);
            $et->setDescription($desc);
            $et->setTelephone($tel);
            $et->setSite($site);
            $et->setSite($email);
            $et->setCategorie($categorie);

            $manager=$dmanager->getManager();
            $manager->persist($et);
            $manager->flush();

            return $this->redirectToRoute('listingDetails', array('id' => $et->getId()));

        }

        if($request->request->get('localisation')!=null){
            
            $adresse = $request->request->get('adresse');
            $lien = $request->request->get('lienMap');

            $et->setAdresse($adresse);
            $et->setLienMap($lien);

            $manager=$dmanager->getManager();
            $manager->persist($et);
            $manager->flush();

            return $this->redirectToRoute('listingDetails', array('id' => $et->getId()));

        }

        if($request->request->get('photo')!=null){
            
            $lien = $request->request->get('lienPhoto');

            $photo = new Photo();

            $photo->setLien($lien);
            $photo->setEtablissement($et);


            $manager=$dmanager->getManager();
            $manager->persist($photo);
            $manager->flush();

            return $this->redirectToRoute('listingDetails', array('id' => $et->getId()));

        }

        if($request->request->get('horaire')!=null){
            
            $contenu = $request->request->get('hor');

            $horaire = $horairerepo->find($et);

            $horaire->setContenu($contenu);


            $manager=$dmanager->getManager();
            $manager->persist($horaire);
            $manager->flush();

            return $this->redirectToRoute('listingDetails', array('id' => $et->getId()));

        }


        $categories = $catrepo->findAll();
        $jours = $jourrepo->findAll();

        return $this->render('back/listing_details.html.twig', [
            'categories' => $categories,
            'etablissement' => $et,
            'jours' => $jours,
        ]);
    }

    /**
     * @Route("delete_photo/{id}/{idListing}", name="delete_photo")
     * 
     * @return Response
     */

    public function delete_photo($id, $idListing, PhotoRepository $photorepo){
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $photo = $photorepo->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($photo);
        $em->flush();

        return $this->redirectToRoute('listingDetails', array('id' => $idListing));
    }

    /**
     * @Route("disable_et/{id}", name="disable_et")
     * 
     * @return Response
     */

    public function disable_et($id, EtablissementRepository $etrepo, ManagerRegistry $dmanager){

        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $et = $etrepo->find($id);

        $et->setEtat(0);

        $manager=$dmanager->getManager();
        $manager->persist($et);
        $manager->flush();

        return $this->redirectToRoute('listingActifs');
    }

    /**
     * @Route("enable_et/{id}", name="enable_et")
     * 
     * @return Response
     */

    public function enable_et($id, EtablissementRepository $etrepo, ManagerRegistry $dmanager){

        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $et = $etrepo->find($id);

        $et->setEtat(1);

        $manager=$dmanager->getManager();
        $manager->persist($et);
        $manager->flush();

        return $this->redirectToRoute('listingDisabled');
    }

      /**
     * @Route("delete_avis/{id}", name="delete_avis")
     * 
     * @return Response
     */

    public function delete_avis($id, CommentaireRepository $comrepo){
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $avis = $comrepo->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($avis);
        $em->flush();

        return $this->redirectToRoute('avis');
    }

    /**
     * @Route("delete_user/{id}", name="delete_user")
     * 
     * @return Response
     */

    public function delete_user($id, UserRepository $userepo, CommentaireRepository $comrepo){

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $em = $this->getDoctrine()->getManager();

        $user = $userepo->find($id);

        $com = $comrepo->findBy(
            [
                'user'=>$user
            ]
        );

        foreach ($com as $com) {
            $em->remove($com);
        }

        
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('utilisateurs');
    }

    /**
     * @Route("modif_user/{id}", name="modif_user", methods={"GET", "POST"})
     * 
     * @return Response
     */

    public function modif_user(User $user, Request $request, ManagerRegistry $dmanager){

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if($request->request->get('modif')!=null){
            
            $pseudo = $request->request->get('pseudo');
            $email = $request->request->get('email');
            $roles[] = $request->request->get('roles');

            $user->setEmail($email);
            $user->setPseudo($pseudo);
            $user->setRoles($roles);

            $manager=$dmanager->getManager();
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('utilisateurs');

        }

        return $this->render('back/modif_user.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("add_listing", name="add_listing", methods={"GET", "POST"})
     * 
     * @return Response
     */

    public function add_listing(CategorieRepository $catrepo, Request $request, ManagerRegistry $dmanager){

        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $categories = $catrepo->findAll();

        if($request->request->get('ajouter')!=null){
            
            $nom = $request->request->get('nom');
            $categorieid = $request->request->get('categorie');
            $categorie = $catrepo->find($categorieid);
            $desc = $request->request->get('desc');
            $tel = $request->request->get('telephone');
            $site = $request->request->get('site');
            $email = $request->request->get('email');
            $adresse = $request->request->get('adresse');
            $lienmap = $request->request->get('lienmap');
            $horaire = $request->request->get('horaire');

            $et = new Etablissement();
            

            $et->setNom($nom);
            $et->setCategorie($categorie);
            $et->setDescription($desc);
            $et->setTelephone($tel);
            $et->setSite($site);
            $et->setEmail($email);
            $et->setAdresse($adresse);
            $et->setLienMap($lienmap);
            $et->setEtat(1);
 

            $manager=$dmanager->getManager();
            $manager->persist($et);

            $hor = new Horaire2();
            $hor->setEtablissement($et);
            $hor->setContenu($horaire);
            $manager->persist($hor);

            $manager->flush();

            return $this->redirectToRoute('listingActifs');

        }

        return $this->render('back/add_listing.html.twig', [
            'categories' => $categories,
        ]);
    }
}

