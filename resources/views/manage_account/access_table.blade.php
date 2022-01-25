@foreach ($access as $user)
    @php
        $permissions = array_filter(array_keys($user->toArray()), function ($v) {
            return strpos($v, 'kelola_') !== false || strpos($v, 'transaksi') !== false;
        });
    @endphp
    <tr>
        <td>
            <span class="d-flex justify-content-start align-items-center">
                <img src="{{ asset('pictures/' . $user->foto) }}">
                <span class="ml-2">
                    <span class="d-block mb-1">{{ $user->nama }}</span>
                    <span class="txt-user-desc">{{ $user->role }} <i class="mdi mdi-checkbox-blank-circle dot"></i>
                        {{ $user->email }}</span>
                </span>
            </span>
        </td>
        @foreach ($permissions as $permission)
            <td class="text-center b-left" data-access="{{ $permission }}" data-user="{{ $user->user }}"
                data-role="{{ $user->role }}">
                @if ($user->$permission == 1)
                    <div class="btn-checkbox btn-access">
                        <i class="mdi mdi-checkbox-marked"></i>
                    </div>
                @else
                    <div class="btn-checkbox btn-non-access">
                        <i class="mdi mdi-checkbox-blank-outline"></i>
                    </div>
                @endif
            </td>
        @endforeach
    </tr>
@endforeach
