@props(['url'])
<tr>
<td style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 50%, #0c4a6e 100%); padding: 25px 0;" align="center">
<table width="570" cellpadding="0" cellspacing="0" role="presentation" style="margin: 0 auto;">
<tr>
<td style="text-align: center;">
<a href="{{ $url }}" style="display: inline-block; text-decoration: none;">
@if (trim($slot) === 'Laravel')
<h1 style="font-family: 'Poppins', Arial, sans-serif; font-size: 48px; font-weight: bold; color: #ffffff; margin: 0; padding: 0; letter-spacing: 2px;">SIRAS</h1>
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
</table>
</td>
</tr>
