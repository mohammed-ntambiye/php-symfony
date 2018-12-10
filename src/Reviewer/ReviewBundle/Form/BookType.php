<?php

namespace Reviewer\ReviewBundle\Form;

use Reviewer\ReviewBundle\Service\BookService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class BookType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var BookService $bookService */
        //  $bookService = $options['book_service'];

        // $genreArray = array_column($bookService->getGenres(), 'genre', 'id');

        $genreArray = array(
            'Love' => null,
            'Horor' => null
        );

        $builder->add('isbn')
            ->add('title')
            ->add('genre_id', ChoiceType::class, [
                'choices' => [
                    'Pick a genre' => $genreArray
                ],
                'label' => 'Genre'
            ])
            ->add('coverImage', FileType::class, [
                'data_class' => null])
            ->add('submit', SubmitType::class, [
                'attr' => array(
                    'class' => 'btn btn-primary')
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Reviewer\ReviewBundle\Entity\Book'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'reviewer_reviewbundle_book';
    }


}
