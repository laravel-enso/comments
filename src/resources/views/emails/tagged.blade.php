@extends('emails.layouts.main')

@section('css')
    <style>
        .code {
            background: #F8F8FF;
            padding: 6px
        }
    </style>
@endsection

@section('content')

    <table style="margin: 0 auto;" cellspacing="0" cellpadding="0" class="force-width-80">
        <tr>
            <td style="text-align:left; color: #6f6f6f;" class="spaced-out-lines">
                {{$line1}}
            </td>
        </tr>
    </table>

    <table style="margin: 15px auto;" cellspacing="0" cellpadding="0" class="force-width-80">
        <tr>
            <td style="text-align:left; color: #6f6f6f;" class="spaced-out-lines code">
                <code>
                    {{$messageBody}}
                </code>
            </td>
        </tr>
    </table>

    <table style="margin: 0 auto;" cellspacing="0" cellpadding="0" class="force-width-80">
        <tr>
            <td style="text-align:left; color: #6f6f6f;" class="spaced-out-lines">
                {{$line2}}
                <br>
                <br>
                {{$line3}}
                <br>
                <br>
            </td>
        </tr>
    </table>
@endsection