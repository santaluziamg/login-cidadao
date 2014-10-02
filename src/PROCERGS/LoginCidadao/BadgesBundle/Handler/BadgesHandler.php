<?php

namespace PROCERGS\LoginCidadao\BadgesBundle\Handler;

use PROCERGS\LoginCidadao\BadgesBundle\Model\BadgeEvaluatorInterface;
use PROCERGS\LoginCidadao\CoreBundle\Model\PersonInterface;
use Symfony\Component\EventDispatcher\ContainerAwareEventDispatcher;
use PROCERGS\LoginCidadao\BadgesBundle\BadgesEvents;
use PROCERGS\LoginCidadao\BadgesBundle\Event\ListBadgesEvent;
use PROCERGS\LoginCidadao\BadgesBundle\Event\EvaluateBadgesEvent;

class BadgesHandler
{

    protected $evaluators;
    protected $dispatcher;

    public function __construct(ContainerAwareEventDispatcher $dispatcher)
    {
        $this->evaluators = array();
        $this->dispatcher = $dispatcher;
        
        $this->setup();
    }

    public function register(BadgeEvaluatorInterface $evaluator)
    {
        $id = $evaluator->getId();
        if (!array_key_exists($id, $this->evaluators)) {
            $this->evaluators[$evaluator->getId()] = $evaluator;
        }
        return $this;
    }

    /**
     * Evaluates the badges for a given person.
     * 
     * @param PersonInterface $person
     * @return PersonInterface instance with badges
     */
    public function evaluate(PersonInterface $person)
    {
        $event = new EvaluateBadgesEvent($person);
        $this->dispatcher->dispatch(BadgesEvents::BADGES_EVALUATE, $event);
        return $event->getPerson();
    }

    protected function setup()
    {
        
    }

    public function getAvailableBadges()
    {
        $event = new ListBadgesEvent();
        $this->dispatcher->dispatch(BadgesEvents::BADGES_LIST_AVAILABLE, $event);
        return $event->getBadges();
    }
    
}
