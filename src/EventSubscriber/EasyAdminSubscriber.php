<?php

namespace App\EventSubscriber;

use App\Entity\Brand;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Customer;
use App\Entity\Image;
use App\Entity\Product;
use App\Entity\User;
use App\Repository\ImageRepository;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityDeletedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\Persistence\ManagerRegistry;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Provider\AdminContextProvider;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    private ImageRepository $imageRepository;
    private ManagerRegistry $managerRegistry;
    private UserPasswordEncoder $passwordEncoder;
    private AdminContextProvider $context;

    public function __construct(ManagerRegistry $managerRegistry, ImageRepository $imageRepository, UserPasswordEncoderInterface $passwordEncoder, AdminContextProvider $context)
    {
        $this->managerRegistry = $managerRegistry;
        $this->imageRepository = $imageRepository;
        $this->passwordEncoder = $passwordEncoder;
        $this->context = $context;
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityUpdatedEvent::class => ['onBeforeEntityUpdatedEvent'],
            BeforeEntityPersistedEvent::class => ['onBeforeEntityPersistedEvent'],
            BeforeEntityDeletedEvent::class => ['onBeforeEntityDeletedEvent'],
            AfterEntityUpdatedEvent::class => ['onAfterEntityUpdatedEvent'],
        ];
    }

    public function onBeforeEntityUpdatedEvent(BeforeEntityUpdatedEvent $event)
    {
        $entityInstance = $event->getEntityInstance();
        if ($entityInstance instanceof Brand || $entityInstance instanceof Product || $entityInstance instanceof Image ) {
            $entityInstance->setUpdatedAt(new \DateTimeImmutable('now', new \DateTimeZone('Europe/Paris')));
        }

        if ($entityInstance instanceof User && $entityInstance == $this->context->getContext()->getUser()){
            $this->setUserPassword($entityInstance);
        }

    }

    public function onAfterEntityUpdatedEvent(AfterEntityUpdatedEvent $event)
    {
        $entityManager = $this->managerRegistry->getManager();

        $images = $this->imageRepository->findBy(['product'=> null]);


        foreach ($images as $key => $image) {
            $entityManager->remove($image);
        }

        $entityManager->flush();
    }

    public function onBeforeEntityPersistedEvent(BeforeEntityPersistedEvent $event)
    {
        $entityInstance = $event->getEntityInstance();

        if ($entityInstance instanceof Brand || $entityInstance instanceof Image  ) {
            $entityInstance->setUpdatedAt(new \DateTimeImmutable('now', new \DateTimeZone('Europe/Paris')));
        }

        if ($entityInstance instanceof Product ) {
            $entityInstance->setCreatedAt(new \DateTimeImmutable('now', new \DateTimeZone('Europe/Paris')));
            $entityInstance->setUpdatedAt(new \DateTimeImmutable('now', new \DateTimeZone('Europe/Paris')));
        }

        if ($entityInstance instanceof Comment ) {
            $entityInstance->setCreatedAt(new \DateTimeImmutable('now', new \DateTimeZone('Europe/Paris')));
        }

        if ($entityInstance instanceof User ){
            $this->setUserPassword($entityInstance);
        }
    }

    public function onBeforeEntityDeletedEvent(BeforeEntityDeletedEvent $event)
    {
        $entityInstance = $event->getEntityInstance();

        if ($entityInstance instanceof Product ) {

            $this->removeProductAttachments($entityInstance);
        }

        if ($entityInstance instanceof Brand ) {

            $this->removeBrandFromProduct($entityInstance);
        }

        if ($entityInstance instanceof Customer ) {
            
            $this->removeCommentsFromCustomer($entityInstance);
        }

    }

    public function removeProductAttachments(Product $product)
    {
        $entityManager = $this->managerRegistry->getManager();

        foreach ($product->getImages() as $key => $image) {
            $product->removeImage($image);
            $entityManager->remove($image);
        }


        foreach ($product->getComments() as $key => $comment) {
            $product->removeComment($comment);
            //Faut-il aussi effacer les commentaires associés ?
            // $entityManager->remove($comment);
        }
    }

    public function removeBrandFromProduct(Brand $brand)
    {
        foreach ($brand->getProducts() as $key => $product) {
            
            $brand->removeProduct($product);
        }
    }

    public function removeCommentsFromCustomer(Customer $customer)
    {
        $entityManager = $this->managerRegistry->getManager();
        foreach ($customer->getComments() as $key => $comment) {
            $customer->removeComment($comment);
            $entityManager->remove($comment);
        }
    }

    public function setUserPassword(User $user)
    {
        $user->setPassword(
            $this->passwordEncoder->encodePassword(
                $user,
                $user->getPlainPassword()
            )
        );
        $user->eraseCredentials();
    }
}
