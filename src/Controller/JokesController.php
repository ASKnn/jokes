<?php


namespace App\Controller;

use App\Events;
use App\Form\JokesType;
use App\Service\JokesService\JokesSourceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class JokesController extends AbstractController
{
    /**
     * @var JokesSourceInterface
     */
    private $jokeSource;

    /**
     * Устанавливает "источник шуток".
     *
     * Можно внедрить в каждый метод эту зависимость, но я подразумеваю, что все методы контроллера будут использовать
     * одну конкретную реализацию. Например, если экшен формы ведет на другой роут (метод), и там требуется выполнить
     * что-то с $jokeSource, например, получить случайную шутку.
     * @param JokesSourceInterface $jokeSource
     */
    public function setJokeSource(JokesSourceInterface $jokeSource)
    {
        $this->jokeSource = $jokeSource;
    }

    /**
     * @Route("/", name="jokes_index")
     * @param Request $request
     * @param EventDispatcherInterface $eventDispatcher
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @return Response
     */
    public function getForm(Request $request, EventDispatcherInterface $eventDispatcher)
    {
        $form = $this->createForm(JokesType::class, null, [
            JokesType::FIELD_CATEGORIES => $this->jokeSource->getAllCategories()
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoriesString = $request->get(JokesType::FIELD_CATEGORIES, null);

            if (isset ($categoriesString) && is_string($categoriesString)) {
                $randomJoke = $this->jokeSource->getRandomItemFromCategories([$categoriesString]);
                $event = new GenericEvent($randomJoke, [
                    "category_name" => $categoriesString,
                    "email_to" => $request->get(JokesType::FIELD_EMAIL)
                ]);
                $eventDispatcher->dispatch(Events::JOKE_SEND, $event);
                return $this->redirectToRoute("jokes_index");

            } else {
                $form->get(JokesType::FIELD_CATEGORIES)->addError(new FormError('Error! :('));
            }
        }

        return $this->render('jokes/index.html.twig', ['form' => $form->createView()]);
    }

}