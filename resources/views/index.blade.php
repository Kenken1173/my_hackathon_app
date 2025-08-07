<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @foreach ( $goals as $goal)
        <p> {{ $goal["name"] }} </p>
        @foreach ($goal["milestones"] as $milestone)
            <p> {{   $milestone["name"] }} </p>
        @endforeach
    @endforeach
</html>