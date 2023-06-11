<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class KeyboardController extends AbstractController
{
    /**
     * @Route("/keyboard", name="keyboard")
     */
    public function index(Request $request): Response
    {
        $form = $this->createFormBuilder()
            ->add('keyboard', TextType::class)
            ->add('language', ChoiceType::class, [
                'choices' => [
                    'English' => 'English',
                    'French' => 'French',
                    'Arabic' => 'Arabix'
                ],
                'label' => 'Language',
            ])
            ->add('submit', SubmitType::class, ['label' => 'Submit'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $inputText = $form->getData()['keyboard'];
            $language = $form->getData()['language'];

            $command = 'python ' . __DIR__ . '/../../scripts/bot.py "' . $inputText . '" "' . $form->getData()['language'] . '"';
            $descriptors = [
                0 => ['pipe', 'r'],
                1 => ['pipe', 'w'],
                2 => ['pipe', 'w'],
            ];
            $process = proc_open($command, $descriptors, $pipes);

            if (is_resource($process)) {
                $output = stream_get_contents($pipes[1]);
                $errorOutput = stream_get_contents($pipes[2]);
                fclose($pipes[0]);
                fclose($pipes[1]);
                fclose($pipes[2]);

                $exitCode = proc_close($process);

                if ($exitCode === 0) {
                    $capitalizedText = trim($output);

                    return $this->render('keyboard/index.html.twig', [
                        'form' => $form->createView(),
                        'capitalizedText' => $capitalizedText,
                    ]);
                } else {
                    throw new \RuntimeException('Failed to execute the Python script: ' . $errorOutput);
                }
            } else {
                throw new \RuntimeException('Failed to start the Python process.');
            }
        }

        return $this->render('keyboard/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
