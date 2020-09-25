<?php

namespace App\Form;

use App\Entity\Colleague;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ColleagueType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('pictureFile', VichImageType::class, [
				'required'     => false,
				'allow_delete' => false,
				//				'delete_label'    => '...',
				//				'download_label'  => '...',
				'download_uri' => false,
				//				'image_uri'       => true,
				//				'imagine_pattern' => '...',
				'asset_helper' => true,
				'constraints'  => [
					new File([
						'maxSize'   => '5M',
						'mimeTypes' => [
							'image/jpeg',
							'image/gif',
							'image/png',
						],
					]),
				],
			])
			->add('name', TextType::class, [
				'required' => true,
			])
			->add('role', TextType::class, [
				'required' => false,
			])
			->add('notes', TextareaType::class, [
				'required' => false,
			]);
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => Colleague::class,
		]);
	}
}
