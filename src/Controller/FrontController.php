<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Categorie;
use App\Entity\Commentaire;
use App\Entity\Etablissement;
use App\Form\SearchEtablissementType;
use App\Repository\CategorieRepository;
use App\Repository\EtablissementRepository;
use App\Repository\TypeRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use DateTime;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(EtablissementRepository $etrepo, CategorieRepository $catrepo)
    {
        $etablissements = $etrepo->findBy(
            [
                'etat'=>1
            ]
        );
        $categories = $catrepo->findAll();

        return $this->render('front/home.html.twig', [
            'etablissements' => $etablissements,
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/listing", name="listing")
     */
    public function listing(CategorieRepository $catrepo, EtablissementRepository $etrepo)
    {
        $categories = $catrepo->findAll();
        $etablissements = $etrepo->findBy(
            [
                'etat'=>1
            ]
        );

        return $this->render('front/listing.html.twig', [
            'categories' => $categories,
            'etablissements' => $etablissements,
        ]);
    }


    /**
     * @Route("/category", name="category")
     */
    public function category(CategorieRepository $catrepo ,EtablissementRepository $etrepo, Request $request, PaginatorInterface $paginator)
    {
        $data = new SearchData();
        $data->page = $request->get('page', 1);
        $form = $this->createForm(SearchEtablissementType::class, $data);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $etablissements = $etrepo->findSearch($data);
        } else {
            $donnees = $etrepo->findBy(
                [
                    'etat'=>1
                ]
            );
    
            $etablissements = $paginator->paginate(
                $donnees, // Requête contenant les données à paginer 
                $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
                6 // Nombre de résultats par page
            );
        }

        $categories = $catrepo->findAll();

        return $this->render('front/category.html.twig', [
            'etablissements' => $etablissements,
            'categories' => $categories,
            'form'=> $form->createView(),
        ]);


    }

     /**
     * @Route("/details", name="details_red")
     */
    public function details_red()
    {
        return $this->redirectToRoute('listing');
    }


    /**
     * @Route("/details/{id}", name="details", methods={"GET", "POST"})
     */
    public function details(Etablissement $etablissement, CategorieRepository $catrepo, Request $request, ManagerRegistry $dmanager, UserRepository $userrepo)
    {
        if($request->request->get('formCom')!=null){

            $note = $request->request->get('note');
            $text = $request->request->get('text');
            $user = $this->getUser();

            $com=new Commentaire();

            $com->setEtablissement($etablissement);
            $com->setNote($note);
            $com->setContenu($text);
            $com->setCreatedAt(new DateTime());
            $com->setUser($user);
            

            $manager=$dmanager->getManager();
            $manager->persist($com);
            $manager->flush();

            $categories = $catrepo->findAll();

            $actualCat = $etablissement->getCategorie();

            return $this->redirectToRoute('details', array('id' => $etablissement->getId()));
        }

        $categories = $catrepo->findAll();

        $actualCat = $etablissement->getCategorie();

        if($actualCat->getTitre() == "Restaurant") {

            return $this->render('front/restaurant.html.twig', [
                'etablissement' => $etablissement,
                'categories' => $categories,
            ]);

        } elseif($actualCat->getTitre() == "Boutique") {
            return $this->render('front/boutique.html.twig', [
                'etablissement' => $etablissement,
                'categories' => $categories,
            ]);

        } elseif($actualCat->getTitre() == "Hotel"){
            return $this->render('front/hotel.html.twig', [
                'etablissement' => $etablissement,
                'categories' => $categories,
            ]);

        } elseif($actualCat->getTitre() == "Bar"){
            return $this->render('front/bar.html.twig', [
                'etablissement' => $etablissement,
                'categories' => $categories,
            ]);
        }

        
    }

    

}
