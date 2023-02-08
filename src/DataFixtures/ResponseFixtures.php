<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Explanation;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ResponseFixtures extends Fixture implements DependentFixtureInterface
{
    public const ANSWERS = [
        [
            'Vrai',
            'Faux',
            'Vrai, si l\'icône de la batterie est en rouge',
        ],
        [
            'Baisser la luminosité',
            'Désactiver l\'accès au wifi',
            'Désactiver la géolocalisation',
        ],
        [
            'La luminosité automatique',
            'La désactivation automatique des données',
            'Le verrouillage automatique',
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($tutoKey = 0; $tutoKey < count(TutorialFixtures::TUTORIALS); $tutoKey++) {
            for ($lessonKey = 0; $lessonKey < count(LessonFixtures::LESSONS); $lessonKey++) {
                for ($questionKey = 0; $questionKey < count(QuestionFixtures::QUESTIONS[$lessonKey]); $questionKey++) {
                    if ($lessonKey == 2) {
                        foreach (self::ANSWERS[$questionKey] as $key => $answerText) {
                            $response = new Explanation();
                            $response->setIsCorrect(false);
                            $response->setAnswer($answerText);
                            if ($key == 1) {
                                $response->setIsCorrect(true);
                            }
                            $question = $this->getReference('tutorial_' . $tutoKey . '_lesson_' . $lessonKey . '_question_' . $questionKey);
                            $response->setQuestion($question);
                            $manager->persist($response);
                        }
                    } else {
                        for ($k = 0; $k < count(self::ANSWERS[$questionKey]); $k++) {
                            $response = new Explanation();
                            $response->setAnswer($faker->sentence(3, true));
                            $response->setIsCorrect(false);
                            if ($k == 1) {
                                $response->setIsCorrect(true);
                            }
                            $question = $this->getReference('tutorial_' . $tutoKey . '_lesson_' . $lessonKey . '_question_' . $questionKey);
                            $response->setQuestion($question);
                            $manager->persist($response);
                        }
                    }
                }
            }
            $manager->flush();
        }
    }

    public function getDependencies()
    {
        return [
            QuestionFixtures::class
        ];
    }
}
