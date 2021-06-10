<?php

namespace App\Controller\Admin;

use App\Entity\Brand;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class BrandCrudController extends AbstractCrudController
{

    public static function getEntityFqcn(): string
    {
        return Brand::class;
    }
    
    public function configureFields(string $pageName): iterable
    {

        $fields = [
            TextField::new('name'),
            DateField::new('updated_at')->hideOnForm(),
            SlugField::new('slug')->setTargetFieldName('name')
        ];

        if ($pageName == Crud::PAGE_INDEX) {
            $fields[] = ImageField::new('logo')->setBasePath('/images/brands');
        }elseif($pageName == Crud::PAGE_EDIT || $pageName == Crud::PAGE_NEW ){
            $fields[] = Field::new('imageFile')->setFormType(VichImageType::class)
                ->setTranslationParameters(['form.label.delete'=>'Remove image ?'])
                ->onlyOnForms();
            
        }
        return $fields; 
    }

    
}
