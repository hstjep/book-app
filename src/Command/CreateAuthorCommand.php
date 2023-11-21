<?php

namespace App\Command;

use App\Common\Constants;
use App\Domain\Repository\AuthorRepositoryInterface;
use App\Service\SecurityServiceInterface;
use App\Utils\Session\SessionFactory;
use DateTime;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

// the name of the command is what users type after "php bin/console"
#[AsCommand(name: 'app:create-author')]
class CreateAuthorCommand extends Command
{
    public function __construct(
        private SecurityServiceInterface $securityService,
        private SessionFactory $sessionFactory,
        private AuthorRepositoryInterface $authorRepository,
        private string $apiUserEmail,
        private string $apiUserPassword
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Validate api credentials
        try {
            $user = $this->securityService->authenticate($this->apiUserEmail, $this->apiUserPassword);

            $session = $this->sessionFactory->create();
            $session->set(Constants::EXTERNAL_API_TOKEN, $user->getApiToken());
        } catch (\Exception $ex) {
            $output->writeln("Invalid credentials. Check 'API_USER_EMAIL' and 'API_USER_PASSWORD' in .env.local file.");
            return Command::INVALID;
        }

        // Ask questions
        $helper = $this->getHelper('question');

        $firstName = $helper->ask($input, $output, $this->createQuestion('First name', true));
        $lastName = $helper->ask($input, $output, $this->createQuestion('Last name', true));
        $birthday = $helper->ask($input, $output, $this->createQuestion('Birthday (d/m/Y)', true));
        $placeOfBirth = $helper->ask($input, $output, $this->createQuestion('Place of birth', true));
        $gender = $helper->ask($input, $output, $this->createChoiceQuestion('Gender', ['male', 'female']), true);
        $biography = $helper->ask($input, $output, $this->createQuestion('Biography', false));

        try {
            $birthdayDate = DateTime::createFromFormat(Constants::DATE_FORMAT, $birthday);

            $data = [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'birthday' => $birthdayDate->format(Constants::DATE_ISO_FORMAT),
                'biography' => $biography ?: '',
                'gender' => $gender,
                'place_of_birth' => $placeOfBirth
            ];

            // Create author
            $author = $this->authorRepository->create($data);

            if ($author) {
                $output->writeln('Author created successfully.');
            }

            return Command::SUCCESS;
        } catch (\Exception $ex) {
            $output->writeln('Failed to create author: ' . $ex->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * Creates question
     *
     * @param string $fieldName
     * @param boolean $isRequierd
     * @param string|null $defaultValue
     * @return Question
     */
    private function createQuestion(string $fieldName, bool $isRequierd = false, ?string $defaultValue = null)
    {
        $question = new Question(
            $this->formatLabel($fieldName, $isRequierd),
            $defaultValue
        );

        if ($isRequierd) {
            $question = $this->setQuestionValidation($question, $fieldName);
        }

        return $question;
    }

    /**
     * Creates choice question
     *
     * @param string $fieldName
     * @param array $choices
     * @param boolean $isRequierd
     * @return ChoiceQuestion
     */
    private function createChoiceQuestion(string $fieldName, array $choices, bool $isRequierd = false)
    {
        $question = new ChoiceQuestion(
            $this->formatLabel($fieldName, $isRequierd),
            $choices
        );

        if ($isRequierd) {
            $question = $this->setQuestionValidation($question, $fieldName);
        }

        return $question;
    }

    /**
     * Formats label
     *
     * @param string $fieldName
     * @param boolean $isRequierd
     * @return string
     */
    private function formatLabel(string $fieldName, bool $isRequierd = false): string
    {
        $requiredStatus = $isRequierd ? 'required' : 'optional';
        return "Please select $fieldName ($requiredStatus):";
    }

    /**
     * Sets question validation
     *
     * @param Question $question
     * @param string $fieldName
     * @return Question
     */
    private function setQuestionValidation(Question $question, string $fieldName)
    {
        $question->setValidator(function ($answer) use ($fieldName) {
            if (empty($answer)) {
                throw new \RuntimeException("$fieldName cannot be empty.");
            }

            return $answer;
        });

        return $question;
    }
}
