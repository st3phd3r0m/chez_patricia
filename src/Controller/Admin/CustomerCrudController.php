<?php

namespace App\Controller\Admin;

use App\Entity\Customer;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CustomerCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Customer::class;
    }

    public function configureFields(string $pageName): iterable
    {

        $fields = [
            TextField::new('firstname'),
            TextField::new('lastname'),
            TextField::new('pseudo'),
            EmailField::new('email'),
            TextField::new('address'),
            TextField::new('postal_code'),
            TextField::new('phone'),
            BooleanField::new('email_verified'),
        ];

        if($pageName == Crud::PAGE_DETAIL){
            $fields[] = CollectionField::new('comments')->setTemplatePath('admin/commentList.html.twig');
        }elseif ($pageName == Crud::PAGE_INDEX) {
            $fields[] = CollectionField::new('comments')->setSortable(false);
        }

        return $fields; 
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
        ;
    }

}
