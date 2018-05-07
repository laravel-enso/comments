@extends('emails.layouts.main')

@section('css')
    <style>
        code {
            background: #F8F8FF;
            padding: 6px
        }
    </style>
@endsection

@section('content')
    <table style="margin: 15px auto;" cellspacing="0" cellpadding="0" class="force-width-80">
        <tr>
            <td style="text-align:left; color: #6f6f6f;" class="spaced-out-lines code">

                <p>
                {{$intro}}
                </p>

                <p>
                    <code>
                        {{$messageBody}}
                    </code>
                </p>

                <p>
                    {{$action}}
                </p>

                <p>
                    {{$ending}}
                </p>
            </td>
        </tr>
    </table>
@endsection

@section('buttons')
    <a href="{{$appURL}}" style="background-color:#f5774e;color:#ffffff;display:inline-block;font-family:'Source Sans Pro', Helvetica, Arial, sans-serif;font-size:18px;font-weight:400;line-height:45px;text-align:center;text-decoration:none;width:220px;-webkit-text-size-adjust:none;">{{$appName}}</a>
@endsection