<!-- new user registered -->
@component('mail::message')

# Anggota Baru Terdaftar

Halo {{ $credential['email'] }},
<br>
<br>
Selamat, Anda telah terdaftar sebagai anggota KSH.
<br>
<br>
Berikut adalah informasi akun Anda:
<br>
<br>
<table>
    <tr>
        <td>Email</td>
        <td>:</td>
        <td>{{ $credential['email'] }}</td>
    </tr>
    <tr>
        <td>Password</td>
        <td>:</td>
        <td>{{ $credential['password'] }}</td>
    </tr>
</table>

@component('mail::button', ['url' => route('login')])
    Login
@endcomponent

Terima kasih,<br>
{{ config('app.name') }}
@endcomponent

