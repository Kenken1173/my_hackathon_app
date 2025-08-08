<x-layout :username=$username title="test">
    <a>{{ $goal["name"] }}</a>
    
    @foreach ($goal["milestones"] as $milestone)
    <p> {{  $milestone["name"] }} </p>
    @endforeach
</x-layout>