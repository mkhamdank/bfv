<table>
    <thead>
        <tr>
            <th rowspan="2">Id</th>
            <th rowspan="2">Tanggal</th>
            <th rowspan="2">Molding</th>
            <th rowspan="2">PIC</th>
            <th rowspan="2">Poin Cek</th>

            <th colspan="3">Eviden</th>

            <th rowspan="2">Judgement</th>
            <th rowspan="2">Status</th>
        </tr>

        <tr>
            <th>Before</th>
            <th>After</th>
            <th>Aktifitas</th>
        </tr>
    </thead>

    <tbody>

        @foreach($records as $record)

            <tr>

                <td>
                    {{ $record->id }}
                </td>

                <td>
                    {{ $record->check_date }}
                </td>

                <td>
                    {{ $record->molding_name }}
                </td>

                <td>
                    {{ $record->pic_name ?? $record->pic }}
                </td>

                <td>
                    {{ $record->point_check }}
                </td>

                {{-- gambar dimasukkan dari controller --}}
                <td></td>

                <td></td>

                <td></td>

                <td>
                    {{ $record->judgement }}
                </td>

                <td>
                    {{ $record->status }}
                </td>

            </tr>

        @endforeach

    </tbody>
</table>