<div id="tabela">
    <style>
        .tabela_docigp {
            border:1px solid #C0C0C0;
            border-collapse:collapse;
            padding:5px;
            width: 95%;
            margin: auto;
            margin-top: 30px;
        }

        .tabela_docigp th {
            border:1px solid #C0C0C0;
            padding:5px;
            background:#F0F0F0;
        }

        .tabela_docigp th:first-child {
            width: 200px;
        }
        .tabela_docigp td {
            border:1px solid #C0C0C0;
            padding:5px;
        }
        .tabela_docigp td:first-child {
            text-align: right;
            padding-right: 15px;
            font-weight: bold;
        }
        .tabela_docigp td:last-child {
            font-weight: bold;
        }

    </style>

    <table class="tabela_docigp">
        <caption>DEPESAS POR CENTRO DE CUSTO</caption>

        <thead>
        <tr>
            @forEach($mainTable[0] as $column)
                <td>{{$column}}</td>
            @endForEach
        </tr>
        </thead>
        <tbody>


        @foreach($mainTable as $key => $row)
            @if($key > 0)
                <tr>
                    @forEach($row as $column)
                        <td>{{$column}}</td>
                    @endForEach
                </tr>
            @endIf
        @endForEach

        <tbody>
    </table>
</div>
