<?php

declare(strict_types=1);

namespace Neklo\Questions\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Neklo\Questions\Model\QuestionsFactory;
use Neklo\Questions\Model\ResourceModel\Answers\CollectionFactory as AnswersCollectionFactory;
use Neklo\Questions\Model\HelperData;

class QuestionsList implements ArgumentInterface
{
    /**
     * @param QuestionsFactory $questionsFactory
     * @param AnswersCollectionFactory $answersCollectionFactory
     * @param DateTime $dateTime
     * @param HelperData $helperData
     */
    public function __construct(
        private QuestionsFactory $questionsFactory,
        private AnswersCollectionFactory $answersCollectionFactory,
        private DateTime $dateTime,
        private HelperData $helperData
    ) {
    }

    /**
     * Retrieves a filtered list of questions with their corresponding answers.
     *
     * @return array
     */
    public function getFilteredQuestionsWithAnswers(): array
    {
        $questions = $this->getQuestionsCollection();
        $filteredQuestionsList = [];

        foreach ($questions as $question) {
            if ($this->shouldBeDisplayed($question)) {
                $answers = $this->getAnswersCollection((int) $question->getId());
                $filteredQuestionsList[] = [
                    'question' => $question->getData(),
                    'answers' => $answers
                ];
            }
        }

        return $filteredQuestionsList;
    }

    /**
     * Retrieves the collection of all Questions, ordered by ID descending.
     *
     * @return mixed
     */
    private function getQuestionsCollection(): mixed
    {
        $questions = $this->questionsFactory->create()->getCollection();
        $questions->setOrder('id', 'DESC');
        return $questions;
    }

    /**
     * Retrieves a collection of Answers for a given 'question_id'.
     *
     * @param int $questionId
     * @return array
     */
    private function getAnswersCollection(int $questionId): array
    {
        $answersCollection = $this->answersCollectionFactory->create()
            ->addFieldToFilter('question_id', $questionId)
            ->setOrder('id', 'DESC');

        $answers = [];
        foreach ($answersCollection as $answer) {
            $answers[] = $answer->getData();
        }
        return $answers;
    }

    /**
     * Determines if a question should be displayed based on the user's login status and the question's status.
     *
     * @param $question
     * @return bool
     */
    private function shouldBeDisplayed($question): bool
    {
        $currentCustomerId = (int) $this->getCurrentCustomerId();
        $isLoggedIn = (bool)$currentCustomerId;
        $isCustomerQuestion = (int) $question->getCustomerId() === $currentCustomerId;
        $isConfirmed = $question->getData('status') === 'confirmed';
        $isPending = $question->getData('status') === 'pending';

        if ($isLoggedIn) {
            return ($isCustomerQuestion && ($isConfirmed || $isPending)) || (!$isCustomerQuestion && $isConfirmed);
        }

        return $isConfirmed;
    }

    /**
     * Formats a date in a specified format.
     *
     * @param $date
     * @return string
     */
    public function formatDate($date): string
    {
        return $this->dateTime->date('d-m-y (H:i:s)', $date);
    }

    /**
     * Gets the 'ID' of the currently authorized customer. If the customer is not authorized, returns 'null'.
     *
     * @return int|null
     */
    public function getCurrentCustomerId(): int|null
    {
        $customerId = $this->helperData->getCustomerIdFromSession();
        return $customerId !== null ? (int) $customerId : null;
    }

    /**
     * Defines a CSS class for the question block depending on if the question belongs to the current authorized user.
     *
     * @param int $questionCustomerId
     * @return string
     */
    public function getQuestionBlockClass(int $questionCustomerId): string
    {
        $currentCustomerId = $this->getCurrentCustomerId();
        return $questionCustomerId === $currentCustomerId ? 'current-customer' : 'question-block';
    }
}
