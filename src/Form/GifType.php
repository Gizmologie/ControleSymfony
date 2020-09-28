<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Gif;
use App\Entity\User;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;

class GifType extends AbstractType
{

    private CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /*
         * méthode add : permet de spécifier un champs au formulaire
         *      paramètre :
         *          identifiant du champ utilisé dans la vue
         *          type de champs
         *          option lié au champ
         *              contrainte de validation
         */


        $builder
            ->add('source',  FileType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => "L'image est obligatoire"
                    ]),
                    new Image([
                        'mimeTypes' => ['image/gif', 'image/webp'],
                        'mimeTypesMessage' => "Le format de l'image est incorrect"
                    ])
                ],
                'help' => "Seuls les gif et webp sont autorisés"
            ])
            //->add('slug')
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'query_builder' => $this->categoryRepository->getSubCategories(),
                'group_by' => 'parent.name',
                'placeholder' => '',
                'constraints' => [
                    new NotBlank(
                        ['message' => "La catégorie doit être renseignée"]
                    )
                ]
            ])
            /*->add('user', EntityType::class, [
                'class' => User::class
            ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Gif::class,
        ]);
    }
}
