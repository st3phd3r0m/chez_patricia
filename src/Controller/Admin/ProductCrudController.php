<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ImageType;
use App\Form\SizeType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    
    public function configureFields(string $pageName): iterable
    {

        $fields = [
            TextField::new('name'),
            TextEditorField::new('description'),
            AssociationField::new('brand'),
            IntegerField::new('mark')->hideOnForm(),
            MoneyField::new('price_duty_free')->setCurrency('EUR'),
            DateTimeField::new('created_at')->hideOnForm(),
            DateTimeField::new('updated_at')->hideOnForm(),
            SlugField::new('slug')->setTargetFieldName('name')
        ];

        if($pageName == Crud::PAGE_DETAIL){
            $fields[] = ArrayField::new('sizes')->setTemplatePath('admin/sizesShow.html.twig');
            $fields[] = CollectionField::new('images')->setTemplatePath('admin/imagesShow.html.twig');
            $fields[] = CollectionField::new('category');
            $fields[] = CollectionField::new('comments')->setTemplatePath('admin/commentList.html.twig');
        }elseif ($pageName == Crud::PAGE_INDEX) {
            $fields[] = CollectionField::new('sizes')->setSortable(false);
            $fields[] = CollectionField::new('images')->setSortable(false);
            $fields[] = CollectionField::new('category')->setSortable(false);
            $fields[] = CollectionField::new('comments')->setSortable(false);
            
        }elseif($pageName == Crud::PAGE_EDIT || $pageName == Crud::PAGE_NEW){
            $fields[] = CollectionField::new('sizes')->setEntryType(SizeType::class);
            $fields[] = AssociationField::new('category');
            $fields[] = CollectionField::new('images')->setEntryType(ImageType::class);
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
