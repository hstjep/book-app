<?php

namespace App\Form;

use App\Domain\Repository\AuthorRepositoryInterface;
use App\Utils\Filter\FilterOptions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class BookFormType extends AbstractType
{
    public function __construct(
        private AuthorRepositoryInterface $authorRepository,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Load author choice options
        $filterOptions = (new FilterOptions())
            ->setPage(1)
            ->setPageSize(1000); // For real-world scenario, can be changed to handle paginated load on scroll.

        $authors = $this->authorRepository->find($filterOptions);
        $authorChoices = $this->mapAuthorChoices($authors->getItems());

        // Build form
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'id' => 'title',
                    'data-validate' => 'required',
                ]
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'id' => 'description',
                    'data-validate' => 'required',
                ]
            ])
            ->add('author', ChoiceType::class, [
                'attr' => [
                    'id' => 'author',
                    'data-validate' => 'required',
                ],
                'choices' => $authorChoices
            ])
            ->add('release_date', DateType::class, [
                'attr' => [
                    'id' => 'release_date',
                    'data-validate' => 'required',
                ],
                'widget' => 'choice',
                'years' => range(date('Y'), date('Y')-100),
                'months' => range(1, 12),
                'days' => range(1, 31)
            ])
            ->add('isbn', TextType::class, [
                'attr' => [
                    'id' => 'isbn',
                    'data-validate' => 'required',
                ]
            ])
            ->add('format', TextType::class, [
                'attr' => [
                    'id' => 'format',
                    'data-validate' => 'required',
                ]
            ])
            ->add('number_of_pages', IntegerType::class, [
                'attr' => [
                    'id' => 'number_of_pages',
                    'data-validate' => 'required',
                ]
            ])
            ->add('_submit', SubmitType::class, [
                'label' => 'Create'
            ]);
    }

    /**
     * Maps author choices
     *
     * @param array $authors
     * @return array
     */
    private function mapAuthorChoices(array $authors): array
    {
        $authorChoices = [];
        $duplicateCounter = []; // Handles possible author name duplicates in select type

        foreach($authors as $index => $author) {
            $fullName = $author['first_name'] . ' ' . $author['last_name'];
            
            if (!array_key_exists($fullName, $duplicateCounter)) {
                $duplicateCounter[$fullName] = 1;
            }

            if (array_key_exists($fullName, $authorChoices)) {
                $fullName .= "_" . ++$duplicateCounter[$fullName];
            }

            $authorChoices[$fullName] = $author['id'];
        }

        return $authorChoices;
    }
}
