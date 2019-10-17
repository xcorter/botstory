<?php

namespace App\Web\Form;

use App\Core\Game\Entity\Game;
use App\Core\Entity\Script;
use App\Core\Game\GameRepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ScriptType extends AbstractType implements DataMapperInterface
{

    /**
     * @var GameRepositoryInterface
     */
    private $gameRepository;

    /**
     * ScriptType constructor.
     * @param GameRepositoryInterface $gameRepository
     */
    public function __construct(GameRepositoryInterface $gameRepository)
    {
        $this->gameRepository = $gameRepository;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('game', HiddenType::class, [
                'data' => $this->getGameId($options),
            ])
            ->add('step', IntegerType::class)
            ->add('text', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'Введите текст'
                ]
            ])
            ->add('Ok', SubmitType::class)
            ->setDataMapper($this)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => Script::class,
                'game_entity' => null,
                'empty_data' => null,
            ]
        );
        $resolver->setAllowedTypes('game_entity', [Game::class, 'NULL']);
    }

    public function mapDataToForms($data, $forms): void
    {
        if (null === $data) {
            return;
        }

        if (!$data instanceof Script) {
            throw new UnexpectedTypeException($data, Script::class);
        }

        /** @var FormInterface[] $forms */
        $forms = iterator_to_array($forms);

        $forms['step']->setData($data->getStep());
        $forms['text']->setData($data->getText());
        $forms['game']->setData($data->getGame());
    }

    public function mapFormsToData($forms, &$data): void
    {
        /** @var FormInterface[] $forms */
        $forms = iterator_to_array($forms);

        $game = (int) $forms['game']->getData();
        if (!$game) {
            throw new \InvalidArgumentException('Wrong game');
        }
        $game = $this->gameRepository->findById($game);
        $data = new Script(
            $forms['text']->getData(),
            $forms['step']->getData(),
            $game
        );
    }


    /**
     * @param array $options
     * @return int|null
     */
    private function getGameId(array $options): ?int
    {
        /** @var Game $game */
        $game = $options['game_entity'] ?? null;
        if ($game) {
            return $game->getId();
        }
        return null;
    }

}
