<div id="tabela">
    <style>

        .barra-titulo-tabela {
            text-align: center;
            background: #0d2954;
            color: #ffffff;
            padding-top: 3px;
            padding-bottom: 3px;
            margin-left: 0px ;
            border:1px solid #0d2954;
        }
        .tabela_docigp {
            border:1px solid #C0C0C0;
            border-collapse:collapse;
            padding:0px;
            width: 100%;
            margin: auto;
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
        .primeira-coluna {
            text-align: right;
            padding-right: 15px;
            font-weight: bold;
        }
        .tabela_docigp td:last-child {
            font-weight: bold;
        }

    </style>

    <div class="barra-titulo-tabela">DESPESAS POR CENTRO DE CUSTO</div>
    <table class="tabela_docigp">

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
                    @forEach($row as $key2 => $column)
                        @if($key2 > 0)
                            <td width="70px">{{$column}}</td>
                        @else
                            <td class="primeira-coluna">{{$column}}</td>
                        @endif
                    @endForEach
                </tr>
        @endIf
        @endForEach



        <tbody>
    </table>
</div>
