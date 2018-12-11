<?php

namespace Reviewer\ReviewBundle\Form;

use Reviewer\ReviewBundle\Service\BookService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class BookType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var BookService $bookService */
        $bookService = $options['book_service'];
        $genreArray = array_column($bookService->getGenres(), 'id', 'genreName');


        $builder->add('isbn', TextType::class)
            ->add('title', TextType::class)
            ->add('genre_id', ChoiceType::class, [
                'choices' => [
                    'Pick a genre' => $genreArray
                ],
                'label' => 'Genre'
            ])
            ->add('cover_image', FileType::class, [
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

        $resolver->setRequired('book_service');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'reviewer_reviewbundle_book';
    }

}
