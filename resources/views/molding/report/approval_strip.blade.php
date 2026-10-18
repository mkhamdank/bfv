@if(isset($points) || (isset($approvals) && $approvals->isNotEmpty()))
<div style="display:grid; grid-template-columns:112px repeat({{ isset($approvals) ? $approvals->count() : 0 }}, 80px) 64px; gap:1px; justify-self:end; width:max-content;">
    <div class="flex flex-col text-center">
        <div class="border-box-thick flex items-center justify-center" style="height:24px; border-bottom:none; font-size:9px;">Poin</div>
        <div class="border-box-thick flex items-center justify-center text-3xl" style="height:48px; border-top-width:1px; background-color:#daeef3;">{{ $points ?? '' }}</div>
    </div>
    @foreach($approvals ?? [] as $approval)
    <div class="flex flex-col text-center">
        <div class="border-box-thick flex items-center justify-center" style="height:24px; border-bottom:none; font-size:9px;">
            {{ $approval->ordering >= 3 ? 'Konfirmasi' : 'Diperiksa' }}
        </div>
        <div class="border-box-thick flex items-center justify-center" style="height:30px; border-top-width:1px; overflow-wrap:anywhere; font-size:9px;">
            {{ $approval->app_name }}
        </div>
        <div class="border-box-thick flex items-center justify-center" style="height:18px; border-top:none; font-size:8px;">
            {{ in_array(strtolower((string) $approval->status), ['hold', 'holded'], true) ? 'Hold' : ($approval->app_at ?? '') }}
        </div>
    </div>
    @endforeach
    <div class="flex flex-col text-center">
        <div class="border-box-thick flex items-center justify-center" style="height:24px; border-bottom:none; font-size:9px;">
            Dibuat
        </div>
        <div class="border-box-thick flex items-center justify-center" style="height:30px; border-top-width:1px; overflow-wrap:anywhere; font-size:9px;">
            {{ $creator ?? '' }}
        </div>
        <div class="border-box-thick flex items-center justify-center" style="height:18px; border-top:none; font-size:8px;">
            {{ $createdAt ?? '' }}
        </div>
    </div>
</div>
@endif