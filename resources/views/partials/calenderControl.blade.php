<span style="display: inline-block;">
    {!! Form::open(array('route' => array('timeCard.show', appGlobals()->getBeginningOfPreviousWeek($timeCardRange)))) !!}
        <input type="hidden" name="_method" value="GET">
        <button type ="submit" style="background: darkgray">
            <span class="glyphicon glyphicon-triangle-left"></span>
        </button>
    {!! Form::close() !!}
</span>
<span style="display: inline-block;">
    {!! Form::open(array('route' => array('timeCard.show', appGlobals()->getBeginningOfCurrentWeek($timeCardRange))
                                                         ,'id'    => 'formNext')) !!}
        <input type="hidden" name="_method" value="GET">
        <input id="specificDay" type="hidden" name="specificDay" value="">
        <input id="timeCardCalendar" style="background: darkgray; border: inset; width: 185px" value="{{$timeCardRange}}">
    {!! Form::close() !!}
</span>
<span style="display: inline-block;">
    {!! Form::open(array('route' => array('timeCard.show', appGlobals()->getBeginningOfNextWeek($timeCardRange)))) !!}
        <input type="hidden" name="_method" value="GET">
        <button type ="submit" style="background: darkgray">
            <span class="glyphicon glyphicon-triangle-right"></span>
        </button>
    {!! Form::close() !!}
</span>

