<div style="font-family: Helvetica,Arial,sans-serif;min-width:1000px;overflow:auto;line-height:2">
    <div style="margin:50px auto;width:70%;padding:20px 0">
        <div style="border-bottom:1px solid #eee">
            <a href="" style="font-size:1.4em;color: #00466a;text-decoration:none;font-weight:600">Task
                Management</a>
        </div>
        <p style="font-size:1.1em">Hi,</p>
        <p>There is a new comment for you in {{ $task['name'] }}</p>
        <br/>
        <h4>{{ $task['comment_user_name'] }}</h4>
        <p>{{ $task['comment'] }}</p>
        <br/>
        <p style="font-size:0.9em;">Regards,<br/>Tanvir</p>
    </div>
</div>
