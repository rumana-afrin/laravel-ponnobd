<x-mail::message>
<h2>Hey, It's me {{ $data['name'] }}</h2>
<br>
<strong>User details: </strong><br>
<strong>Name: </strong>{{ $data['name'] }} <br>
<strong>Email: </strong>{{ $data['email'] }} <br>
<strong>Message: </strong>{{ $data['message'] }} <br><br>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
