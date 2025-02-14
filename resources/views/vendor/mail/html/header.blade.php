@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="{{ url('https://i.ibb.co/H2jfkgd/logo1.png') }}" class="logo" alt="Peak Pulse Market Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
