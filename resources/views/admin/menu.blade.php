<div id='cssmenu'>
    <ul>
        <li class='active has-sub'><a href='#'><span>Notifications</span></a>
        <ul>
            <li><a href="{{route('notifications')}}"><span>Send notifications to all users</span></a></li>
            <li><a href="{{route('notificationsPersons')}}"><span>Send notifications to specific user/users</span></a></li>
        </ul>
        </li>
        <li class='active has-sub'><a href='#'><span>Text tools</span></a>
            <ul>
                <li><a href="{{route('snippets')}}"><span>Update text snippets</span></a></li>
            </ul>
        </li>
        <li class='active has-sub'><a href='#'><span>Round results</span></a>
            <ul>
                <li><a href="{{route('roundResults',['round'=>1])}}"><span>Round 1</span></a></li>
                <li><a href="{{route('roundResults',['round'=>2])}}"><span>Round 2</span></a></li>
                <li><a href="{{route('roundResults',['round'=>3])}}"><span>Round 3</span></a></li>
                <li><a href="{{route('roundResults',['round'=>4])}}"><span>Round 4</span></a></li>
                <li><a href="{{route('roundResults',['round'=>10])}}"><span>All rounds</span></a></li>
            </ul>
        </li>
<li class='active has-sub'><a href='#'><span>Game data</span></a>
            <ul>
                <li><a href="{{url('/admin/rounds/grid')}}"><span>Rounds</span></a></li>
                <li><a href="{{url('/admin/results/grid')}}"><span>Match results</span></a></li>
                <li><a href="{{url('/admin/matches/grid')}}"><span>Matches</span></a></li>
            </ul>
        </li>



    </ul>
</div>
