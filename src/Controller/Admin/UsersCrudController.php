<?php

namespace App\Controller\Admin;

use App\Entity\Users;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use EasyCorp\Bundle\EasyAdminBundle\Provider\AdminContextProvider;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UsersCrudController extends AbstractCrudController
{

    private AdminContextProvider $context;

    public function __construct(AdminContextProvider $context)
    {
        $this->context = $context;
    }

    public static function getEntityFqcn(): string
    {
        return Users::class;
    }
    
    public function configureFields(string $pageName): iterable
    {

        $fields = [
            EmailField::new('email'),
            ChoiceField::new('roles')->setChoices([
                    'Administrateur'=>'ROLE_ADMIN',
                    'Utilisateur'=>'ROLE_USER',
                ])->allowMultipleChoices()
        ];

        $entityInstance = $this->context->getContext()->getEntity()->getInstance();

        if( ($pageName == Crud::PAGE_EDIT && $entityInstance == $this->getUser() ) || $pageName == Crud::PAGE_NEW){

            $fields[] = Field::new('plainPassword')->setFormType(RepeatedType::class)->setFormTypeOptions([
                'required' => true,
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passes ne sont pas identiques !',
                'first_options' => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmer le mot de passe'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez votre mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir minimum {{ limit }} caractères',
                        'max' => 4096,
                    ]),
                ],

            ]);
            
        }

        return $fields;
    }
    
}
