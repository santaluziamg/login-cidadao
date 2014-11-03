<?php

namespace PROCERGS\LoginCidadao\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use PROCERGS\LoginCidadao\CoreBundle\Helper\GridHelper;
use PROCERGS\LoginCidadao\CoreBundle\Form\Type\RemoveIdCardFormType;
use PROCERGS\LoginCidadao\CoreBundle\Model\PersonInterface;
use Doctrine\Common\Collections\Collection;

class IdCardController extends Controller
{

    /**
     * @Route("/person/idcards", name="lc_person_id_cards_list")
     * @Template
     */
    public function listAction(Request $request)
    {
        $idCards = $this->getPerson()->getIdCards();
        $deleteForms = $this->getDeleteForms($idCards);
        return compact('idCards', 'deleteForms');
    }

    /**
     * @Route("/person/idcards/{id}/edit", name="lc_person_id_cards_edit")
     * @Template
     */
    public function editAction(Request $request, $id)
    {
        $fragment = $request->query->has('fragment');
        $em = $this->getDoctrine()->getManager();
        $person = $this->getUser();

        $idCards = $em->getRepository('PROCERGSLoginCidadaoCoreBundle:IdCard');
        $idCard = $idCards->findPersonIdCard($person, $id);

        $form = $this->createForm('lc_idcard_form', $idCard);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $newIdCard = $form->getData();
            $em->persist($newIdCard);
            $em->flush();
            return $this->redirect($this->generateUrl('lc_documents'));
        }

        return array(
            'form' => $form->createView(),
            'fragment' => $fragment,
            'id' => $id,
            'deleteForms' => $this->getDeleteForms()
        );
    }

    /**
     * @Route("/person/idcards/{id}/remove", name="lc_person_id_cards_delete")
     * @Template()
     */
    public function deleteAction(Request $request, $id)
    {
        $translator = $this->get('translator');
        $form = $this->createForm(new RemoveIdCardFormType());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $person = $this->getUser();
            $em = $this->getDoctrine()->getManager();
            $idCards = $em->getRepository('PROCERGSLoginCidadaoCoreBundle:IdCard');
            $idCard = $idCards->find($id);

            try {
                if ($idCard->getPerson()->getId() !== $person->getId()) {
                    throw new AccessDeniedException();
                }
                $em->remove($idCard);
                $em->flush();
            } catch (AccessDeniedException $e) {
                $this->get('session')->getFlashBag()->add('error',
                                                          $translator->trans("Access Denied."));
            } catch (\Exception $e) {
                $this->get('session')->getFlashBag()->add('error',
                                                          $translator->trans("Couldn't remove this ID Card."));
                $this->get('session')->getFlashBag()->add('error',
                                                          $e->getMessage());
            }
        } else {
            $this->get('session')->getFlashBag()->add('error',
                                                      $translator->trans("Couldn't remove this ID Card."));
        }
        return $this->redirect($this->generateUrl('lc_documents'));
    }

    protected function getDeleteForms($idCards = null)
    {
        $deleteForms = array();
        if ($idCards === null) {
            $idCards = $this->getPerson()->getIdCards();
        }

        if (is_array($idCards) || $idCards instanceof Collection) {
            foreach ($idCards as $idCard) {
                $data = array('id_card_id' => $idCard->getId());
                $deleteForms[$idCard->getId()] = $this->createForm(new RemoveIdCardFormType(),
                                                                   $data)->createView();
            }
        }
        return $deleteForms;
    }

    /**
     * @return PersonInterface
     */
    protected function getPerson()
    {
        return $this->getUser();
    }

}
