<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="http://apicms.sonnguyen.top/images/logo.png" class="logo" alt=" Logo" width="100">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
