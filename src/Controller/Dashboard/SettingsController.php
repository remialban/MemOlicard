<?php

namespace App\Controller\Dashboard;

use App\Entity\User;
use App\Form\Settings\ProfileType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\Settings\Security\ChangePasswordType;
use App\Form\Settings\Security\CreatePasswordType;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @Route(path={
 *     "en": "/settings",
 *     "fr": "/parametre"
 * }, name="settings_")
 * @IsGranted("ROLE_USER")
 */
class SettingsController extends AbstractController
{
    /**
     * @Route(path={
     *     "en": "/my-profile",
     *     "fr": "/mon-compte"
     * }, name="profile")
     */
    public function profile(
        Request $request,
        ManagerRegistry $managerRegistry,
        User $user = null,
        TranslatorInterface $translator,
        UserPasswordHasherInterface $encoder,
        TokenStorageInterface $tokenStorage): Response
    {
        if (!$user)
        {
            $user = $this->getUser();
        }

        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        $deleteAccountForm = $this->createFormBuilder()
            ->add('password', PasswordType::class, ['label' => 'form.default.password'])
            ->getForm()
        ;
        $deleteAccountForm->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $user = $form->getData();
            $doctrine = $managerRegistry->getManager();
            $doctrine->persist($user);
            $doctrine->flush();

            $this->addFlash("success", $translator->trans('flash.profile.update_successful'));
        }

        if ($deleteAccountForm->isSubmitted() && $deleteAccountForm->isValid())
        {
            $data = $deleteAccountForm->getData();
            if ($encoder->isPasswordValid($this->getUser(), $data['password']))
            {
                $user = $this->getUser();
                $session = $request->getSession();
                $session->invalidate();
                $tokenStorage->setToken();
                $doctrine = $managerRegistry->getManager();
                $doctrine->remove($user);
                $doctrine->flush();
                $this->addFlash('success', $translator->trans('flash.profile.delete.success'));
                return $this->redirectToRoute("home");
            } else {
                $this->addFlash('warning', $translator->trans('flash.auth.invalid_password'));
            }
        }

        return $this->render('dashboard/settings/profile.html.twig', [
            'form' => $form->createView(),
            'deleteAccountForm' => $deleteAccountForm->createView(),
        ]);
    }

    /**
     * @Route(path={
     *     "en": "/security",
     *     "fr": "/securite"
     * }, name="security")
     */
    public function security(
        Request $request,
        ManagerRegistry $managerRegistry,
        UserPasswordHasherInterface $userPasswordHasherInterface,
        TranslatorInterface $translator): Response
    {
        $user = $this->getUser();
        if ($user instanceof User)
        {
            if ($user->getPassword())
            {
                $form = $this->createForm(ChangePasswordType::class, $user);
            } else {
                $form = $this->createForm(CreatePasswordType::class, $user);
            }
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid())
            {
                $user = $form->getData();

                $hashedPassword = $userPasswordHasherInterface->hashPassword($user, $user->getModifiedPassword());
                $user->setPassword($hashedPassword);

                $em = $managerRegistry->getManager();
                $em->persist($user);
                $em->flush();

                $this->addFlash('success', $translator->trans('flash.profile.password_update_successful'));
            }

            return $this->render('dashboard/settings/security.html.twig', [
                'form' => $form->createView(),
            ]);
        }
    }
}
