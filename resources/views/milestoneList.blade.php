<x-layout :username=$username title="Milestone list">
    <a>{{ $goal["name"] }}</a>
    @foreach ($goal["milestones"] as $milestone)
        <p> {{  $milestone["name"] }} </p>
    @endforeach

</x-layout>
