<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <title>test page</title>
    <a>{{ $goal["name"] }}</a>
    
    @foreach ($goal["milestones"] as $milestone)
    <p> {{  $milestone["name"] }} </p>
    @endforeach
</html>