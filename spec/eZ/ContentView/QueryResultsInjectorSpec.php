<?php

namespace spec\EzSystems\EzPlatformQueryFieldType\eZ\ContentView;

use eZ\Publish\Core\MVC\Symfony\View\Event\FilterViewParametersEvent;
use eZ\Publish\Core\MVC\Symfony\View\View;
use eZ\Publish\Core\MVC\Symfony\View\ViewEvents;
use EzSystems\EzPlatformQueryFieldType\API\QueryFieldServiceInterface;
use EzSystems\EzPlatformQueryFieldType\eZ\ContentView\QueryResultsInjector;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class QueryResultsInjectorSpec extends ObjectBehavior
{
    const FIELD_VIEW = 'line';
    const OTHER_VIEW = 'anything_else';
    const VIEWS = ['field' => self::FIELD_VIEW, 'item' => 'field'];

    function it_is_initializable()
    {
        $this->shouldHaveType(QueryResultsInjector::class);
    }

    function let(
        QueryFieldServiceInterface $queryFieldService,
        FilterViewParametersEvent $event,
        ParameterBagInterface $parameterBag,
        View $view
    )
    {
        $this->beConstructedWith($queryFieldService, self::VIEWS);
        $event->getView()->willReturn($view);
        $event->getParameterBag()->willReturn($parameterBag);
    }

    function it_throws_an_InvalidArgumentException_if_no_item_view_is_provided(QueryFieldServiceInterface $queryFieldService)
    {
        $this->beConstructedWith($queryFieldService, ['field' => self::FIELD_VIEW]);
        $this->shouldThrow(\InvalidArgumentException::class)->duringInstantiation();
    }

    function it_throws_an_InvalidArgumentException_if_no_field_view_is_provided(QueryFieldServiceInterface $queryFieldService)
    {
        $this->beConstructedWith($queryFieldService, ['item' => 'field']);
        $this->shouldThrow(\InvalidArgumentException::class)->duringInstantiation();
    }

    function it_is_an_event_subscriber()
    {
        $this->shouldHaveType(EventSubscriberInterface::class);
    }

    function it_subscribes_to_the_FILTER_VIEW_PARAMETERS_View_Event()
    {
        $this->getSubscribedEvents()->shouldSubscribeTo(ViewEvents::FILTER_VIEW_PARAMETERS);
    }

    function it_does_nothing_for_non_field_views(FilterViewParametersEvent $event, View $view)
    {
        $view->getViewType()->willReturn(self::OTHER_VIEW);
        $this->injectQueryResults($event);
        $event->getParameterBag()->shouldNotHaveBeenCalled();
    }

    function getMatchers(): array
    {
        return [
            'subscribeTo' => function($return, $event) {
                return is_array($return) && isset($return[$event]);
            }
        ];
    }
}
