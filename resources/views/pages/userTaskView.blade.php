@extends('pages.userMainView')

@section('content')

    <form class="contact_form" action="{{ route('survey.store') }}" method="post" name="contact_form" autocomplete="off">
        {{ csrf_field() }}
        <ul>
            <li>
                <h2>Bull Creek Data "Free Day" Survey</h2>
                <span class="required_notification">Please enter all of the below fields</span>
            </li>
            <li>
                <label for="name">Name:</label>
                <input type="text" name="name" placeholder="John Doe" required/>
            </li>
            <li>
                <label for="email">Email:</label>
                <input type="email" name="email" placeholder="john_doe@example.com" required/>
                <span class="form_hint"></span>
            </li>
            <li>
                <label for="website">Website:</label>
                <input type="url" name="website" placeholder="http://johndoe.com/" required/>
                {{--<span class="form_hint">Proper format http://someaddress.com</span>--}}
            </li>
            <li>
                <label for="message">Message:</label>
                <textarea name="message" cols="40" rows="6" required></textarea>
            </li>
            <li>
                <button class="submit" type="submit">Submit Form</button>
            </li>
        </ul>
    </form>


    <h1 class="page-heading">{{\app\Helpers\appGlobals::getTaskTableName()}} View</h1>

    <hr>

    <table class="table table-striped table-bordered">
        <head>
            <th>Name</th>
            <th>email</th>
            <th>website</th>
            <th>message</th>
            <th>survey sent</th>
        </head>

        <body>
        @foreach ($surveys as $survey)
            <tr>
                <td>{{ $survey->name }}</td>
                <td>{{ $survey->email }}</td>
                <td>{{ $survey->website }}</td>
                <td>{{ $survey->message }}</td>
                <td>{{ $survey->created_at->diffForHumans() }}</td>
            </tr>
        @endforeach
        </body>
    </table>

    @unless(count($surveys))
        <p class="text-center">No surveys have been added as yet</p>
    @endunless

@stop