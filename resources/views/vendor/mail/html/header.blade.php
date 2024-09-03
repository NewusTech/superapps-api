@props(['url'])
<tr>
<td class="header">
<a href="https://ramatranz.co.id" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://newus-bucket.s3.ap-southeast-2.amazonaws.com/superapps/assets/Icon.png" class="logo" alt="RamaTranz Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
