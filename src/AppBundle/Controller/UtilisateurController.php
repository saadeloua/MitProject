<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Forms;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Utilisateur controller.
 *
 * @Route("utilisateur")
 */
class UtilisateurController extends Controller
{

  /**
  * @Route("/login", name="utilisateur_login")
  * @Method({"GET", "POST"})
  */
  public function loginAction(Request $request)
  {

    if ($request->isMethod('POST')) {
        $form = $this->createFormBuilder()->getForm();
        $username = $request->request->get('form')["username"];
        $password = $request->request->get('form')["password"];
        // si j'avais plus de temps, à deplacer dans le repo
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
                'SELECT u
                FROM AppBundle:Utilisateur u
                WHERE u.username = :username
                AND  u.password = :password'
                )->setParameter('password', $password)->setParameter('username', $username);
        $utilisateur = $query->getResult();
        if($utilisateur != null){
          $session = new Session();
          $session->set('user', $utilisateur);
          return $this->redirectToRoute('utilisateur_index');
        }
        else{
          $formFactory = Forms::createFormFactoryBuilder()->getFormFactory();
          $form = $formFactory->createBuilder()
          ->add('username')
          ->add('password')
          ->getForm();
          return $this->render('utilisateur/login.html.twig', array(
            'error' => 'Utilisateur introuvable',
            'form' => $form->createView()
          ));
        }

    }
    if ($request->isMethod('GET')) {
      $session = new Session();
      if($session->get('user') == null){
        $formFactory = Forms::createFormFactoryBuilder()->getFormFactory();
        $form = $formFactory->createBuilder()
        ->add('username')
        ->add('password')
        ->getForm();
        return $this->render('utilisateur/login.html.twig', array(
          'error' => 'Utilisateur introuvable',
          'form' => $form->createView()
        ));
      }
      else {
        return $this->redirectToRoute('utilisateur_index');
      }
    }
  }

    /**
     * Lists all utilisateur entities.
     *
     * @Route("/", name="utilisateur_index")
     * @Method("GET")
     */
    public function indexAction()
    {

        if(!$this->checkSession()){
          return $this->forward('AppBundle:Utilisateur:login');
        }
        else{
        $em = $this->getDoctrine()->getManager();

        $utilisateurs = $em->getRepository('AppBundle:Utilisateur')->findAll();

        return $this->render('utilisateur/index.html.twig', array(
            'utilisateurs' => $utilisateurs,
        ));
        }
    }

    /**
     * Creates a new utilisateur entity.
     *
     * @Route("/new", name="utilisateur_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        if(!$this->checkSession()){
         return $this->forward('AppBundle:Utilisateur:login');
        }
        $utilisateur = new Utilisateur();
        $form = $this->createForm('AppBundle\Form\UtilisateurType', $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($utilisateur);
            $em->flush();
            //$this->addFlash('Succes', 'Utilisateur créé avec succes' . $utilisateur->getUsername());
            return $this->redirectToRoute('utilisateur_show', array('id' => $utilisateur->getId()));
        }

        return $this->render('utilisateur/new.html.twig', array(
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new utilisateur entity.
     *
     * @Route("/logout", name="utilisateur_logout")
     * @Method({"GET", "POST"})
     */
    public function logoutAction(Request $request)
    {

      $session = new Session();
      $session->invalidate();
      return $this->forward('AppBundle:Utilisateur:login');
    }

    /**
     * Finds and displays a utilisateur entity.
     *
     * @Route("/{id}", name="utilisateur_show")
     * @Method("GET")
     */
    public function showAction(Utilisateur $utilisateur)
    {
      if(!$this->checkSession()){
       return $this->forward('AppBundle:Utilisateur:login');
      }
        $deleteForm = $this->createDeleteForm($utilisateur);

        return $this->render('utilisateur/show.html.twig', array(
            'utilisateur' => $utilisateur,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing utilisateur entity.
     *
     * @Route("/{id}/edit", name="utilisateur_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Utilisateur $utilisateur)
    {
      if(!$this->checkSession()){
       return $this->forward('AppBundle:Utilisateur:login');
      }
        $deleteForm = $this->createDeleteForm($utilisateur);
        $editForm = $this->createForm('AppBundle\Form\UtilisateurType', $utilisateur);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('utilisateur_edit', array('id' => $utilisateur->getId()));
        }
        return $this->render('utilisateur/edit.html.twig', array(
            'utilisateur' => $utilisateur,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a utilisateur entity.
     *
     * @Route("/{id}", name="utilisateur_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Utilisateur $utilisateur)
    {
        $form = $this->createDeleteForm($utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($utilisateur);
            $em->flush();
        }

        return $this->redirectToRoute('utilisateur_index');
    }

    /**
     * Creates a form to delete a utilisateur entity.
     *
     * @param Utilisateur $utilisateur The utilisateur entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Utilisateur $utilisateur)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('utilisateur_delete', array('id' => $utilisateur->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }



    /**
     *
     * @Route("/zab", name="utilisateur_zab")
     * @Method("GET")
     */
    public function zabAction()
    {
      /*
      public function logoutAction(Request $request)
      {
        return $this->render('utilisateur/logout.html.twig');
        /*$session = new Session();
        $session->invalidate();
        $formFactory = Forms::createFormFactoryBuilder()->getFormFactory();
        $form = $formFactory->createBuilder()
        ->add('username')
        ->add('password')
        ->getForm();
        return $this->render('utilisateur/login.html.twig', array(
          'error' => 'Vous vous êtes déconnecté',
          'form' => $form->createView()
        ));
      }
      */
    }

    private function checkSession(){
      $session = new Session();
      if($session->get('user') == null){
        return false;
      }
      return true;
    }
}
